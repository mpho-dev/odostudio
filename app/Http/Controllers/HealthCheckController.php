<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\Invoice;
use App\Models\Media;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class HealthCheckController extends Controller
{
    /**
     * Display the system health check dashboard.
     */
    public function show()
    {
        $checks = $this->runAllChecks();
        $overview = $this->getSystemOverview();
        $alerts = $this->getActiveAlerts($checks);

        return view('admin.health-check', compact('checks', 'overview', 'alerts'));
    }

    /**
     * Run database connectivity test.
     */
    public function testDatabase()
    {
        try {
            $pdo = DB::connection()->getPdo();
            $databaseName = DB::connection()->getDatabaseName();
            $driverName = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
            $serverVersion = $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);

            // Get table count using schema-agnostic approach
            $tableCount = $this->getTableCount();

            $details = [
                'Database Name' => $databaseName,
                'Driver' => ucfirst($driverName),
                'Server Version' => $serverVersion,
                'Tables' => $tableCount . ' tables',
            ];

            return $this->jsonResponse(true, 'Database connection successful', 'success', $details);
        } catch (\Exception $e) {
            return $this->jsonResponse(false, 'Database error: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Get table count in a database-agnostic way.
     */
    private function getTableCount(): int
    {
        $driver = DB::connection()->getDriverName();

        try {
            if ($driver === 'sqlite') {
                // SQLite: count from sqlite_master
                $result = DB::select("SELECT COUNT(*) as count FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                return (int) ($result[0]->count ?? 0);
            } elseif (in_array($driver, ['mysql', 'pgsql'])) {
                // MySQL/PostgreSQL: use information_schema
                $databaseName = DB::connection()->getDatabaseName();
                $result = DB::select("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = ?", [$databaseName]);
                return (int) ($result[0]->count ?? 0);
            }
        } catch (\Exception $e) {
            // Fallback: try to count Laravel tables
        }

        // Fallback: count known Laravel tables
        $knownTables = ['users', 'bookings', 'booking_requests', 'invoices', 'media', 'services', 'projects', 'testimonials'];
        $count = 0;
        foreach ($knownTables as $table) {
            try {
                DB::connection()->getPdo()->query("SELECT 1 FROM $table LIMIT 1");
                $count++;
            } catch (\Exception $e) {
                // Table doesn't exist
            }
        }

        return $count;
    }

    /**
     * Run cache/storage test.
     */
    public function testCache()
    {
        try {
            $driver = config('cache.default');
            $key = 'health_check_' . time();
            Cache::put($key, 'test_value', 60);
            $value = Cache::get($key);
            Cache::forget($key);

            if ($value !== 'test_value') {
                throw new \Exception('Cache read-back failed — got: ' . var_export($value, true));
            }

            $details = [
                'Cache Driver' => ucfirst($driver),
                'Connection' => 'Read/Write test passed',
                'Default TTL' => $this->getCacheTimeout(),
            ];

            return $this->jsonResponse(true, 'Cache system working', 'success', $details);
        } catch (\Exception $e) {
            return $this->jsonResponse(false, 'Cache error: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Get cache timeout description.
     */
    private function getCacheTimeout(): string
    {
        $driver = config('cache.default');
        $store = config('cache.stores.' . $driver);

        if (isset($store['timeout'])) {
            return ($store['timeout'] / 60) . ' minutes';
        }

        return 'Default (forever)';
    }

    /**
     * Run email configuration test.
     */
    public function testEmail()
    {
        try {
            $config = DB::table('email_configurations')->get();
            
            $details = [];
            $hasConfig = false;
            
            if ($config->count() > 0) {
                $hasConfig = true;
                foreach ($config as $item) {
                    $details[$item->type ?? 'Setting'] = $item->subject ?? 'Configured';
                }
            }

            if (!$hasConfig) {
                $details = [
                    'Status' => 'No email templates configured',
                    'Action Required' => 'Configure email templates in Admin > Email Configuration',
                ];
                return $this->jsonResponse(false, 'Email not configured', 'warning', $details);
            }

            return $this->jsonResponse(true, 'Email configured (' . $config->count() . ' templates)', 'success', $details);
        } catch (\Exception $e) {
            return $this->jsonResponse(false, 'Email configuration error: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Run storage test.
     */
    public function testStorage()
    {
        try {
            $path = storage_path('app/health_check_' . time() . '.txt');
            file_put_contents($path, 'test');
            @unlink($path);

            $disk = config('filesystems.default');
            $root = config('filesystems.disks.' . $disk . '.root');
            $details = [
                'Filesystem Driver' => ucfirst($disk),
                'Root Path' => $root,
                'Permissions' => 'Read/Write test passed',
            ];

            return $this->jsonResponse(true, 'Storage writable', 'success', $details);
        } catch (\Exception $e) {
            return $this->jsonResponse(false, 'Storage error: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Run role system test.
     */
    public function testRoles()
    {
        try {
            $roles = Role::all();
            $roleNames = $roles->pluck('name')->toArray();
            $permissionsCount = \DB::table('permissions')->count();
            
            $details = [
                'Roles Count' => $roles->count(),
                'Available Roles' => implode(', ', $roleNames),
                'Total Permissions' => $permissionsCount,
            ];

            if ($roles->count() >= 3) {
                return $this->jsonResponse(true, 'Roles set up (' . $roles->count() . ' roles)', 'success', $details);
            }

            $details['Warning'] = 'Expected at least 3 roles (admin, manager, photographer)';
            return $this->jsonResponse(false, 'Roles not fully configured', 'warning', $details);
        } catch (\Exception $e) {
            return $this->jsonResponse(false, 'Role system error: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Get system statistics.
     */
    public function getStatistics()
    {
        try {
            return response()->json([
                'users' => User::count(),
                'booking_requests' => BookingRequest::count(),
                'bookings' => Booking::count(),
                'invoices' => Invoice::count(),
                'media_items' => Media::count(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Run all health checks.
     */
    private function runAllChecks()
    {
        return [
            'database' => $this->runCheck('database'),
            'cache' => $this->runCheck('cache'),
            'email' => $this->runCheck('email'),
            'storage' => $this->runCheck('storage'),
            'roles' => $this->runCheck('roles'),
            'disk' => $this->runCheck('disk'),
            'queue' => $this->runCheck('queue'),
            'application' => $this->runCheck('application'),
        ];
    }

    /**
     * Run individual check and return result.
     */
    private function runCheck($check)
    {
        // Whitelist of allowed checks with their corresponding methods
        $allowedChecks = [
            'database' => 'testDatabase',
            'cache' => 'testCache',
            'email' => 'testEmail',
            'storage' => 'testStorage',
            'roles' => 'testRoles',
            'disk' => 'testDisk',
            'queue' => 'testQueue',
            'application' => 'testApplication',
        ];

        // Validate that the check is in the whitelist
        if (!isset($allowedChecks[$check]) || !method_exists($this, $allowedChecks[$check])) {
            return [
                'status' => false,
                'message' => 'Invalid health check requested',
                'type' => 'error',
            ];
        }

        try {
            $method = $allowedChecks[$check];
            $response = $this->$method();
            $data = json_decode($response->getContent(), true);

            return [
                'status' => $data['success'] ?? false,
                'message' => $data['message'] ?? 'Unknown error',
                'type' => $data['type'] ?? 'success',
                'details' => $data['details'] ?? [],
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'type' => 'error',
            ];
        }
    }

    /**
     * Get system overview metrics.
     */
    private function getSystemOverview(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => \Illuminate\Foundation\Application::VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'timezone' => config('app.timezone'),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'url' => config('app.url'),
        ];
    }

    /**
     * Get active alerts based on check results.
     */
    private function getActiveAlerts(array $checks): array
    {
        $alerts = [];

        foreach ($checks as $key => $check) {
            if (!$check['status'] || $check['type'] === 'warning') {
                $alerts[] = [
                    'type' => $check['type'] ?? 'error',
                    'source' => ucfirst($key),
                    'message' => $check['message'],
                    'action' => $this->getRecommendedAction($key, $check),
                ];
            }
        }

        return $alerts;
    }

    /**
     * Get recommended action for an alert.
     */
    private function getRecommendedAction(string $check, array $result): ?string
    {
        $actions = [
            'database' => 'Check database credentials in .env and verify MySQL/PostgreSQL is running.',
            'cache' => 'Verify cache driver configuration. For Redis, check if Redis server is running.',
            'email' => 'Configure email templates in Admin > Email Configuration.',
            'storage' => 'Check storage directory permissions (chmod -R 775 storage).',
            'roles' => 'Run php artisan db:seed to initialize roles and permissions.',
            'queue' => 'Start queue worker with: php artisan queue:work',
            'disk' => 'Free up disk space or expand storage volume.',
        ];

        return $actions[$check] ?? null;
    }

    /**
     * Test disk space availability.
     */
    public function testDisk()
    {
        try {
            $total = disk_total_space(storage_path());
            $free = disk_free_space(storage_path());
            $used = $total - $free;
            $usagePercent = round(($used / $total) * 100, 2);

            $status = $usagePercent > 90 ? 'error' : ($usagePercent > 75 ? 'warning' : 'success');
            $success = $usagePercent < 90;

            $details = [
                'Total Space' => $this->formatBytes($total),
                'Used Space' => $this->formatBytes($used),
                'Free Space' => $this->formatBytes($free),
                'Usage' => $usagePercent . '%',
            ];

            $message = $success
                ? "Disk space healthy ({$usagePercent}% used)"
                : "Disk space critical ({$usagePercent}% used)";

            return $this->jsonResponse($success, $message, $status, $details);
        } catch (\Exception $e) {
            return $this->jsonResponse(false, 'Disk check error: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Test queue status.
     */
    public function testQueue()
    {
        try {
            $connection = config('queue.default');
            $failedJobs = \DB::table('failed_jobs')->count();
            
            // Check queue size for database driver
            $pendingJobs = 0;
            if ($connection === 'database') {
                $pendingJobs = \DB::table('jobs')->count();
            }

            $success = $failedJobs === 0;
            $status = $failedJobs > 10 ? 'error' : ($failedJobs > 0 ? 'warning' : 'success');

            $details = [
                'Driver' => ucfirst($connection),
                'Failed Jobs' => $failedJobs,
                'Pending Jobs' => $pendingJobs,
            ];

            $message = $success
                ? "Queue system healthy ({$failedJobs} failed)"
                : "Queue has {$failedJobs} failed jobs requiring attention";

            return $this->jsonResponse($success, $message, $status, $details);
        } catch (\Exception $e) {
            return $this->jsonResponse(false, 'Queue check error: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Test application version and environment.
     */
    public function testApplication()
    {
        try {
            $env = config('app.env');
            $debug = config('app.debug');
            $maintenance = app()->isDownForMaintenance();

            $details = [
                'Environment' => ucfirst($env),
                'Debug Mode' => $debug ? 'Enabled' : 'Disabled',
                'Maintenance' => $maintenance ? 'Active' : 'Inactive',
                'PHP Version' => PHP_VERSION,
                'Laravel Version' => \Illuminate\Foundation\Application::VERSION,
            ];

            $success = !$maintenance;
            $status = $maintenance ? 'warning' : 'success';
            $message = $maintenance
                ? 'Application in maintenance mode'
                : 'Application running normally';

            return $this->jsonResponse($success, $message, $status, $details);
        } catch (\Exception $e) {
            return $this->jsonResponse(false, 'Application check error: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Test database performance metrics.
     */
    public function testDatabasePerformance()
    {
        try {
            $start = microtime(true);
            DB::select('SELECT 1');
            $responseTime = round((microtime(true) - $start) * 1000, 2);

            // Get connection count if MySQL
            $connections = 'N/A';
            if (DB::getDriverName() === 'mysql') {
                $result = DB::select("SHOW STATUS LIKE 'Threads_connected'");
                $connections = $result[0]->Value ?? 'N/A';
            }

            $slowQueries = DB::table('slow_queries')->count();

            $success = $responseTime < 100;
            $status = $responseTime > 500 ? 'error' : ($responseTime > 100 ? 'warning' : 'success');

            $details = [
                'Response Time' => $responseTime . 'ms',
                'Active Connections' => $connections,
                'Slow Queries' => $slowQueries,
            ];

            $message = $success
                ? "Database performing well ({$responseTime}ms response)"
                : "Database slow ({$responseTime}ms response)";

            return $this->jsonResponse($success, $message, $status, $details);
        } catch (\Exception $e) {
            return $this->jsonResponse(false, 'Database performance error: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Format bytes to human readable.
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Return JSON response.
     */
    private function jsonResponse(bool $success, string $message, ?string $type = null, array $details = [])
    {
        if ($type === null) {
            $type = $success ? 'success' : 'error';
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'type' => $type,
            'details' => $details,
        ]);
    }
}

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingRequestController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\EquipmentCategoryController;
use App\Http\Controllers\EquipmentCheckoutController;
use App\Http\Controllers\EquipmentItemController;
use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\InvestmentTierController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NotificationPreferenceController;
use App\Http\Controllers\ProcessStepController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\UserManagementController;
use App\Models\InvestmentTier;
use App\Models\ProcessStep;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    $services = Service::orderBy('order')->get();
    $settings = \App\Models\SiteSetting::all()->pluck('value', 'key')->toArray();
    $processSteps = ProcessStep::orderBy('display_order')->get();
    $investmentTiers = InvestmentTier::orderBy('order')->get();
    $testimonials = Testimonial::orderBy('order')->get();

    return view('welcome', compact('services', 'settings', 'processSteps', 'investmentTiers', 'testimonials'));
})->name('home');

Route::get('/portfolio', [MediaController::class, 'portfolio'])->name('portfolio');
Route::get('/portfolio/{project:slug}', [MediaController::class, 'showProject'])->name('portfolio.project');
Route::get('/contact', [BookingRequestController::class, 'showForm'])->name('contact.form');
Route::post('/contact', [BookingRequestController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Dashboard route - redirects based on user role
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.documentation');
    } elseif ($user->hasRole('manager')) {
        return redirect()->route('manager.dashboard');
    } else {
        return redirect()->route('photographer.calendar');
    }
})->middleware('auth')->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Invoices are internally authorized via InvoicePolicy
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{booking}/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices/{booking}', [InvoiceController::class, 'store'])->middleware('throttle:10,1')->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::patch('/invoices/{invoice}/sent', [InvoiceController::class, 'markAsSent'])->middleware('throttle:20,1')->name('invoices.sent');
    Route::patch('/invoices/{invoice}/pay', [InvoiceController::class, 'markPaid'])->middleware('throttle:20,1')->name('invoices.pay');
});

// Manager routes
Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/manager/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');
    Route::get('/manager/requests', [BookingController::class, 'indexRequests'])->name('requests.index');
    Route::get('/manager/bookings/{bookingRequest}/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/manager/bookings/{bookingRequest}', [BookingController::class, 'store'])->middleware('throttle:10,1')->name('bookings.store');
    Route::post('/manager/requests/{bookingRequest}/crew', [BookingRequestController::class, 'assignCrew'])->name('requests.crew.assign');
    Route::delete('/manager/requests/{bookingRequest}/crew/{user}', [BookingRequestController::class, 'removeCrew'])->name('requests.crew.remove');

    // Equipment Assignment
    Route::get('/manager/bookings/{booking}/equipment', [EquipmentCheckoutController::class, 'assign'])->name('manager.equipment.assign');
    Route::post('/manager/bookings/{booking}/equipment', [EquipmentCheckoutController::class, 'store'])->name('manager.equipment.store');
    Route::delete('/manager/equipment-checkouts/{checkout}', [EquipmentCheckoutController::class, 'remove'])->name('manager.equipment.remove');
});

// Photographer routes
Route::middleware(['auth', 'role:crew'])->group(function () {
    Route::get('/photographer/calendar', [BookingController::class, 'calendar'])->name('photographer.calendar');
    Route::get('/photographer/calendar/events', [BookingController::class, 'calendarEvents'])->name('photographer.calendar.events');
    Route::get('/photographer/requests', [BookingRequestController::class, 'index'])->name('photographer.requests');
    Route::get('/photographer/requests/{bookingRequest}', [BookingRequestController::class, 'show'])->name('photographer.requests.show');
    Route::get('/photographer/preferences', [NotificationPreferenceController::class, 'edit'])->name('photographer.preferences');
    Route::patch('/photographer/preferences', [NotificationPreferenceController::class, 'update'])->name('photographer.preferences.update');

    // Equipment Management
    Route::get('/photographer/my-gear', [EquipmentCheckoutController::class, 'myGear'])->name('photographer.equipment.my-gear');
    Route::patch('/photographer/equipment-checkouts/{checkout}/checkin', [EquipmentCheckoutController::class, 'checkIn'])->name('photographer.equipment.checkin');
});

// Admin routes
Route::middleware(['auth', 'role:admin', 'cross_origin_isolation'])->group(function () {
    Route::get('/admin/email-config', [AdminController::class, 'editEmailConfig'])->name('admin.email-config');
    Route::post('/admin/email-config', [AdminController::class, 'updateEmailConfig'])->middleware('throttle:5,1')->name('admin.email-config.update');
    Route::get('/admin/email-config/preview/{slug}', [AdminController::class, 'previewEmailConfig'])->name('admin.email-config.preview');
    Route::post('/admin/email-config/send-test', [AdminController::class, 'sendTestEmail'])->middleware('throttle:5,1')->name('admin.email-config.test');

    Route::get('/admin/site-config', [SiteSettingController::class, 'edit'])->name('admin.site-config');
    Route::post('/admin/site-config', [SiteSettingController::class, 'update'])->middleware('throttle:5,1')->name('admin.site-config.update');

    Route::get('/admin/system-health', [HealthCheckController::class, 'show'])->name('admin.health-check');
    Route::get('/admin/documentation', [DocumentationController::class, 'show'])->name('admin.documentation');

    Route::get('/api/health/database', [HealthCheckController::class, 'testDatabase']);
    Route::get('/api/health/cache', [HealthCheckController::class, 'testCache']);
    Route::get('/api/health/email', [HealthCheckController::class, 'testEmail']);
    Route::get('/api/health/storage', [HealthCheckController::class, 'testStorage']);
    Route::get('/api/health/roles', [HealthCheckController::class, 'testRoles']);
    Route::get('/api/health/disk', [HealthCheckController::class, 'testDisk']);
    Route::get('/api/health/queue', [HealthCheckController::class, 'testQueue']);
    Route::get('/api/health/application', [HealthCheckController::class, 'testApplication']);
    Route::get('/api/health/statistics', [HealthCheckController::class, 'getStatistics']);

    Route::patch('/admin/bookings/{id}/restore', [BookingController::class, 'restore'])->name('bookings.restore');
    Route::post('/admin/bookings/{booking}/restore-cancellation', [BookingController::class, 'restoreFromCancellation'])->name('bookings.restore-cancellation');
    Route::delete('/admin/bookings/{id}/force-delete', [BookingController::class, 'forceDelete'])->name('bookings.forceDelete');

    Route::resource('/admin/services', ServiceController::class);
    Route::resource('/admin/projects', ProjectController::class);
    Route::resource('/admin/process-steps', ProcessStepController::class);
    Route::resource('/admin/investment-tiers', InvestmentTierController::class);
    Route::resource('/admin/testimonials', TestimonialController::class);
    Route::resource('/admin/users', UserManagementController::class)->names('admin.users');

    // Equipment Management
    Route::get('/admin/equipment/categories', [EquipmentCategoryController::class, 'index'])->name('admin.equipment.categories.index');
    Route::post('/admin/equipment/categories', [EquipmentCategoryController::class, 'store'])->name('admin.equipment.categories.store');
    Route::patch('/admin/equipment/categories/{category}', [EquipmentCategoryController::class, 'update'])->name('admin.equipment.categories.update');
    Route::delete('/admin/equipment/categories/{category}', [EquipmentCategoryController::class, 'destroy'])->name('admin.equipment.categories.destroy');

    Route::get('/admin/equipment', [EquipmentItemController::class, 'index'])->name('admin.equipment.items.index');
    Route::get('/admin/equipment/create', [EquipmentItemController::class, 'create'])->name('admin.equipment.items.create');
    Route::post('/admin/equipment', [EquipmentItemController::class, 'store'])->name('admin.equipment.items.store');
    Route::get('/admin/equipment/{item}', [EquipmentItemController::class, 'show'])->name('admin.equipment.items.show');
    Route::get('/admin/equipment/{item}/edit', [EquipmentItemController::class, 'edit'])->name('admin.equipment.items.edit');
    Route::patch('/admin/equipment/{item}', [EquipmentItemController::class, 'update'])->name('admin.equipment.items.update');
    Route::delete('/admin/equipment/{item}', [EquipmentItemController::class, 'destroy'])->name('admin.equipment.items.destroy');

    Route::get('/admin/equipment/reports/overdue', [EquipmentCheckoutController::class, 'overdue'])->name('admin.equipment.overdue');
});

// Documentation routes (public - anyone can read)
Route::get('/docs', [DocumentationController::class, 'show'])->name('docs.index');
Route::get('/docs/{doc}', [DocumentationController::class, 'show'])->name('docs.show');

// Media (admins and photographers can browse and upload)
Route::middleware(['auth', 'role:admin|crew'])->group(function () {
    Route::get('/admin/media', [MediaController::class, 'index'])->name('admin.media.index');
    Route::post('/admin/media', [MediaController::class, 'store'])->middleware('throttle:10,1')->name('media.store');
    Route::patch('/admin/media/{media}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('/admin/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::post('/admin/media/bulk-add-to-collection', [MediaController::class, 'bulkAddToCollection'])->name('media.bulk-add-to-collection');

    // Tag search API
    Route::get('/api/tags', [TagController::class, 'search'])->name('tags.search');
    Route::post('/api/tags', [TagController::class, 'store'])->name('tags.store');
});

// Collection management (admin only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/collections', [CollectionController::class, 'index'])->name('admin.collections.index');
    Route::post('/admin/collections', [CollectionController::class, 'store'])->name('admin.collections.store');
    Route::get('/admin/collections/{collection}/edit', [CollectionController::class, 'edit'])->name('admin.collections.edit');
    Route::patch('/admin/collections/{collection}', [CollectionController::class, 'update'])->name('admin.collections.update');
    Route::delete('/admin/collections/{collection}', [CollectionController::class, 'destroy'])->name('admin.collections.destroy');
});

// Bookings (accessible to both manager and photographer)
Route::middleware(['auth'])->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::patch('/bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
});

require __DIR__.'/auth.php';

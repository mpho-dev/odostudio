@extends('layouts.app')

@section('title', 'System Vitals — Odo Studio')

@section('header', 'System Vitals')

@section('content')
<div class="space-y-12 pb-12">
    <!-- Header Section with System Overview -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 reveal">
        <div>
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Infrastructure</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">System <em>Vitals</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Real-time surveillance of Odo Studio core systems</p>
        </div>
        <div class="flex items-center gap-3 text-xs text-ash/50">
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-gold"></span>
                Laravel {{ \Illuminate\Foundation\Application::VERSION }}
            </span>
            <span class="text-ash/30">|</span>
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-ash"></span>
                PHP {{ PHP_VERSION }}
            </span>
            <span class="text-ash/30">|</span>
            <span class="px-2 py-1 rounded bg-charcoal/50 border border-white/10 {{ config('app.env') === 'production' ? 'text-gold' : 'text-ash' }}">
                {{ ucfirst(config('app.env')) }}
            </span>
        </div>
    </div>

    <!-- Active Alerts Section -->
    <div id="alerts-container" class="reveal hidden" style="animation-delay: 50ms">
        <div class="flex items-center gap-4 mb-4">
            <span class="text-xs tracking-[0.4em] uppercase text-red-400 font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Active Alerts (<span id="alert-count">0</span>)
            </span>
            <div class="h-px flex-1 bg-red-500/20"></div>
        </div>
        <div id="alerts-list" class="space-y-3"></div>
    </div>

    <!-- Health Check Cards with Collapsible Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $checks = [
                ['id' => 'database', 'title' => 'Core Database', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>', 'description' => 'Database connection and configuration'],
                ['id' => 'cache', 'title' => 'Velocity Cache', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>', 'description' => 'Caching system performance'],
                ['id' => 'email', 'title' => 'Comm Infrastructure', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>', 'description' => 'Email configuration status'],
                ['id' => 'storage', 'title' => 'Vault Storage', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>', 'description' => 'File storage system'],
                ['id' => 'roles', 'title' => 'Permission Engine', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>', 'description' => 'Roles and permissions'],
                ['id' => 'disk', 'title' => 'Disk Space', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>', 'description' => 'Storage capacity monitoring'],
                ['id' => 'queue', 'title' => 'Job Queue', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>', 'description' => 'Queue worker and job status'],
                ['id' => 'application', 'title' => 'Application', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>', 'description' => 'App version and environment'],
            ];
        @endphp

        @foreach($checks as $check)
            @php
                $hasDetails = in_array($check['id'], ['database', 'disk', 'queue', 'application', 'roles']);
            @endphp
            <div class="health-check-card group" data-check="{{ $check['id'] }}" data-has-details="{{ $hasDetails ? 'true' : 'false' }}">
                <div class="glass group relative rounded-xl border border-white/10 overflow-hidden transition-all duration-500 hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5">
                    <!-- Card Header -->
                    <div class="p-6 {{ $hasDetails ? 'cursor-pointer' : '' }}" onclick="{{ $hasDetails ? "toggleDetails('" . $check['id'] . "')" : '' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-gold/10 flex items-center justify-center text-gold">
                                    {!! $check['icon'] !!}
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-white tracking-wide">{{ $check['title'] }}</h3>
                                    <p class="text-xs text-ash/50 mt-0.5">{{ $check['description'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="status-indicator w-3 h-3 rounded-full bg-iron/30"></div>
                                @if($hasDetails)
                                    <svg class="w-5 h-5 text-ash/40 transform transition-transform duration-300 expand-icon" id="icon-{{ $check['id'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Status Message -->
                        <div class="mt-4">
                            <p class="status-message text-sm text-ash/60 italic">Initializing check...</p>
                        </div>
                        
                        <!-- Progress Bar with Percentage -->
                        <div class="mt-3">
                            <div class="flex justify-between text-xs text-ash/40 mb-1">
                                <span>Health Score</span>
                                <span class="health-score">--%</span>
                            </div>
                            <div class="h-2 bg-charcoal/50 rounded-full overflow-hidden">
                                <div class="status-progress h-full bg-gold/30 w-0 transition-all duration-700 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    
                    @if($hasDetails)
                        <!-- Collapsible Details -->
                        <div class="details-panel hidden border-t border-white/5" id="details-{{ $check['id'] }}">
                            <div class="p-6 bg-charcoal/20">
                                <h4 class="text-xs font-bold text-ash/40 uppercase tracking-wider mb-4">Configuration Details</h4>
                                <div class="details-content space-y-3" id="details-content-{{ $check['id'] }}">
                                    <p class="text-xs text-ash/50 italic">Loading...</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- System Overview Panel -->
    <div class="reveal pt-4" style="animation-delay: 100ms">
        <div class="flex items-center gap-4 mb-6">
            <span class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>System Overview</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </span>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="glass p-5 text-center border border-white/10 hover:border-gold/20 transition-all">
                <p class="text-[0.55rem] tracking-[0.15em] uppercase text-ash/50 mb-1">Environment</p>
                <p class="text-sm font-medium {{ config('app.env') === 'production' ? 'text-gold' : 'text-ash' }}">{{ ucfirst(config('app.env')) }}</p>
            </div>
            <div class="glass p-5 text-center border border-white/10 hover:border-gold/20 transition-all">
                <p class="text-[0.55rem] tracking-[0.15em] uppercase text-ash/50 mb-1">Debug Mode</p>
                <p class="text-sm font-medium {{ config('app.debug') ? 'text-gold' : 'text-white' }}">{{ config('app.debug') ? 'Enabled' : 'Disabled' }}</p>
            </div>
            <div class="glass p-5 text-center border border-white/10 hover:border-gold/20 transition-all">
                <p class="text-[0.55rem] tracking-[0.15em] uppercase text-ash/50 mb-1">Timezone</p>
                <p class="text-sm font-medium text-white">{{ config('app.timezone') }}</p>
            </div>
            <div class="glass p-5 text-center border border-white/10 hover:border-gold/20 transition-all">
                <p class="text-[0.55rem] tracking-[0.15em] uppercase text-ash/50 mb-1">Server</p>
                <p class="text-sm font-medium text-white truncate" title="{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}">{{ Str::limit($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown', 20) }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="reveal pt-4" style="animation-delay: 150ms">
        <div class="flex items-center gap-4 mb-6">
            <span class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Archive Statistics</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </span>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @php
                $stats = [
                    ['id' => 'stat-users', 'label' => 'Total Users'],
                    ['id' => 'stat-requests', 'label' => 'Enquiries'],
                    ['id' => 'stat-bookings', 'label' => 'Productions'],
                    ['id' => 'stat-invoices', 'label' => 'Financials'],
                    ['id' => 'stat-media', 'label' => 'Film Assets'],
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="glass p-5 text-center border border-white/10 hover:border-gold/20 transition-all">
                    <div class="text-2xl font-serif text-white mb-1" id="{{ $stat['id'] }}">-</div>
                    <p class="text-[0.55rem] tracking-[0.15em] uppercase text-ash/50">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Control Actions -->
    <div class="pt-8 flex flex-wrap gap-4">
        <button onclick="runAllChecks()" class="group relative px-8 py-4 bg-gold text-black text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
            <span class="relative z-10">Re-Initialize Vitals</span>
            <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
        </button>
        <a href="{{ url()->current() }}" class="group relative px-8 py-4 glass border border-charcoal/10 dark:border-white/10 text-silver text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:text-gold hover:border-gold/30 transition-all duration-500">
            <span class="relative z-10">Refresh Workspace</span>
        </a>
    </div>
</div>

<style>
    /* Status Styles */
    .health-check-card.online .status-indicator {
        @apply bg-ash;
        box-shadow: 0 0 12px rgba(196, 196, 196, 0.4);
    }
    .health-check-card.online .status-message {
        @apply text-ash font-medium;
    }
    .health-check-card.online .status-progress {
        @apply bg-gold;
        width: 100% !important;
    }
    .health-check-card.online {
        @apply border-gold/30;
    }
    .health-check-card.online .health-score {
        @apply text-gold;
    }

    .health-check-card.offline .status-indicator {
        @apply bg-red-500;
        box-shadow: 0 0 12px rgba(239, 68, 68, 0.5);
    }
    .health-check-card.offline .status-message {
        @apply text-red-400 font-medium;
    }
    .health-check-card.offline .status-progress {
        @apply bg-red-500;
        width: 100% !important;
    }
    .health-check-card.offline {
        @apply border-red-500/30;
    }
    .health-check-card.offline .health-score {
        @apply text-red-400;
    }

    .health-check-card.warning .status-indicator {
        @apply bg-silver;
        box-shadow: 0 0 12px rgba(138, 138, 138, 0.4);
    }
    .health-check-card.warning .status-message {
        @apply text-silver font-medium;
    }
    .health-check-card.warning .status-progress {
        @apply bg-silver;
        width: 75% !important;
    }
    .health-check-card.warning {
        @apply border-silver/30;
    }
    .health-check-card.warning .health-score {
        @apply text-silver;
    }

    /* Details Panel */
    .details-panel {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out, padding 0.3s ease-out;
    }
    .details-panel.open {
        max-height: 400px;
    }
    .details-panel .details-content {
        font-size: 0.75rem;
    }
    .details-panel .details-content dt {
        @apply text-ash/50 font-medium;
    }
    .details-panel .details-content dd {
        @apply text-white/80;
    }

    /* Rotate expand icon */
    .expand-icon.rotate {
        transform: rotate(180deg);
    }

    /* Loading animation */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .checking .status-progress {
        animation: pulse 1.5s ease-in-out infinite;
    }
</style>

<script>
    let allAlerts = [];

    function toggleDetails(checkId) {
        const card = document.querySelector(`[data-check="${checkId}"]`);
        // Only toggle if card has details
        if (card.dataset.hasDetails !== 'true') return;
        
        const panel = document.getElementById('details-' + checkId);
        const icon = document.getElementById('icon-' + checkId);
        
        panel.classList.toggle('open');
        icon.classList.toggle('rotate');
    }

    async function runAllChecks() {
        const checks = ['database', 'cache', 'email', 'storage', 'roles', 'disk', 'queue', 'application'];
        
        // Reset visual state
        document.querySelectorAll('.health-check-card').forEach(card => {
            card.classList.remove('online', 'offline', 'warning');
            card.classList.add('checking');
            card.querySelector('.status-progress').style.width = '0%';
            card.querySelector('.status-message').textContent = 'Running check...';
            card.querySelector('.health-score').textContent = '--%';
        });
        
        allAlerts = [];

        for (const check of checks) {
            await runCheck(check);
        }
        
        document.querySelectorAll('.health-check-card').forEach(card => {
            card.classList.remove('checking');
        });
        
        renderAlerts();
        await loadStatistics();
    }

    async function runCheck(checkName) {
        const card = document.querySelector(`[data-check="${checkName}"]`);
        const message = card.querySelector('.status-message');
        const detailsContent = document.getElementById('details-content-' + checkName);
        const progressBar = card.querySelector('.status-progress');
        const healthScore = card.querySelector('.health-score');

        try {
            const response = await fetch(`/api/health/${checkName}`);
            const data = await response.json();

            const statusClass = data.success ? 'online' : (data.type === 'warning' ? 'warning' : 'offline');
            const score = data.success ? 100 : (data.type === 'warning' ? 75 : 0);
            
            card.classList.remove('checking');
            card.classList.add(statusClass);
            message.textContent = data.message;
            healthScore.textContent = score + '%';
            progressBar.style.width = score + '%';

            // Collect alerts
            if (!data.success || data.type === 'warning') {
                allAlerts.push({
                    type: data.type || 'error',
                    source: checkName.charAt(0).toUpperCase() + checkName.slice(1),
                    message: data.message,
                    action: getRecommendedAction(checkName)
                });
            }

            // Update details panel only if it exists
            if (detailsContent) {
                if (data.details && Object.keys(data.details).length > 0) {
                    let detailsHtml = '<dl class="grid grid-cols-2 gap-y-3 gap-x-4">';
                    for (const [key, value] of Object.entries(data.details)) {
                        detailsHtml += `<dt class="text-ash/50">${key}</dt><dd class="text-white/80 text-right">${value}</dd>`;
                    }
                    detailsHtml += '</dl>';
                    detailsContent.innerHTML = detailsHtml;
                } else {
                    detailsContent.innerHTML = '<p class="text-xs text-ash/50">No additional details available</p>';
                }
            }
        } catch (error) {
            card.classList.remove('checking');
            card.classList.add('offline');
            message.textContent = 'Connection failed: ' + error.message;
            healthScore.textContent = '0%';
            progressBar.style.width = '100%';
            
            if (detailsContent) {
                detailsContent.innerHTML = `<p class="text-xs text-red-400">Error: ${error.message}</p>`;
            }
            
            allAlerts.push({
                type: 'error',
                source: checkName.charAt(0).toUpperCase() + checkName.slice(1),
                message: 'Connection failed: ' + error.message,
                action: 'Check network connection and server status.'
            });
        }
    }

    function getRecommendedAction(check) {
        const actions = {
            'database': 'Check database credentials in .env and verify MySQL/PostgreSQL is running.',
            'cache': 'Verify cache driver configuration. For Redis, check if Redis server is running.',
            'email': 'Configure email templates in Admin > Email Configuration.',
            'storage': 'Check storage directory permissions (chmod -R 775 storage).',
            'roles': 'Run php artisan db:seed to initialize roles and permissions.',
            'queue': 'Start queue worker with: php artisan queue:work',
            'disk': 'Free up disk space or expand storage volume.',
            'application': 'Check application logs for errors.',
        };
        return actions[check] || null;
    }

    function renderAlerts() {
        const alertsContainer = document.getElementById('alerts-container');
        const alertsList = document.getElementById('alerts-list');
        const alertCount = document.getElementById('alert-count');
        
        if (allAlerts.length === 0) {
            alertsContainer.classList.add('hidden');
            return;
        }
        
        alertsContainer.classList.remove('hidden');
        alertCount.textContent = allAlerts.length;
        
        alertsList.innerHTML = allAlerts.map(alert => `
            <div class="glass rounded-xl border ${alert.type === 'error' ? 'border-red-500/30 bg-red-500/5' : 'border-amber-500/30 bg-amber-500/5'} p-4 flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg ${alert.type === 'error' ? 'bg-red-500/20 text-red-400' : 'bg-amber-500/20 text-amber-400'} flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${alert.type === 'error' 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'
                        }
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-bold text-white tracking-wide">${alert.source}</span>
                        <span class="text-[0.6rem] px-1.5 py-0.5 rounded ${alert.type === 'error' ? 'bg-red-500/20 text-red-400' : 'bg-amber-500/20 text-amber-400'}">${alert.type.toUpperCase()}</span>
                    </div>
                    <p class="text-sm text-ash/70 mb-2">${alert.message}</p>
                    ${alert.action ? `
                        <div class="flex items-start gap-2 text-xs text-ash/50 bg-charcoal/30 rounded p-2">
                            <svg class="w-4 h-4 text-gold shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span>${alert.action}</span>
                        </div>
                    ` : ''}
                </div>
            </div>
        `).join('');
    }

    async function loadStatistics() {
        try {
            const response = await fetch('/api/health/statistics');
            const data = await response.json();

            animateValue('stat-users', parseInt(document.getElementById('stat-users').textContent) || 0, data.users || 0, 1000);
            animateValue('stat-requests', parseInt(document.getElementById('stat-requests').textContent) || 0, data.booking_requests || 0, 1000);
            animateValue('stat-bookings', parseInt(document.getElementById('stat-bookings').textContent) || 0, data.bookings || 0, 1000);
            animateValue('stat-invoices', parseInt(document.getElementById('stat-invoices').textContent) || 0, data.invoices || 0, 1000);
            animateValue('stat-media', parseInt(document.getElementById('stat-media').textContent) || 0, data.media_items || 0, 1000);
        } catch (error) {
            console.error('Data retrieval failure:', error);
        }
    }

    function animateValue(id, start, end, duration) {
        const obj = document.getElementById(id);
        if (start === end || isNaN(start) || isNaN(end)) {
            obj.textContent = end;
            return;
        }
        const range = end - start;
        const increment = end > start ? 1 : -1;
        const stepTime = Math.abs(Math.floor(duration / range));
        let current = start;
        const timer = setInterval(function() {
            current += increment;
            obj.textContent = current;
            if (current == end) {
                clearInterval(timer);
            }
        }, Math.max(stepTime, 10));
    }

    // Run checks on page load
    document.addEventListener('DOMContentLoaded', runAllChecks);
</script>
@endsection

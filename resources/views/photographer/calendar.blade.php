@extends('layouts.app')

@section('title', 'Shooting Schedule — Odo Studio')

@section('header', 'Schedule')

@section('content')
<div class="space-y-12 animate-hero-in pb-24 md:pb-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 reveal">
        <div>
            <h1 class="font-serif text-3xl text-charcoal dark:text-white leading-tight">Shooting <em>Schedule</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Timeline of upcoming shoots and deadlines</p>
        </div>
        <a href="{{ route('bookings.index') }}" class="bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 text-charcoal/70 dark:text-silver px-6 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold hover:bg-gold/10 hover:text-gold hover:border-gold transition-all italic">
            View Call Sheets →
        </a>
    </div>

    <!-- Calendar Container -->
    <div class="glass p-6 md:p-10 border border-charcoal/10 dark:border-white/10 rounded-xl reveal shadow-2xl relative overflow-hidden group">
        <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div id='calendar' class="min-h-[700px]"></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: '{{ route('photographer.calendar.events') }}',
            eventClick: function(info) {
                var id = 'calendarEvent' + Date.now();
                var title = info.event.title;
                var location = info.event.extendedProps.location || 'Not specified';
                var notes = info.event.extendedProps.notes || 'No addendum found.';
                var start = info.event.start ? info.event.start.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '';
                var modal = `
                    <div id="${id}" class="fixed inset-0 z-50" role="dialog" aria-modal="true">
                        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="document.getElementById('${id}').remove()"></div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
                            <div class="glass bg-charcoal border border-white/10 rounded-xl p-8 shadow-2xl">
                                <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                                    <span>Production Details</span>
                                    <div class="h-px w-16 bg-gold/30"></div>
                                </div>
                                <h2 class="font-serif text-2xl text-white mb-4">${title}</h2>
                                <div class="space-y-4 mb-8">
                                    <div>
                                        <p class="text-[0.55rem] tracking-[0.3em] uppercase text-gold/60 mb-1">Schedule</p>
                                        <p class="text-sm text-ash/80">${start}</p>
                                    </div>
                                    <div>
                                        <p class="text-[0.55rem] tracking-[0.3em] uppercase text-gold/60 mb-1">Coordinates</p>
                                        <p class="text-sm text-ash/80">${location}</p>
                                    </div>
                                    <div>
                                        <p class="text-[0.55rem] tracking-[0.3em] uppercase text-gold/60 mb-1">Notes</p>
                                        <p class="text-sm text-ash/80 italic">${notes}</p>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="button" onclick="document.getElementById('${id}').remove()" class="px-8 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold bg-gold text-charcoal hover:bg-white transition-all duration-300">
                                        Dismiss
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modal);
            },
            dayMaxEvents: true,
            themeSystem: 'standard',
            height: 'auto',
            handleWindowResize: true,
            windowResizeDelay: 100
        });
        calendar.render();
    });
</script>
@endpush

<style>
    /* FullCalendar Premium Cinematic Overrides */
    .fc { 
        --fc-border-color: rgba(255,255,255,0.05);
        --fc-button-bg-color: rgba(255,255,255,0.03);
        --fc-button-border-color: rgba(255,255,255,0.08);
        --fc-button-hover-bg-color: rgba(201,168,76,0.15);
        --fc-button-hover-border-color: #c9a84c;
        --fc-button-active-bg-color: #c9a84c;
        --fc-button-active-border-color: #c9a84c;
        --fc-today-bg-color: rgba(201,168,76,0.05);
        --fc-neutral-bg-color: transparent;
        --fc-list-event-hover-bg-color: rgba(201,168,76,0.1);
        font-family: 'Outfit', sans-serif;
        background: transparent;
    }

    .fc .fc-toolbar-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.75rem !important;
        font-style: italic;
        color: #f5f0e1 !important;
    }

    .fc .fc-toolbar-chunk {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .fc .fc-button {
        font-size: 0.6rem !important;
        letter-spacing: 0.15em !important;
        text-transform: uppercase !important;
        font-weight: 600 !important;
        padding: 10px 18px !important;
        background: rgba(255,255,255,0.03) !important;
        border: 1px solid rgba(255,255,255,0.08) !important;
        color: #a0a0a0 !important;
        transition: all 0.3s ease !important;
    }

    .fc .fc-button:hover {
        background: rgba(201,168,76,0.15) !important;
        border-color: #c9a84c !important;
        color: #c9a84c !important;
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active, 
    .fc .fc-button-primary:not(:disabled):active {
        background-color: #c9a84c !important;
        border-color: #c9a84c !important;
        color: #000000 !important;
        box-shadow: 0 0 20px rgba(201,168,76,0.3) !important;
    }

    .fc-theme-standard .fc-scrollgrid {
        border: none !important;
    }

    .fc-theme-standard .fc-scrollgrid td,
    .fc-theme-standard .fc-scrollgrid th {
        border-color: rgba(255,255,255,0.05) !important;
    }

    .fc-col-header-cell {
        background: rgba(255,255,255,0.02) !important;
        border-bottom: 1px solid rgba(201,168,76,0.2) !important;
        padding: 16px 0 !important;
    }

    .fc-col-header-cell-cushion {
        font-size: 0.65rem !important;
        letter-spacing: 0.25em !important;
        text-transform: uppercase !important;
        color: #c9a84c !important;
        font-weight: 600 !important;
        text-decoration: none !important;
    }

    .fc-daygrid-day {
        background: rgba(255,255,255,0.01) !important;
        transition: background 0.3s ease !important;
    }

    .fc-daygrid-day:hover {
        background: rgba(201,168,76,0.03) !important;
    }

    .fc-daygrid-day-number {
        color: #a0a0a0 !important;
        font-size: 0.75rem !important;
        padding: 12px !important;
        text-decoration: none !important;
    }

    .fc-day-today {
        background: rgba(201,168,76,0.08) !important;
    }

    .fc-day-today .fc-daygrid-day-number {
        color: #c9a84c !important;
        font-weight: 700 !important;
        text-shadow: 0 0 20px rgba(201,168,76,0.5) !important;
    }

    .fc-event {
        border: none !important;
        background: linear-gradient(135deg, rgba(201,168,76,0.3) 0%, rgba(201,168,76,0.15) 100%) !important;
        border-left: 2px solid #c9a84c !important;
        border-radius: 2px !important;
        padding: 4px 8px !important;
        margin: 2px 4px !important;
        transition: all 0.3s ease !important;
        cursor: pointer !important;
    }

    .fc-event:hover {
        background: linear-gradient(135deg, rgba(201,168,76,0.5) 0%, rgba(201,168,76,0.3) 100%) !important;
        transform: translateX(2px) !important;
        box-shadow: 0 4px 15px rgba(201,168,76,0.2) !important;
    }

    .fc-event-title {
        font-family: 'Cormorant Garamond', serif !important;
        font-style: italic !important;
        font-size: 0.8rem !important;
        color: #f5f0e1 !important;
    }

    .fc-event-time {
        color: #c9a84c !important;
        font-weight: 600 !important;
        font-size: 0.65rem !important;
        letter-spacing: 0.1em !important;
    }

    .fc-day-other .fc-daygrid-day-number {
        color: rgba(255,255,255,0.15) !important;
    }

    /* Time Grid Specific */
    .fc-timegrid-slot {
        height: 48px !important;
        border-color: rgba(255,255,255,0.03) !important;
    }

    .fc-timegrid-slot-label {
        color: #666 !important;
        font-size: 0.65rem !important;
    }

    .fc-timegrid-event {
        background: linear-gradient(135deg, rgba(201,168,76,0.4) 0%, rgba(201,168,76,0.2) 100%) !important;
        border-left: 3px solid #c9a84c !important;
        border-radius: 2px !important;
    }

    .fc-timegrid-event:hover {
        background: linear-gradient(135deg, rgba(201,168,76,0.6) 0%, rgba(201,168,76,0.4) 100%) !important;
    }

    /* More Button */
    .fc-daygrid-more-link {
        color: #c9a84c !important;
        font-size: 0.65rem !important;
        letter-spacing: 0.1em !important;
        text-transform: uppercase !important;
        font-weight: 600 !important;
    }

    /* List View */
    .fc-list-event:hover td {
        background: rgba(201,168,76,0.05) !important;
    }

    .fc-list-event-title a {
        color: #f5f0e1 !important;
        font-family: 'Cormorant Garamond', serif !important;
        font-style: italic !important;
    }

    .fc-list-event-time {
        color: #c9a84c !important;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .fc {
            padding: 0 !important;
        }
        
        .fc .fc-toolbar {
            flex-direction: column !important;
            gap: 16px !important;
            margin-bottom: 20px !important;
        }
        
        .fc .fc-toolbar-title {
            font-size: 1.4rem !important;
            text-align: center;
            order: -1;
        }
        
        .fc .fc-toolbar-chunk {
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .fc .fc-button {
            padding: 8px 12px !important;
            font-size: 0.55rem !important;
        }
        
        .fc-header-toolbar {
            gap: 12px !important;
        }
        
        .fc-col-header-cell {
            padding: 10px 2px !important;
        }
        
        .fc-col-header-cell-cushion {
            font-size: 0.55rem !important;
            letter-spacing: 0.15em !important;
        }
        
        .fc-daygrid-day {
            min-height: 70px !important;
        }
        
        .fc-daygrid-day-number {
            padding: 6px 8px !important;
            font-size: 0.7rem !important;
        }
        
        .fc-event {
            margin: 1px 2px !important;
            padding: 2px 4px !important;
        }
        
        .fc-event-title {
            font-size: 0.65rem !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            max-width: 60px !important;
            display: block !important;
        }
        
        .fc-event-time {
            display: none !important;
        }
        
        .fc .fc-timegrid-slot {
            height: 32px !important;
        }
        
        .fc .fc-timegrid-slot-label {
            font-size: 0.5rem !important;
        }
    }
</style>
@endsection

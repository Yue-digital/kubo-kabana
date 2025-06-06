@php
    $events = collect($events)->map(function ($event) {
        return [
            'title' => $event['title'],
            'start' => $event['start'],
            'end' => $event['end'],
            'backgroundColor' => $event['color'],
            'borderColor' => $event['color'],
        ];
    })->toJson();
@endphp

<x-filament-panels::page>
    <div class="p-4">
        <div id="calendar" class="bg-white rounded-lg shadow"></div>
    </div>

    
</x-filament-panels::page> 



    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script>
        console.log('calendar-view');
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: {!! $events !!},
                eventDidMount: function(info) {
                    // Add tooltip
                    info.el.title = info.event.title;
                }
            });
            calendar.render();
        });
    </script>
    
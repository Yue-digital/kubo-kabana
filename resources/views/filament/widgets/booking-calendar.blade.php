@php
    $events = collect($this->getEvents())->map(function ($event) {
        return [
            'title' => $event['title'],
            'start' => $event['start'],
            'end' => $event['end'],
            'backgroundColor' => $event['color'],
            'borderColor' => $event['color'],
        ];
    });
    $eventsJson = $events->toJson();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="p-4">
            {{-- <pre style="color: white; background: #222; padding: 8px; border-radius: 4px;">{!! $eventsJson !!}</pre> --}}
            <div id="calendar-{{ $this->getId() }}" class="bg-white rounded-lg shadow"></div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

<script>
    function renderBookingCalendar_{{ $this->getId() }}() {
        const calendarEl = document.getElementById('calendar-{{ $this->getId() }}');
        if (!calendarEl || !window.FullCalendar) return false;

        const events = {!! $eventsJson !!};
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: events,
            eventDidMount: function(info) {
                info.el.title = info.event.title;
            },
            height: 'auto',
            contentHeight: 600,
            aspectRatio: 1.5,
        });

        calendar.render();
        return true;
    }

    (function waitForFullCalendar() {
        if (!window.FullCalendar) {
            setTimeout(waitForFullCalendar, 100);
        } else {
            renderBookingCalendar_{{ $this->getId() }}();
        }
    })();
</script>
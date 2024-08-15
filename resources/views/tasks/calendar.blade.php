@extends('layouts.app')

@section('calendar_task')
<div class="container mt-4">
    <!-- Conteneur pour le calendrier -->
    <div id='calendar'></div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($tasks), 
            eventClick: function(info) {
                alert('Tâche : ' + info.event.title + '\nStart Date: ' + info.event.startStr + '\nEnd Date: ' + info.event.endStr + '\nDescription : ' + info.event.extendedProps.description);
            }
        });
        calendar.render();
    });
</script>
@endsection

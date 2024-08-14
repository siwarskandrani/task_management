@extends('layouts.app')

@section('calendar_task')
<div class="container mt-4">
    <!-- Conteneur pour le calendrier -->
    <div id='calendar'></div>
</div>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($tasks), // Passer les données des tâches en JSON
            eventClick: function(info) {
                // Code pour afficher les détails de la tâche lorsqu'un événement est cliqué
                alert('Tâche : ' + info.event.title + '\nStart Date: ' + info.event.startStr + '\nEnd Date: ' + info.event.endStr + '\nDescription : ' + info.event.extendedProps.description);
            }
        });
        calendar.render();
    });
</script>
@endsection

@extends('layouts.app')

@section('calendar_task')
<div class="container">
    <div id='calendar'></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: [ 'dayGrid', 'interaction' ],
                editable: true,
                events: @json($tasks), // Passer les données des tâches en JSON
                eventClick: function(info) {
                    // Code pour afficher les détails de la tâche lorsqu'un événement est cliqué
                    alert('Tâche : ' + info.event.title + '\nDescription : ' + info.event.extendedProps.description);
                }
            });

            calendar.render();
        });
    </script>
</div>
@endsection

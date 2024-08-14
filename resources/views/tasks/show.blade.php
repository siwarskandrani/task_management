<!DOCTYPE html>
<html>
<head>
    <title>Tâche Détails</title>
</head>
<body>
    <h1>{{ $task->title }}</h1>
    <p><strong>Description:</strong> {{ $task->description }}</p>
    <p><strong>Équipe:</strong> {{ $task->team->name ?? 'N/A' }}</p>
    <p><strong>Propriétaire:</strong> {{ $task->owner ? $task->owner->name : 'N/A' }}</p>
    <p><strong>Statut:</strong> {{ $task->status }}</p>
    <p><strong>Date de début:</strong> {{ $task->start_date }}</p>
    <p><strong>Date de fin:</strong> {{ $task->end_date }}</p>

    <!-- Ajouter plus d'informations ou fonctionnalités ici -->

    <a href="{{ route('tasks.index') }}">Retour à la liste des tâches</a>
</body>
</html>

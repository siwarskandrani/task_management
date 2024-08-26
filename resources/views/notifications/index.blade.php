<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <!-- Add your styles here -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <h1>Notifications</h1>

    @if ($notifications->isEmpty())
        <p>No notifications</p>
    @else
        <ul>
            @foreach ($notifications as $notification)
                <li>
                    @if ($notification->type === 'App\Notifications\TaskDeletedNotification')
                        Task titled "{{ $notification->data['task_title'] }}" has been deleted.
                    @elseif ($notification->type === 'App\Notifications\TaskUpdatedNotification')
                        Task titled "{{ $notification->data['title'] }}" has been updated.
                    @else
                        {{ $notification->data['message'] ?? 'You have a new notification.' }}
                    @endif
                    <span class="date">{{ $notification->created_at->format('Y-m-d H:i') }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>

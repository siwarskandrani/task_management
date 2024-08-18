<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <!-- Add your styles here -->
</head>
<body>
    <h1>Notifications</h1>

    @if ($notifications->isEmpty())
        <p>No notifications</p>
    @else
        <ul>
            @foreach ($notifications as $notification)
                <li>
                    Task titled "{{ $notification->data['title'] }}" has been updated.
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Team Invitation</title>
</head>
<body>
    <h1>You're Invited to Join the Team</h1>
    <p>You've been invited to join the team "{{ $teamName }}".</p>

    @if ($invitationLink)
        <p>Since you don’t have an account, please <a href="{{ $invitationLink }}">register here</a> to join the team.</p>
    @else
        <p>Please log in to view and accept your invitation.</p>
    @endif

    <p>Best regards,<br>Your Team</p>
</body>
</html>

@extends('layouts.app')

@section('content_invitations')
    @auth
        <h1>Invitation to Join {{ $team->name }}</h1>

        <p>Do you want to join this team?</p>

        <form method="POST" action="{{ route('teams.acceptInvitation', $team->id) }}">
            @csrf
            <button type="submit" class="btn btn-success">Accept</button>
        </form>

        <form method="POST" action="{{ route('teams.rejectInvitation', $team->id) }}">
            @csrf
            <button type="submit" class="btn btn-danger">Reject</button>
        </form>
    @else
        <p>You need to be logged in to view and respond to invitations. Please <a href="{{ route('login') }}">log in</a> to continue.</p>
    @endauth
@endsection

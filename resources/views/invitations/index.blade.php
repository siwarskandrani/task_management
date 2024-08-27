<!-- resources/views/invitations/index.blade.php -->

@extends('layouts.app')

@section('content_invitations')
<div class="container">
    <h2>Vos Invitations</h2>

    @if($invitations->isEmpty())
        <p>Aucune invitation pour le moment.</p>
    @else
        <ul>
            @foreach($invitations as $invitation)
                <li>
                    Vous avez été invité à rejoindre l'équipe {{ $invitation->team->name }}.
                    <a href="{{ route('teams.accept_invitation', $invitation->id) }}">Accepter</a>
                    <a href="{{ route('teams.reject_invitation', $invitation->id) }}">Refuser</a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection

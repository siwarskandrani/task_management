@extends('layouts.app')

@section('index_team')
<div class="container p-5">
    <div class="row mb-3">
        <div class="col align-self-start">
            <a class="btn btn-primary" href="{{ route('teams.create') }}">Create Team</a>
        </div>
    </div>

    @if ($message = Session::get('success')) 
    <div class="alert alert-success" role="alert">
        {{ $message }}  
    </div>           
    @endif

    @if ($message = Session::get('error'))
    <div class="alert alert-danger" role="alert">
        {{ $message }}
    </div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th>Team Name</th>
                <th>Description</th>
                <th>My Role</th>
                <th>Members</th>
                <th>Actions</th>
                <th>Details</th> 
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $team)
            <tr>
                <td>{{ $team->name }}</td>
                <td>{{ $team->description }}</td>
                <td>{{ $team->pivot->role }}</td>
                <td>
                    <div class="circle-container">
                        @foreach ($team->users as $member)
                        @php
                            // Générer des couleurs aléatoires pour les cercles
                            $colors = ['#ff007f', '#ffeb3b', '#00e676', '#00bcd4', '#ff5722'];
                            $color = $colors[array_rand($colors)];
                             $initials = strtoupper(substr($member->name, 0, 1) . substr($member->name, strpos($member->name, ' ') + 1, 1));
                    @endphp                        
                            <div class="circle" style="background-color: {{ $color }};" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $member->name }} {{ $member->surname }} ({{ $member->pivot->role }})">
                                {{ $initials }}
                            </div>
                        @endforeach
                    </div>
                </td>
                <td>
                    <form action="{{ route('teams.destroy', $team->id) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-primary btn-sm">Edit</a>
                </td>
                <td>
                    <a href="{{ route('teams.show', $team->id) }}" class="btn btn-info btn-sm">Show</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
    .circle-container {
        position: relative;
        width: 150px; /* Ajuster la largeur pour contenir les cercles superposés */
        height: 40px; /* Hauteur fixe pour la ligne de cercles */
        overflow: hidden; /* Cache les cercles qui débordent */
    }

    .circle {
        width: 40px;
        height: 40px;
        color: white;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        position: absolute;
        cursor: pointer;
        text-align: center;
        line-height: 40px; /* Aligner le texte au centre */
        margin-left: 5px; /* Espacement entre les cercles */
    }

    .circle:nth-child(1) { left: 0px; }
    .circle:nth-child(2) { left: 30px; }
    .circle:nth-child(3) { left: 60px; }
    .circle:nth-child(4) { left: 90px; }
    .circle:nth-child(5) { left: 120px; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection

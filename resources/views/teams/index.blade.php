@extends('layouts.app')

@section('index_team')
<div class="container p-5">
    <div class="row mb-4 align-items-center">
        <!-- Status filter and Sort by name on the left -->
        <div class="col-md-4 d-flex align-items-center">
            <!-- Status filter -->
            <form action="{{ route('teams.index') }}" method="GET" class="d-flex align-items-center me-3">
                <select name="role" class="form-select me-2" onchange="this.form.submit()" style="border-radius: 10px;">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request()->query('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="member" {{ request()->query('role') == 'member' ? 'selected' : '' }}>Member</option>
                </select>
            </form>           
        </div>

        <!-- Search input centered -->
        <div class="col-md-4 d-flex justify-content-center">
            <form action="{{ route('teams.index') }}" method="GET" class="d-flex w-100">
                <input type="text" name="search" class="form-control me-2" placeholder="Search teams..." value="{{ request()->query('search') }}" style="border-radius: 10px;">
                <button type="submit" class="btn btn-outline-primary" style="border-radius: 10px;">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <!-- Create Team button on the right -->
        <div class="col-md-4 d-flex justify-content-end align-items-center">
            <a class="btn btn-primary d-flex align-items-center" href="{{ route('teams.create') }}" style="border-radius: 10px;">
                <i class="bi bi-plus-lg me-2"></i> Create Team
            </a>
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
        <thead class="thead-dark">
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
                    <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-warning btn-sm">Edit</a>
                </td>
                <td>
                    <a href="{{ route('teams.show', $team->id) }}" class="btn btn-info btn-sm">Show</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $teams->appends(request()->query())->links() }}
    </div>
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
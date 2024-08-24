@extends('layouts.app')

@section('show_teams')
<div class="container p-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">{{ $team->name }}</h1>
            <p><strong>Description:</strong> {{ $team->description }}</p>
            
            <h3 class="mt-4">Members</h3>
            @if ($members->isEmpty())
                <p>No members in this team.</p>
            @else
                <ul class="list-group">
                    @foreach ($members as $member)
                        <li class="list-group-item">
                            {{ $member->name }} ({{ $member->email }})
                            @if (auth()->user()->teams()->wherePivot('ID_team', $team->id)->wherePivot('role', 'admin')->exists())
                                <form 
                                    action="{{ route('teams.destroyMember', ['teamId' => $team->id, 'memberId' => $member->id]) }}" 
                                    method="POST" 
                                    style="display:inline;"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm float-end" onclick="return confirm('Are you sure you want to remove this member?')">
                                        Remove
                                    </button>
                                </form>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection

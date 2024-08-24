@extends('layouts.app')

@section('index_project')
<div class="container p-5">
    <br>
    <div class="row">
        <div class="col align-self-start">
            <a class="btn btn-primary" href="{{ route('projects.create') }}">Create Project</a>
        </div>
    </div>
    <br>

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

    <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless table-primary align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Tasks</th> <!-- Nouvelle colonne pour les tâches -->
                    <th width='300px'>Actions</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($projects as $project)
                <tr class="table-primary">
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->description }}</td>
                    <td>
                        <ul class="list-unstyled">
                            @forelse ($project->tasks as $task)
                                <li>{{ $task->title }}</li>
                            @empty
                                <li>No tasks assigned</li>
                            @endforelse
                        </ul>
                    </td>
                    <td>
                        <form action="{{ route('projects.destroy', $project->id) }}" method="post" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        <a class="btn btn-primary" href="{{ route('projects.edit', $project->id) }}">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

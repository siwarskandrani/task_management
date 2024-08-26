@extends('layouts.app')

@section('index_project')
<div class="container p-5">
    <div class="row mb-4">
        <div class="col-auto">
            <a class="btn btn-primary" href="{{ route('projects.create') }}">
                <i class="bi bi-plus"></i> Create Project
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

    <div class="row">
        @foreach ($projects as $project)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $project->name }}</h5>
                    <p class="card-text">{{ Str::limit($project->description, 100, '...') }}</p>
                    <h6 class="card-subtitle mb-2 text-muted">Tasks:</h6>
                    @forelse ($project->tasks as $task)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span>{{ $task->title }}</span>
                                <small class="text-muted d-block">Assigned to: {{ $task->owner ? $task->owner->name : 'Unassigned' }}</small>
                            </div>
                            <a class="text-info" href="{{ route('tasks.show', $task->id) }}">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    @empty
                        <p class="text-muted">No tasks assigned</p>
                    @endforelse
                    <div class="mt-3">
                        <a class="text-primary me-2" href="{{ route('projects.edit', $project->id) }}">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('projects.destroy', $project->id) }}" method="post" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger p-0" style="font-size: 1.2rem;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@extends('layouts.app')

@section('show_tasks')
<div class="container">
    <h1 class="mb-4">Task Details</h1>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">{{ $task->title }}</h2>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Description:</strong> {{ $task->description }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong> <span class="badge {{ $task->status == 'Completed' ? 'bg-success' : 'bg-warning' }}">{{ $task->status_label }}</span></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Team:</strong> {{ $task->team->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Project:</strong> {{ $task->project->name ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Start Date:</strong> {{ $task->start_date }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>End Date:</strong> {{ $task->end_date }}</p>
                </div>
            </div>

            <div class="mb-4">
                <h4>Tags</h4>
                @if($task->tags->isNotEmpty())
                    <div class="mb-2">
                        @foreach($task->tags as $tag)
                            <span class="badge bg-secondary me-2 text-white">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @else
                    <p>No tags</p>
                @endif
            </div>

            <div class="mb-4">
                <h4 class="mb-2">Attachments:</h4>
                @if($task->media->isNotEmpty())
                <div ml-2class="d-flex flex-wrap"> 
                    @foreach($task->media as $media)
                            <a href="{{ asset('storage/' . $media->path) }}" class="btn btn-outline-primary me-2 mb-2" target="_blank">{{ $media->name }}</a>
                        @endforeach
                    </div>
                @else
                    <p>No attachments</p>
                @endif
            </div>
        </div>
    </div>

    @if($subTasks->isNotEmpty())
        <div class="card mt-4 shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h3 class="mb-0">Subtasks</h3>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($subTasks as $subTask)
                        <li class="list-group-item">
                            <h5 class="mb-1">{{ $subTask->title }}</h5>
                            <p class="mb-1">{{ $subTask->description }}</p>
                            <div class="d-flex justify-content-between">
                                <span><strong>Status:</strong> <span class="badge {{ $subTask->status == 'Completed' ? 'bg-success' : 'bg-warning' }}">{{ $subTask->status_label }}</span></span>
                                <span><strong>Start Date:</strong> {{ $subTask->start_date }}</span>
                                <span><strong>End Date:</strong> {{ $subTask->end_date }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @else
        <p class="mt-3">No sub-tasks</p>
    @endif
</div>
@endsection

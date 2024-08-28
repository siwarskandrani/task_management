@extends('layouts.app')

@section('show_tasks')
<div class="container">
    <h1 class="mb-4">Task Details</h1>

    <!-- Main Task -->
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
                    <p><strong>Status:</strong> <span class="badge {{ $task->status == '3' ? 'bg-success' : 'bg-warning' }}">{{ $task->status_label }}</span></p>
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

    <!-- Subtasks -->
    @if($subTasks->isNotEmpty())
        <h3 class="mb-4">Subtasks</h3>
        @foreach($subTasks as $subTask)
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">{{ $subTask->title }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Description:</strong> {{ $subTask->description }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> <span class="badge {{ $subTask->status == '3' ? 'bg-success' : 'bg-warning' }}">{{ $subTask->status_label }}</span></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Start Date:</strong> {{ $subTask->start_date }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>End Date:</strong> {{ $subTask->end_date }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Tags</h5>
                        @if($subTask->tags->isNotEmpty())
                            <div class="mb-2">
                                @foreach($subTask->tags as $tag)
                                    <span class="badge bg-secondary me-2 text-white">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <p>No tags</p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-2">Attachments:</h5>
                        @if($subTask->media->isNotEmpty())
                        <div ml-2class="d-flex flex-wrap"> 
                            @foreach($subTask->media as $media)
                                    <a href="{{ asset('storage/' . $media->path) }}" class="btn btn-outline-primary me-2 mb-2" target="_blank">{{ $media->name }}</a>
                                @endforeach
                            </div>
                        @else
                            <p>No attachments</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p class="mt-3">No sub-tasks</p>
    @endif
</div>
@endsection

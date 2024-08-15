@extends('layouts.app')

@section('create_task')
<div class="container">
    <div class="mb-3">
        <p><strong>Title:</strong> {{ $task->title }}</p>
    </div>
    <div class="mb-3">
        <p><strong>Description:</strong> {{ $task->description }}</p>
    </div>
    <div class="mb-3">
        <p><strong>Team:</strong> {{ $task->team->name ?? 'N/A' }}</p>
    </div>
    <div class="mb-3">
        <p><strong>Owner:</strong> {{ $task->owner->name ?? 'N/A' }}</p>
    </div>
    <div class="mb-3">
        <p><strong>Status:</strong> {{ $task->status }}</p>
    </div>
    <div class="mb-3">
        <p><strong>Start Date:</strong> {{ $task->start_date }}</p>
    </div>
    <div class="mb-3">
        <p><strong>End Date:</strong> {{ $task->end_date }}</p>
    </div>
    <div class="mb-3">
        <p><strong>Attachments:</strong></p>
        @if($task->media->isNotEmpty())
        @foreach($task->media as $media)
            @if (strpos($media->path, 'task_images') !== false)
                <img src="{{ asset('storage/task_images/' . $media->path) }}" width="300px" alt="Media">
            @elseif (strpos($media->path, 'task_documents') !== false)
                <a href="{{ asset('storage/task_documents/' . $media->path) }}" target="_blank">View Document</a>
            @endif
        @endforeach
    @else
        <p>No attachments</p>
    @endif
    
    </div>
</div>
@endsection
@extends('layouts.app')

@section('tasks_edit')
<div class="container">
    <h1>Edit Task</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $task->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control">{{ old('description', $task->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="team_id" class="form-label">Team</label>
            <select id="team_id" name="team_id" class="form-select" required>
                <option value="" disabled>Select a team</option>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}" {{ $team->id == old('team_id', $task->team_id) ? 'selected' : '' }}>
                        {{ $team->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="owner_id" class="form-label">Owner</label>
            <select id="owner_id" name="owner_id" class="form-select">
                <option value="" disabled>Select an owner</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}" {{ $member->id == old('owner_id', $task->owner) ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select" required>
                <option value="not_started" {{ old('status', $task->status) == 'not_started' ? 'selected' : '' }}>Not Started</option>
                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date', $task->start_date) }}">
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date', $task->end_date) }}">
        </div>

        <div class="mb-3">
            <label for="media" class="form-label">Attachments</label>
            <input type="file" id="media" name="media[]" class="form-control" multiple>
        </div>

        <div class="mb-3">
            <label for="tags" class="form-label">Tags</label>
            <select id="tags" name="tags[]" class="form-select" multiple>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $task->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Task</button>
    </form>
</div>
@endsection

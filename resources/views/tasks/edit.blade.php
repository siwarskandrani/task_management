@extends('layouts.app')

@section('tasks_edit')
<div class="container">
    <h1>Edit Task</h1>

    <!-- Afficher les messages de succès ou d'erreur -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Titre -->
        <div class="form-group mb-3">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $task->title) }}" required>
            @error('title')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description -->
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $task->description) }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Team -->
        <div class="form-group mb-3">
            <label for="team_id">Team</label>
            <select name="team_id" id="team_id" class="form-select">
                <option value="">None</option>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}" {{ old('team_id', $task->team_id) == $team->id ? 'selected' : '' }}>
                        {{ $team->name }}
                    </option>
                @endforeach
            </select>
            @error('team_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>



            <!-- Assignee -->
        <div class="form-group mb-3">
            <label for="owner_id">Assignee</label>
            <select name="owner_id" id="assignee_id" class="form-select" {{ $task->team_id ? '' : 'disabled' }}>
                <option value="{{ auth()->id() }}">Self Assignee</option>
                @foreach($teams as $team)
                    @foreach($team->users as $user)
                        <option value="{{ $user->id }}" {{ old('owner_id', $task->owner) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                @endforeach
            </select>
            @error('owner_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <!-- Projet -->
        <div class="form-group mb-3">
            <label for="project_id">Project</label>
            <select name="project_id" id="project_id" class="form-select">
                <option value="">None</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
            @error('project_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Statut -->
        <div class="form-group mb-3">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="not_started" {{ old('status', $task->status) == 'not_started' ? 'selected' : '' }}>Not Started</option>
                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            @error('status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tags -->
        <div class="form-group mb-3">
            <label for="tags">Tags</label>
            <select id="tags" name="tags[]" class="form-control" multiple>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" 
                        {{ (in_array($tag->id, old('tags', $task->tags->pluck('id')->toArray()))) ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
            @error('tags')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Type -->
        <div class="form-group mb-3">
            <label for="type">Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="1" {{ old('type', $task->type) == 1 ? 'selected' : '' }}>Main task</option>
                <option value="2" {{ old('type', $task->type) == 2 ? 'selected' : '' }}>Sub task</option>
            </select>
            @error('type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Parent Task -->
        <div class="form-group mb-3">
            <label for="parent_task">Parent Task</label>
            <select name="parent_task" id="parent_task" class="form-select">
                <option value="">None</option>
                @foreach($parent_tasks as $parentTask)
                    <option value="{{ $parentTask->id }}" {{ old('parent_task', $task->parent_task_id) == $parentTask->id ? 'selected' : '' }}>
                        {{ $parentTask->title }}
                    </option>
                @endforeach
            </select>
            @error('parent_task')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Dates -->
        <div class="form-group mb-3">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $task->start_date) }}">
            @error('start_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $task->end_date) }}">
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Médias -->
         <!-- Médias existants -->
        <div class="form-group mb-3">
            <label for="existing_media">Existing Media</label>
            <div id="existing_media" class="d-flex flex-wrap">
                @foreach($task->media as $media)
                    <div class="media-item">
                        <img src="{{ asset('storage/'.$media->path) }}" alt="Media" class="img-thumbnail" style="width: 150px; height: 150px;">
                        <input type="checkbox" name="delete_media[]" value="{{ $media->id }}"> Delete
                    </div>
                @endforeach
            </div>
        </div>

      

    <!-- Médias -->
    <div class="form-group mb-3">
        <label for="media">Add Media</label>
        <input type="file" name="media[]" id="media" class="form-control" multiple>
        @error('media.*')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        <pre>{{ print_r($task->media) }}</pre>
    </div>
    <div id="media-preview" class="d-flex flex-wrap"></div>

    <button type="submit" class="btn btn-primary">Update Task</button>
</form>
</div>

        <button type="submit" class="btn btn-primary">Update Task</button>
    </form>

</div>
@endsection

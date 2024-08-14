@extends('layouts.app')

@section('create_task')
<div class="container">
    <h1>Create Task</h1>

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

    <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- titrr -->
        <div class="form-group mb-3">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
            @error('title')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- description -->
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- team -->
        <div class="form-group mb-3">
            <label for="team_id">Team</label>
            <select name="team_id" id="team_id" class="form-select">
                @foreach($teams as $team)
                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
            </select>
            @error('team_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- owner -->
        <div class="form-group mb-3">
            <label for="owner_id">Assignee</label>
            <select name="owner_id" id="assignee_id" class="form-select">
                <option value="">Select an assignee</option>
            </select>
            @error('owner_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

    <!--projet -->
<div class="form-group mb-3">
    <label for="project_id">Project</label>
    <select name="project_id" id="project_id" class="form-select">
        <option value="">None</option>
        @foreach($projects as $project)
            <option value="{{ $project->id }}">{{ $project->name }}</option>
        @endforeach
    </select>
    @error('project_id')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>


        <!-- statut -->
        <div class="form-group mb-3">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="not_started" {{ old('status') == 'not_started' ? 'selected' : '' }}>Not Started</option>
                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            @error('status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Sélection du type -->
        <div class="form-group mb-3">
            <label for="type">Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Main task</option>
                <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Sub task</option>
            </select>
            @error('type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- parent task -->
        <div class="form-group mb-3">
            <label for="parent_task_id">Parent Task</label>
            <select name="parent_task_id" id="parent_task_id" class="form-select">
                <option value="">None</option>
                @foreach($tasks as $task)
                    <option value="{{ $task->id }}" {{ old('parent_task_id') == $task->id ? 'selected' : '' }}>
                        {{ $task->title }}
                    </option>
                @endforeach
            </select>
            @error('parent_task_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Dates -->
        <div class="form-group mb-3">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}">
            @error('start_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    <!-- médias -->
        <div class="form-group mb-3">
            <label for="media">Add Media</label>
            <input type="file" name="media[]" id="media" class="form-control" multiple>
            @error('media.*')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div id="media-preview" class="d-flex flex-wrap"></div>


        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

@section('scripts')
<script>
 document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const teamSelect = document.getElementById('team_id');
    const assigneeSelect = document.getElementById('assignee_id');
    const mediaInput = document.getElementById('media');
    const mediaContainer = document.getElementById('media-preview');

    // Fonction pour afficher les aperçus des médias
    function displayMediaPreviews(media) {
        mediaContainer.innerHTML = '';
        media.forEach(mediaItem => {
            const mediaElement = document.createElement('img');
            mediaElement.src = `/storage/${mediaItem.path}`;
            mediaElement.alt = 'Media Preview';
            mediaElement.style.maxWidth = '100px';
            mediaElement.style.margin = '5px';
            mediaContainer.appendChild(mediaElement);
        });
    }

    // Gestion du formulaire
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('{{ route('tasks.store') }}', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Task created successfully');
                displayMediaPreviews(data.media); 
            } else {
                alert('Error creating task');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    teamSelect.addEventListener('change', function() {
        const teamId = this.value;
        
        if (teamId) {
            fetch(`/teams/${teamId}/members`)
                .then(response => response.json())
                .then(data => {
                    assigneeSelect.innerHTML = '<option value="">Select an assignee</option>';
                    
                    data.members.forEach(member => {
                        const option = document.createElement('option');
                        option.value = member.id;
                        option.textContent = member.name;
                        assigneeSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching team members:', error));
        } else {
            assigneeSelect.innerHTML = '<option value="">Select an assignee</option>';
        }
    });
});

    </script>
    
@endsection


@endsection

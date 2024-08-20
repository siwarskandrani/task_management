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

        <!-- Title -->
        <div class="form-group mb-3">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
            @error('title')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description -->
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
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
                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
            </select>
            @error('team_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Assignee -->
        <div class="form-group mb-3">
            <label for="owner_id">Assignee</label>
            <select name="owner_id" id="assignee_id" class="form-select" disabled>
                <option value="{{ auth()->id() }}">Self Assignee</option>
                <!-- Options will be populated via JavaScript -->
            </select>
            @error('owner_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Project -->
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

        <!-- Status -->
        <div class="form-group mb-3">
            <label for="status">Status</label>
            <input type="text" id="status_display" class="form-control" value="Not Started" readonly>
            <input type="hidden" name="status" id="status" value="1">
            @error('status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tags -->
        <div class="form-group mb-3">
            <label for="tags">Tags:</label>
            <select id="tags" name="tags[]" class="form-select" multiple>
            </select>
        </div>

        <!-- Type -->
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

        <!-- Parent Task -->
        <div class="form-group mb-3">
            <label for="parent_task">Parent Task</label>
            <select name="parent_task" id="parent_task" class="form-select">
                <option value="">None</option>
                @foreach($parent_tasks as $task)
                    <option value="{{ $task->id }}" {{ old('parent_task') == $task->id ? 'selected' : '' }}>
                        {{ $task->title }}
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
            <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}">
            @error('start_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="end_date">End Date</label>
            <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Media -->
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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tagsSelect = $('#tags');

    // Initialisation de Selectize pour les tags
    tagsSelect.selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        create: true,
        valueField: 'id',
        labelField: 'name',
        searchField: 'name',
        load: function(query, callback) {
            if (!query.length) return callback();
            fetch(`/tags?query=${query}`)
                .then(response => response.json())
                .then(data => callback(data))
                .catch(error => console.error('Error fetching tags:', error));
        },
        onItemAdd: function(value, $item) {
            // Vérifie si le tag est nouveau
            if (!$item.data('isNew')) return;

            fetch('{{ route('tags.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name: value })
            })
            .then(response => response.json())
            .then(data => {
                if (data.tag) {
                    // Met à jour l'élément avec la valeur correcte
                    $item.data('value', data.tag.id);
                    $item.removeData('isNew');
                    // Ajoute le nouveau tag à la liste de sélection
                    tagsSelect[0].selectize.addOption({ id: data.tag.id, name: data.tag.name });
                    tagsSelect[0].selectize.addItem(data.tag.id);
                } else {
                    alert('Error creating tag');
                }
            })
            .catch(error => console.error('Error creating tag:', error));
        }
    });

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

            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Delete';
            deleteButton.className = 'btn btn-danger btn-sm';
            deleteButton.onclick = function() {
                fetch(`/media/${mediaItem.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Media deleted successfully');
                        displayMediaPreviews(data.media); // Update media previews
                    } else {
                        alert('Error deleting media');
                    }
                })
                .catch(error => console.error('Error deleting media:', error));
            };

            mediaElement.appendChild(deleteButton);
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
                form.reset(); // Optionnel: Réinitialiser le formulaire après succès
            } else {
                alert('Error creating task');
            }
        })
        .catch(error => console.error('Error creating task:', error));
    });

    // Fonction pour activer ou désactiver le champ "Assignee"
    function toggleAssigneeField() {
        if (teamSelect.value === "") { // Si "None" est sélectionné
            assigneeSelect.disabled = true;
            assigneeSelect.innerHTML = '<option value="{{ auth()->id() }}">Self Assignee</option>';
        } else {
            assigneeSelect.disabled = false;
            fetch(`/teams/${teamSelect.value}/members`)
                .then(response => response.json())
                .then(data => {
                    assigneeSelect.innerHTML = '<option value="">Select Assignee</option>';
                    data.members.forEach(member => {
                        assigneeSelect.innerHTML += `<option value="${member.id}">${member.name}</option>`;
                    });
                })
                .catch(error => console.error('Error fetching team members:', error));
        }
    }

    // Événement de changement pour le sélecteur d'équipe
    teamSelect.addEventListener('change', toggleAssigneeField);
});



</script>
@endsection

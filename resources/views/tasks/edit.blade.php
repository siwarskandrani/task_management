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
            <select name="team_id" id="team_id" class="form-select" >
                <option value="">None</option>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}" {{ old('team_id', $task->team_id) == $team->id ? 'selected' : '' }} > {{-- value fiha l variable elli bch yodhhor kima l placeholder(fiha l valeur ancien)--}}
                        {{ $team->name }} {{--et ici on met le nouveau variable mtaana--}}
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
            <select name="project_id" id="project_id" class="form-select"  >
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
   <!-- Médias -->
                <!-- existing Médias -->

                <div class="form-group mb-3">
                    <div id="existing_media" class="d-flex flex-wrap">
                        @foreach($task->media as $media)
                            <div class="media-item">
                                <a href="{{ asset('storage/' . $media->path) }}" target="_blank">{{ $media->name }}</a>
                                 {{-- <form action="{{ route('tasks.removeMedia', ['taskId' => $task->id, 'mediaId' => $media->id]) }}" method="POST" >
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" onclick="submitDeleteForm(this)">×</button>
                                </form>  --}}
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
        
            </div>
            <div id="media-preview" class="d-flex flex-wrap"></div>
        <!-- Statut -->
        <div class="form-group mb-3">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="1" {{ old('status', $task->status) == '1' ? 'selected' : '' }}>Not Started</option>
                <option value="2" {{ old('status', $task->status) == '2' ? 'selected' : '' }}>In Progress</option>
                <option value="3" {{ old('status', $task->status) == '3' ? 'selected' : '' }}>Completed</option>
            </select>
            @error('status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <!-- tags -->

        <div class="form-group mb-3">
            <label for="tags">Tags</label>
            <select name="tags[]" id="tags" class="form-select" multiple>
                @foreach($tags as $tag)
                    <option value="{{ $tag->name }}" {{ in_array($tag->id, old('tags', $task->tags->pluck('id')->toArray())) ? 'selected' : '' }}> 
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
            <select name="type" id="type" class="form-select"  required>
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
            <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $task->start_date) }}">
            @error('start_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="end_date">End Date</label>
            <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $task->end_date) }}" >
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

     

    <button type="submit" class="btn btn-primary">Update Task</button>
</form>
</div>

     

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tagsSelect = $('#tags').selectize({
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
                    $item.data('value', data.tag.id);
                    $item.removeData('isNew');
                    tagsSelect[0].selectize.addOption({ id: data.tag.id, text: data.tag.name });
                    tagsSelect[0].selectize.addItem(data.tag.id);
                } else {
                    alert('Error creating tag');
                }
            })
            .catch(error => console.error('Error creating tag:', error));
        },
        onItemRemove: function(value, $item) {
            // Suppression de l'association de tag à la tâche
            fetch('{{ route('tasks.update', $task->id) }}', {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ tags: tagsSelect[0].selectize.getValue() })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $item.remove();
                } else {
                    alert('Error updating task tags');
                }
            })
            .catch(error => console.error('Error updating task tags:', error));
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

    // Fonction pour activer ou désactiver le champ "Assignee"
    function toggleAssigneeField() {
        if (teamSelect.value === "") { // Si "None" est sélectionné
            assigneeSelect.disabled = true;
            assigneeSelect.innerHTML = '<option value="{{ auth()->id() }}">Self Assignee</option>'; // Remet "Self Assignee"
        } else {
            assigneeSelect.disabled = false;
            fetch(`/teams/${teamSelect.value}/members`)
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
        }
    }

    // Appel initial pour définir l'état correct au chargement
    toggleAssigneeField();

    // Ajoute un écouteur pour le changement de la sélection de l'équipe
    teamSelect.addEventListener('change', toggleAssigneeField);
});

</script>
@endsection



@endsection
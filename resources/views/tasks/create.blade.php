@extends('layouts.app')

@section('create_task')
<div class="container mt-3">
    <h1 class="mb-4">Create Task</h1>

    <!-- Afficher les messages de succès ou d'erreur -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf

        <!-- Title -->
        <div class="form-group mb-4">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
            @error('title')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description -->
        <div class="form-group mb-4">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Team -->
        <div class="form-group mb-4">
            <label for="team_id" class="form-label">Team</label>
            <select name="team_id" id="team_id" class="form-select">
                <option value="">None</option>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}"
                        {{ old('team_id') == $team->id ? 'selected' : '' }}
                        >{{ $team->name }}</option>
                @endforeach
            </select>
            @error('team_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Assignee -->
        <div class="form-group mb-4">
            <label for="owner_id" class="form-label">Assignee</label>
            <select name="owner_id" id="assignee_id" class="form-select" disabled>
                <option value="{{ auth()->id() }}">Self Assignee</option>
            </select>
            @error('owner_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

          <!-- Type -->
          <div class="form-group mb-4">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Main task</option>
                <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Sub task</option>
            </select>
            @error('type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

       <!-- Parent Task -->
       <div class="form-group mb-4">
        <label for="parent_task" class="form-label">Parent Task</label>
        <select name="parent_task" id="parent_task" class="form-select" {{ old('type') == '1' ? 'disabled' : '' }}>
            <option value="">None</option>
            @foreach($parent_tasks as $task)
                <option value="{{ $task->id }}" data-project="{{ $task->project_id }}" {{ old('parent_task') == $task->id ? 'selected' : '' }}>
                    {{ $task->title }}
                </option>
            @endforeach
        </select>
        @error('parent_task')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Project -->
    <div class="form-group mb-4">
        <label for="project_id" class="form-label">Project</label>
        <select name="project_id" id="project_id" class="form-select">
            <option value="">None</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}" 
                    {{ old('project_id') == $project->id ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
            @endforeach
        </select>
        @error('project_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>



        <!-- Status -->
        <div class="form-group mb-4">
            <label for="status" class="form-label">Status</label>
            <input type="text" id="status_display" class="form-control" value="Not Started" readonly>
            <input type="hidden" name="status" id="status" value="1">
            @error('status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tags -->
        <div class="form-group mb-4">
            <label for="tags" class="form-label">Tags:</label>
            <select id="tags" name="tags[]" class="form-select" multiple>
            </select>
        </div>

      

        <!-- Dates -->
        <div class="form-group mb-4">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ old('start_date',$defaultStartDate) }}" required>
            @error('start_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="end_date" class="form-label">End Date</label>
            <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{ old('end_date',$defaultEndDate) }}"required>
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Media -->
          <div class="form-group mb-4">
            <label for="media" class="form-label">Add Media</label>
            <input type="file" name="media[]" id="media" class="form-control" multiple>
            <div id="media-names" class="mt-2"></div> <!-- Conteneur pour les noms des fichiers -->
            @error('media.*')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Submit</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tagsSelect = $('#tags');
    const form = document.querySelector('form');
    const teamSelect = document.getElementById('team_id');
    const assigneeSelect = document.getElementById('assignee_id');
    const mediaInput = document.getElementById('media');
    const typeSelect = document.getElementById('type');
    const parentTaskSelect = document.getElementById('parent_task');
    const projectSelect = document.getElementById('project_id');
    const mediaNamesContainer = document.getElementById('media-names');

    // Fonction pour afficher les aperçus des médias
    function displayMediaNames(files) {
        mediaNamesContainer.innerHTML = ''; // Vider le conteneur
        files.forEach(file => {
            const fileName = document.createElement('div');
            fileName.textContent = file.name;
            mediaNamesContainer.appendChild(fileName);
        });
    }
  
    mediaInput.addEventListener('change', (event) => {
        const files = Array.from(event.target.files);
        displayMediaNames(files);
    });

    // Fonction pour mettre à jour les membres de l'équipe assignés
    async function updateAssignees(teamId) {
        assigneeSelect.disabled = true;
        assigneeSelect.innerHTML = ''; // Effacer les options précédentes

        try {
            const response = await fetch(`/teams/${teamId}/members`);
            const data = await response.json();

            if (data.members.length > 0) {
                data.members.forEach(member => {
                    const option = document.createElement('option');
                    option.value = member.id;
                    option.textContent = member.name;
                    assigneeSelect.appendChild(option);
                });
                assigneeSelect.disabled = false;
            } else {
                // Si aucun membre n'est disponible, vous pouvez afficher un message ou gérer cette situation
                assigneeSelect.innerHTML = '<option value="">No members available</option>';
            }
        } catch (error) {
            console.error('Error fetching assignees:', error);
        }
    }

    // Mettre à jour les membres lors du changement de l'équipe
    teamSelect.addEventListener('change', (event) => {
        const teamId = event.target.value;
        if (teamId) {
            updateAssignees(teamId);
        } else {
            assigneeSelect.disabled = true;
            assigneeSelect.innerHTML = '<option value="">Select an assignee</option>';
        }
    });

    // Activer/désactiver le champ "Parent Task" en fonction de la sélection du type de tâche
    function toggleParentTaskField() {
        const isMainTask = typeSelect.value == '1';
        parentTaskSelect.disabled = isMainTask;
    }

    typeSelect.addEventListener('change', () => {
        toggleParentTaskField();
    });

    // Quand une tâche parente est sélectionnée
    parentTaskSelect.addEventListener('change', function() {
        const selectedOption = parentTaskSelect.options[parentTaskSelect.selectedIndex];
        const projectId = selectedOption.getAttribute('data-project');

        if (projectId) {
            projectSelect.value = projectId; // Mettre à jour le projet automatiquement
        }
    });

    // Vérifier et ajuster l'état du champ "Parent Task" au chargement initial de la page
    toggleParentTaskField();

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
                    tagsSelect[0].selectize.addOption({ id: data.tag.id, name: data.tag.name });
                    tagsSelect[0].selectize.addItem(data.tag.id);
                } else {
                    alert('Error creating tag');
                }
            })
            .catch(error => console.error('Error creating tag:', error));
        }
    });
});

</script>
@endsection

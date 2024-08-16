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
                <option value="not_started" {{ old('status', $task->status) == 'not_started' ? 'selected' : '' }}>Not Started</option>
                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            @error('status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

    <button type="submit" class="btn btn-primary">Update Task</button>
</form>
</div>

@endsection

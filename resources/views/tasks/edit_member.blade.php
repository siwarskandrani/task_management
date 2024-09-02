@extends('layouts.app')

@section('tasks_member_edit')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h1>Edit Task</h1>
        </div>
        <div class="card-body">
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
                <div class="form-group mb-4">
                    <label class="form-label">Existing Media</label>
                    <div id="existing_media" class="d-flex flex-wrap gap-2 ">
                        @foreach($task->media as $media)
                            <div class="media-item ml-2">
                                <a href="{{ asset('storage/' . $media->path) }}" class="btn btn-outline-secondary btn-sm" target="_blank">{{ $media->name }}</a>
                                {{-- <form action="{{ route('tasks.removeMedia', ['taskId' => $task->id, 'mediaId' => $media->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" onclick="submitDeleteForm(this)">×</button>
                                </form> --}}
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="media" class="form-label">Add Media</label>
                    <input type="file" name="media[]" id="media" class="form-control" multiple>
                    <small class="text-muted">You can upload multiple files at once.</small>
                    @error('media.*')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div id="media-preview" class="d-flex flex-wrap mt-2"></div>
                </div>

                <!-- Statut -->
                <div class="form-group mb-4">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="1" {{ old('status', $task->status) == '1' ? 'selected' : '' }}>Not Started</option>
                        <option value="2" {{ old('status', $task->status) == '2' ? 'selected' : '' }}>In Progress</option>
                        <option value="3" {{ old('status', $task->status) == '3' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Update Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

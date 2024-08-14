@extends('layouts.app')

@section('edit_project')
<div class="container p-5">
    <br>
    <div class="row">
        <div class="col align-self-start">
            <a class="btn btn-primary" href="">All projects</a>
        </div>
    </div>
    <br>
    
    @if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $item)
            <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="container p-5">
        <form action="{{ route('projects.update', $project->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input
                    type="text"
                    class="form-control"
                    name="name" {{-- Correspond au 'name' dans la validation du contrôleur --}}
                    id="name"
                    value="{{ old('name', $project->name) }}"
                />
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea
                    name="description"
                    id="description"
                    
                    class="form-control"
                    rows="3"
                > {{ old('description', $project->description) }}</textarea>
            </div>
           
        
        
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
</div>
@endsection

<!-- resources/views/teams/create.blade.php -->
@extends('layouts.app')

@section('create_team')
<div class="container p-5">
    <br>
    <div class="row">
        <div class="col align-self-start">
            <a class="btn btn-primary" href="{{ route('teams.index') }}">All teams</a>
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
        <form action="{{ route('teams.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input
                    type="text"
                    class="form-control"
                    name="name" {{-- Correspond au 'name' dans la validation du contrôleur --}}
                    id="name"
                />
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea
                    name="description"
                    id="description"
                    class="form-control"
                    rows="3"
                ></textarea>
            </div>
           
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
</div>
@endsection

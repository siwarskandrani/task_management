<!-- resources/views/teams/edit.blade.php -->
@extends('layouts.app')

@section('edit_team')
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
        <form action="{{ route('teams.update', $team->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input
                    type="text"
                    class="form-control"
                    name="name"
                    id="name"
                    value="{{ old('name', $team->name) }}"
                />
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea
                    name="description"
                    id="description"
                    class="form-control"
                    rows="3"
                >{{ old('description', $team->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="emails_member" class="form-label">Update Members (emails separated by commas)</label>
                <input
                    type="text"
                    class="form-control"
                    name="emails_member"
                    id="emails_member"
                    value="{{ old('emails_member', implode(',', $members->pluck('email')->toArray())) }}"
                />
                <small class="form-text text-muted">Enter multiple emails separated by commas.</small>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
@endsection

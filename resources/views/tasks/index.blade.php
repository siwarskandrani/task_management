@extends('layouts.app')

@section('tasks_index')
<div class="container">
    <h1>My Tasks</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($tasks->isEmpty())
        <p>No tasks available.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Project</th>
                    <th>Team</th>
                    <th>Status</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->project->name ?? 'N/A' }}</td>
                        <td>{{ $task->team->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $task->status)) }}</td>
                        <td>{{ $task->start_date }}</td>
                        <td>{{ $task->end_date }}</td>
                        <td>
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info btn-sm">show</a>

                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

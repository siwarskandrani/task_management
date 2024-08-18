@extends('layouts.app')

@section('tasks_index')
<div class="container p-5">
    <div class="row mb-4">
        <div class="col align-self-start">
            <a class="btn btn-primary" href="{{ route('tasks.create') }}">Create Task</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($tasks->isEmpty())
        <p>No tasks available.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover table-borderless table-primary align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Project</th>
                        <th>Team</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th width="200px">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach($tasks as $task)
                        <tr class="table-primary">
                            <td>{{ $task->title }}</td>
                            <td>{{ $task->project->name ?? 'N/A' }}</td>
                            <td>{{ $task->team->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $task->status)) }}</td>
                            <td>{{ $task->start_date }}</td>
                            <td>{{ $task->end_date }}</td>
                            <td>
                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info btn-sm">Show</a>

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
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('tasks_index')
<div class="container p-5">
    <div class="row mb-4 align-items-center">
        <!-- Status filter and Sort by date on the left -->
        <div class="col-md-4 d-flex align-items-center">
            <!-- Status filter -->
            <form action="{{ route('tasks.index') }}" method="GET" class="d-flex align-items-center me-3">
                <select name="status" class="form-select me-2" onchange="this.form.submit()" style="border-radius: 10px;">
                    <option value="">All Statuses</option>
                    <option value="Completed" {{ $statusFilter == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="In Progress" {{ $statusFilter == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Not Started" {{ $statusFilter == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                </select>
            </form>

            <!-- Sort by date -->
            <form action="{{ route('tasks.index') }}" method="GET" class="d-flex align-items-center ml-2">
                <select name="sort" class="form-select me-2" onchange="this.form.submit()" style="border-radius: 10px;">
                    <option value="">Sort By</option>
                    <option value="start_date_desc" {{ $sort == 'start_date_desc' ? 'selected' : '' }}>Start Date</option>
                    <option value="end_date_asc" {{ $sort == 'end_date_asc' ? 'selected' : '' }}>End Date</option>
                </select>
            </form>
        </div>

        <!-- Search input centered -->
        <div class="col-md-4 d-flex justify-content-center">
            <form action="{{ route('tasks.index') }}" method="GET" class="d-flex w-100">
                <input type="text" name="search" class="form-control me-2" placeholder="Search tasks..." value="{{ $searchQuery }}" style="border-radius: 10px;">
                <button type="submit" class="btn btn-outline-primary" style="border-radius: 10px;">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <!-- Create Task button on the right -->
        <div class="col-md-4 d-flex justify-content-end align-items-center">
            <a class="btn btn-primary d-flex align-items-center" href="{{ route('tasks.create') }}" style="border-radius: 10px;">
                <i class="bi bi-plus-lg me-2"></i> Create Task
            </a>
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
        <ul class="list-group">
            @foreach($tasks as $task)
                <li class="list-group-item mb-3 shadow-sm position-relative" style="border-radius: 10px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-2">{{ $task->title }}</h5>
                        </div>
                        <div class="position-absolute" style="top: 15px; right: 15px;">
                            <a href="{{ route('tasks.edit', $task->id) }}" class="text-primary me-3" title="Edit">
                                <i class="bi bi-pencil-square" style="font-size: 1.2rem;"></i>
                            </a>
                            <a href="{{ route('tasks.show', $task->id) }}" class="text-info me-3" title="View">
                                <i class="bi bi-eye" style="font-size: 1.2rem;"></i>
                            </a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0 text-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this task?');">
                                    <i class="bi bi-trash" style="font-size: 1.2rem;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div>
                            <p class="mb-1"><strong>Project:</strong> {{ $task->project->name ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Team:</strong> {{ $task->team->name ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="badge {{ $task->status == 'Completed' ? 'bg-success' : 'bg-warning' }} p-2" style="border-radius: 10px; font-size: 0.8rem;">
                                {{ $task->status_label }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="mb-1"><strong>Start Date:</strong> {{ $task->start_date }}</p>
                            <p class="mb-1"><strong>End Date:</strong> {{ $task->end_date }}</p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        <!-- Pagination links -->
        <div class="mt-4">
            {{ $tasks->appends(['search' => $searchQuery, 'status' => $statusFilter, 'sort' => $sort])->links() }}
        </div>
    @endif
</div>
@endsection

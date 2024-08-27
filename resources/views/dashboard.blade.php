@extends('layouts.app')

@section('dashboard')
<div class="py-12">
    <div class="container">
        <h1 class="text-center mb-5" style="font-size: 1.7rem; color: #333; , sans-serif; font-weight: 300;">You want to create:</h1>        <div class="row g-4 " >
            <!-- First Card -->
            <div class="col-md-4 col-sm-6">
                <a href="{{ route('teams.create') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="position-relative">
                            <img src="{{ asset('images/team.png') }}" class="card-img-top" alt="Team Image" style="object-fit: cover; height: 200px;">
                            <div class="position-absolute bottom-0 start-0 w-100 bg-dark text-white text-center p-2" style="background-color: #ff7a57">
                                Team
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Second Card -->
            <div class="col-md-4 col-sm-6">
                <a href="{{ route('tasks.create') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="position-relative">
                            <img src="{{ asset('images/task.png') }}" class="card-img-top" alt="Task Image" style="object-fit: cover; height: 200px;">
                            <div class="position-absolute bottom-0 start-0 w-100 bg-dark text-white text-center p-2" style="background-color: #ff7a57">
                                Task
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Third Card -->
            <div class="col-md-4 col-sm-6">
                <a href="{{ route('projects.create') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="position-relative">
                            <img src="{{ asset('images/project.png') }}" class="card-img-top" alt="Project Image" style="object-fit: cover; height: 200px;">
                            <div class="position-absolute bottom-0 start-0 w-100 bg-dark text-white text-center p-2" style="background-color: #ff7a57">
                                Project
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

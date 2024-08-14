@extends('layouts.app')

@section('dashboard')
<div class="py-12">
    <div class="container">
        <h1 class="mb-3">You want to create:</h1>
        <div class="row g-4">
            <!-- First Card -->
            <div class="col-md-4 col-sm-6">
                <a href="{{ route('teams.create') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="position-relative">
                            <img src="{{ asset('images/20240801080737.jpg') }}" class="card-img-top" alt="Team Image" style="object-fit: cover; height: 200px;">
                            <div class="position-absolute bottom-0 start-0 w-100 bg-dark text-white text-center p-2">
                                Team
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Second Card -->
            <div class="col-md-4 col-sm-6">
                <a href="" class="text-decoration-none">
                    <div class="card">
                        <div class="position-relative">
                            <img src="{{ asset('images/20240801080737.jpg') }}" class="card-img-top" alt="Task Image" style="object-fit: cover; height: 200px;">
                            <div class="position-absolute bottom-0 start-0 w-100 bg-dark text-white text-center p-2">
                                Task
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Third Card -->
            <div class="col-md-4 col-sm-6">
                <a href="" class="text-decoration-none">
                    <div class="card">
                        <div class="position-relative">
                            <img src="{{ asset('images/20240801080737.jpg') }}" class="card-img-top" alt="Project Image" style="object-fit: cover; height: 200px;">
                            <div class="position-absolute bottom-0 start-0 w-100 bg-dark text-white text-center p-2">
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

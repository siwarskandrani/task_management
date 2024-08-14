@extends('layouts.app')

@section('index_project')
<div class="container p-5">
    <br>
    <div class="row">
        <div class="col align-self-start">
            <a class="btn btn-primary" href="{{ route('projects.create') }}">Create project </a>
        </div>
    </div>
    <br>
    
    @if ($message = Session::get('success')) 
    {{-- Si un message de succès existe (provenant du contrôleur) --}}
    <div class="alert alert-success" role="alert">
        {{ $message }}  
    </div>           
    @endif

    @if ($message = Session::get('error'))
    <div class="alert alert-danger" role="alert">
        {{ $message }}
    </div>
    @endif
<div class="table-responsive">
    <table class="table table-striped table-hover table-borderless table-primary align-middle">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Description</th>
                
                <th width='300px'>Actions</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @foreach ($projects as $project) 
                {{-- La variable $products est définie dans le contrôleur (dans la méthode index).
                     Elle est passée à la vue products.index en utilisant compact('products'). --}}
                <tr class="table-primary">
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->description }}</td>                  
                    <td>
                        <form action="{{route('projects.destroy', $project->id) }}"method="post" style="display: inline-block;">
                            @csrf
                            @method('DELETE') 
                            {{-- Les formulaires HTML ne supportent que les méthodes GET et POST.
                                 Cependant, les conventions RESTful utilisées par Laravel impliquent l'utilisation de différentes méthodes HTTP (comme PUT, PATCH, et DELETE) pour différentes opérations CRUD. --}}
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                      <a class="btn btn-primary" href="{{ route('projects.edit', $project->id) }}">Edit</a> 
                       
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
    </div>
</div>
@endsection

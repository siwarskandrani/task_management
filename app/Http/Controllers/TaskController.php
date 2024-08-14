<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\Project; 
use App\Models\User;
use App\Models\Media;

class TaskController extends Controller
{
    public function index()
    {
        // Pour que seul le propriétaire des tâches puisse voir la liste de ses tâches
        $userId = auth()->id();
        $tasks = Task::where('owner', $userId)->get();
        
        // Passer les tâches à la vue
        return view('tasks.index', compact('tasks'));
    }
    public function create()
    {
        $userId = auth()->id();
        
        // Récupérer les projets appartenant à l'utilisateur connecté
        $projects = Project::where('owner', $userId)->get();
        
        // Récupérer les équipes auxquelles l'utilisateur connecté appartient
        $teams = Team::whereHas('users', function($query) use ($userId) {
            $query->where('user__teams.ID_user', $userId);
        })->get();
    
        // Récupérer toutes les tâches 
        $tasks = Task::all();
        
        return view('tasks.create', compact('projects', 'teams', 'tasks'));
    }
    
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'team_id' => 'required|exists:teams,id',//exists:teams,id: Il doit correspondre à un ID valide dans la table teams
        'owner_id' => 'required|exists:users,id',
        'project_id' => 'nullable|exists:projects,id',
        'status' => 'required|string|in:not_started,in_progress,completed',
        'type' => 'required|integer|in:1,2',
        'parent_task_id' => 'nullable|exists:tasks,id',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date', //after_or_equal règle par laravel il sufiit de l'ecrire
        'media.*' => 'mimes:jpg,jpeg,png,pdf|max:2048', //* car on peut telecharger plusieur fichiers donc chaque fichier doit respecter ces contraintes
    ]);

    //Création de la Tâche
    $ownerId = $request->input('owner_id');
    $input = $request->except('media');
    $input['owner'] = $ownerId;
    $task = Task::create($input);
    //Gestion des Fichiers Média
    if ($request->hasFile('media')) {
        foreach ($request->file('media') as $file) {
            $path = $file->store('task_media'); //on stock l file dans le dossier task_media
            $media = Media::create(['path' => $path]);
            $task->media()->attach($media->id);
        }
    }

    return redirect()->route('tasks.index')->with('success', 'New task added successfully');
}

    
    public function edit(Task $task)
    {
        if (auth()->id() !== $task->owner) {
            return redirect()->route('tasks.index')->with('error', 'Unauthorized access');
        }
    
        $teams = Team::whereHas('users', function($query) {
            $query->where('user__teams.ID_user', auth()->id());
        })->get();
    
        $members = $task->team->users ?? collect();
    
        return view('tasks.edit', compact('task', 'teams', 'members'));
    }
    
    public function update(Request $request, Task $task)
    {
        if (auth()->id() !== $task->owner) {
            return redirect()->route('tasks.index')->with('error', 'Unauthorized access');
        }
    
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'status' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'team_id' => 'required|exists:teams,id',
            'owner_id' => [
                'nullable',
                'exists:users,id',
                function($attribute, $value, $fail) use ($request) {
                    $team = Team::find($request->input('team_id'));
                    if (!$team || !$team->users->contains('id', $value)) {
                        $fail('The selected owner is not a member of the selected team.');
                    }
                },
            ],
        ]);
    
        $input = $request->only(['title', 'description', 'status', 'start_date', 'end_date', 'team_id', 'owner_id']);
        $task->update($input);
        
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully');
    }
    

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
    }
}


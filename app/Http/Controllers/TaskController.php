<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\Project; 
use App\Models\User;
use App\Models\Media;
use App\Models\Tag;

class TaskController extends Controller
{
    public function index()
    {
        // Pour que seul le propriétaire des tâches puisse voir la liste de ses tâches
        $userId = auth()->id();
        $tasks = Task::where('owner', $userId)->with('team')->get();
        
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
    
        // Récupérer toutes les tâches qui n'ont pas de parent_task
        $parent_tasks = Task::whereNull('parent_task')->get();
        
        $tags = Tag::all();
        return view('tasks.create', compact('projects', 'teams', 'parent_tasks','tags'));
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
        'tags.*' => 'exists:tags,id', // Validation pour les tags

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
    // Attacher les tags
    if ($request->has('tags')) {
        $task->tags()->attach($request->input('tags'));
    }
    return redirect()->route('tasks.index')->with('success', 'New task added successfully');
}

    
public function edit($id)
{
    $task = Task::findOrFail($id);

    // Récupérer toutes les équipes
    $teams = Team::all();

    // Vérifier si la tâche a une équipe assignée
    $members = $task->team ? $task->team->users : collect(); // Membres de l'équipe ou collection vide si aucune équipe

    // Récupérer tous les tags
    $tags = Tag::all();

    return view('tasks.edit', compact('task', 'teams', 'members', 'tags'));
}


public function update(Request $request, $id)
{
    $task = Task::findOrFail($id);

    // Validation des données
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'team_id' => 'required|exists:teams,id',
        'owner_id' => 'nullable|exists:users,id',
        'status' => 'required|in:not_started,in_progress,completed',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'tags' => 'nullable|array',
        'tags.*' => 'exists:tags,id',
        'media.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
    ]);

    // Mise à jour des informations de la tâche
    $task->update([
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'team_id' => $request->input('team_id'),
        'owner' => $request->input('owner_id'),
        'status' => $request->input('status'),
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
    ]);

    // Gestion des pièces jointes
    if ($request->hasFile('media')) {
        foreach ($request->file('media') as $file) {
            $path = $file->store('public/media');
            $task->media()->create(['path' => $path]);
        }
    }

    // Gestion des tags
    $task->tags()->sync($request->input('tags', []));

    return redirect()->route('tasks.index')->with('success', 'Task updated successfully');
}

public function show($id)
{
    $task = Task::findOrFail($id);
    return view('tasks.show', compact('task'));
}


    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
    }

    

    public function calendar()
    {
        $tasks = Task::all()->map(function($task) {
            // Define a list of colors
            $colors = ['#ff5733', '#33ff57', '#3357ff', '#ff33a6', '#f1c40f'];
    
            // Randomly assign a color
            $color = $colors[array_rand($colors)];
    
            return [
                'title' => $task->title,
                'start' => $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('Y-m-d') : null,
                'end' => $task->end_date ? \Carbon\Carbon::parse($task->end_date)->format('Y-m-d') : null,
                'description' => $task->description,
                'backgroundColor' => $color, // Background color for the event
                'borderColor' => $color      // Border color for the event (optional)
            ];
        });
    
        return view('tasks.calendar', ['tasks' => $tasks]);
    }
    
    }
    
    




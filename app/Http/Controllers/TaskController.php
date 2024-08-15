<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

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
        $userId = auth()->id(); //userId c le user qui son session est ouverte
        $tasks = Task::where('owner', $userId)->with('team')->get(); //en php: select * from table tasks where owner == userId n faisant un appel a la table teams appeleé par  la relation team (presente dans le model Task) car dans la formulaire ona besoin de team name : {{ $task->team->name }} 
        
        // Passer les tâches à la vue
        return view('tasks.index', compact('tasks'));
    }
    public function create()
    {
        $userId = auth()->id();
        
        // Récupérer les projets appartenant à l'utilisateur connecté
        $projects = Project::where('owner', $userId)->get(); 
        
        // Récupérer les équipes où l'utilisateur est admin
        $teams = Team::whereHas('users', function($query) use ($userId) { //on utilise whereHas quand on a l where sur une relation pas un attrtibut ==>users c'est une relation dans le model Team
            $query->where('user__teams.ID_user', $userId)
                  ->where('user__teams.role', 'admin'); 
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
            'team_id' => 'nullable|exists:teams,id', // Nullable pour permettre de créer une tâche sans équipe
            'owner_id' => 'nullable|exists:users,id|required_with:team_id', // Requis si team_id est présent
            'project_id' => 'nullable|exists:projects,id',
            'status' => 'required|string|in:not_started,in_progress,completed',
            'type' => 'required|integer|in:1,2',
            'parent_task_id' => 'nullable|exists:tasks,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'media.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,bmp,doc,docx,ppt,pptx,xls,xlsx,pdf',
            'tags.*' => 'exists:tags,id',
        ]);
    
        // Création de la tâche
        $task = Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'team_id' => $request->input('team_id'),
            'owner' => $request->input('owner_id') ?: auth()->id(), // Assigner à soi-même si aucun owner_id n'est fourni
            'project_id' => $request->input('project_id'),
            'status' => $request->input('status'),
            'type' => $request->input('type'),
            'parent_task_id' => $request->input('parent_task_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);
        
       // Gestion des fichiers média
if ($request->hasFile('media')) {
    foreach ($request->file('media') as $file) {
        $extension = $file->getClientOriginalExtension();
        $originalName = $file->getClientOriginalName(); // Récupère le nom original

        $path = $file->store('task_documents', 'public'); // Stocke le fichier dans le disque public

        $media = Media::create([
            'path' => $path,
            'name' => $originalName // Stocke le nom original du fichier
        ]);
        $task->media()->attach($media->id);
    }
}

        
        
        // Attacher les tags
        if ($request->has('tags')) {
            $task->tags()->attach($request->input('tags'));
        }
    
        return redirect()->route('tasks.index')->with('success', 'New task added successfully');
    }
    

    

    public function edit(Task $task)
    {
        $userId = auth()->id();
        
        // Récupérer les projets appartenant à l'utilisateur connecté
        $projects = Project::where('owner', $userId)->get(); 
        
        // Récupérer les équipes où l'utilisateur est admin
        $teams = Team::whereHas('users', function($query) use ($userId) {
            $query->where('user__teams.ID_user', $userId)
                  ->where('user__teams.role', 'admin'); 
        })->get();
        
        // Récupérer toutes les tâches qui n'ont pas de parent_task
        $parent_tasks = Task::whereNull('parent_task')->get();
        
        $tags = Tag::all();
        
        // Assurez-vous que la tâche inclut les relations nécessaires (team et owner)
      //  $task->load('team', 'owner');
      //  dd($task);
        return view('tasks.edit', compact('projects', 'teams', 'parent_tasks', 'tags', 'task'));
    }
    
    

    
    


public function update(Request $request, $id)
{      
     $task = Task::findOrFail($id);

    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'team_id' => 'nullable|exists:teams,id', // Nullable pour permettre de créer une tâche sans équipe
        'owner_id' => 'nullable|exists:users,id|required_with:team_id', // Requis si team_id est présent
        'project_id' => 'nullable|exists:projects,id',
        'status' => 'required|string|in:not_started,in_progress,completed',
        'type' => 'required|integer|in:1,2',
        'parent_task_id' => 'nullable|exists:tasks,id',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'media.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,bmp,doc,docx,ppt,pptx,xls,xlsx,pdf',
        'tags.*' => 'exists:tags,id',
    ]);

    // Création de la tâche
    $task->update([
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'team_id' => $request->input('team_id'),
        'owner' => $request->input('owner_id') ?: auth()->id(), // Assigner à soi-même si aucun owner_id n'est fourni
        'project_id' => $request->input('project_id'),
        'status' => $request->input('status'),
        'type' => $request->input('type'),
        'parent_task_id' => $request->input('parent_task_id'),
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
    ]);
    
    // Gestion des fichiers média
    if ($request->hasFile('media')) {
        foreach ($request->file('media') as $file) {
            $extension = $file->getClientOriginalExtension();

            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $path = $file->store('task_images');
            } else {
                $path = $file->store('task_documents');
            }

            $media = Media::create(['path' => $path]);
            $task->media()->attach($media->id);
        }
    }
    
    // Attacher les tags
    if ($request->has('tags')) {
        $task->tags()->attach($request->input('tags'));
    }

    return redirect()->route('tasks.index')->with('success', 'New task adupdated successfully');
}


public function show(Task $task)
{
    // Chemin relatif depuis le dossier public
    $filePath = 'task_documents/' . $task->document_path;

    // Obtenez l'URL du fichier
    $url = Storage::disk('public')->url($filePath);

    return view('tasks.show', compact('task', 'url'));
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
    
    




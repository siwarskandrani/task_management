<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\Project; 
use App\Models\User;
use App\Models\Media;
use App\Models\Tag;
use App\Notifications\TaskUpdatedNotification;
use App\Notifications\TaskDeletedNotification;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        
        // Get search query, status filter, and sorting parameter from the request
        $searchQuery = $request->input('search'); //search houwa bidou name= fel formulaire==> le controleur va recuperer l name = search elli fel form
        $statusFilter = $request->input('status', ''); // Default to no status filter
        $sort = $request->input('sort', ''); // Default to no sorting
        
        // Map status values to their corresponding integers
        $statusMap = [
            'Not Started' => 1,
            'In Progress' => 2,
            'Completed' => 3,
        ];
        
        // Start the query
        $tasksQuery = Task::where('owner', $userId)->whereNull('parent_task'); //whereNull pour inclure seulemnt les main task
        
        // Apply search filter if present
        if ($searchQuery) {
            $tasksQuery->where(function($query) use ($searchQuery) {
                $query->where('title', 'like', "%{$searchQuery}%")
                      ->orWhere('description', 'like', "%{$searchQuery}%");
            });
        }
        
        // Apply status filter if present
        if ($statusFilter) {
            $statusValue = $statusMap[$statusFilter] ?? null;
            if ($statusValue) {
                $tasksQuery->where('status', $statusValue);
            }
        }
        
        // Apply sorting if present
        switch ($sort) {
            case 'start_date_desc':
                $tasksQuery->orderBy('start_date', 'desc');
                break;
            case 'end_date_asc':
                $tasksQuery->orderBy('end_date', 'asc');
                break;
            default:
                break;
        }
        
        // Fetch tasks with related team data
        $tasks = $tasksQuery->with('team')->paginate(5);
        
        // Pass data to view
        return view('tasks.index', compact('tasks', 'searchQuery', 'statusFilter', 'sort'));
    }
    

    
    
    public function create()
    {
        $userId = auth()->id();

        $defaultStartDate = now()->format('Y-m-d\TH:i');
        $defaultEndDate = now()->addMinutes(5)->format('Y-m-d\TH:i');
        
        // Récupérer les projets appartenant à l'utilisateur connecté
        $projects = Project::where('owner', $userId)->get(); 
        
        // Récupérer les équipes où l'utilisateur est admin
        $teams = Team::whereHas('users', function($query) use ($userId) { //on utilise whereHas quand on a l where sur une relation pas un attrtibut ==>users c'est une relation dans le model Team
            $query->where('user__teams.ID_user', $userId)
                  ->where('user__teams.role', 'admin'); 
        })->get();

        // Récupérer toutes les tâches qui n'ont pas de parent_task
        $parent_tasks = Task::whereNull('parent_task')->where('owner', $userId)->get();

     
        
        return view('tasks.create', compact('projects', 'teams', 'parent_tasks','defaultStartDate', 'defaultEndDate'));
    }
    
    
    
    public function store(Request $request)
    {
        $defaultStartDate = now()->format('Y-m-d\TH:i');
        $defaultEndDate = now()->addMinutes(5)->format('Y-m-d\TH:i');

       

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_id' => 'nullable|exists:teams,id', // Nullable pour permettre de créer une tâche sans équipe
            'owner_id' => 'nullable|exists:users,id|required_with:team_id', // Requis si team_id est présent
            'project_id' => 'required|nullable|exists:projects,id',
            'status' => 'required|integer|in:1,2,3',
            'type' => 'required|integer|in:1,2',
            'parent_task' => 'nullable|exists:tasks,id',
            'start_date' => 'required|date_format:Y-m-d\TH:i',
            'end_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:start_date',
            'media.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,bmp,doc,docx,ppt,pptx,xls,xlsx,pdf',
            'tags.*' => 'string',
        ]);

        $startDate = $request->input('start_date', $defaultStartDate);
         $endDate = $request->input('end_date', $defaultEndDate);
        
        // Création de la tâche
        $task = Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'team_id' => $request->input('team_id'),
            'owner' => $request->input('owner_id') ?: auth()->id(), // Assigner à soi-même si aucun owner_id n'est fourni
            'project_id' => $request->input('project_id'),
            'status' => (int) $request->input('status'),
            'type' => $request->input('type'),
            'parent_task' => $request->input('parent_task'),
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

        
        
        // Attacher ou créer des tags
        if ($request->has('tags')) {
        // Collecter les tags soumis
        $tags = collect($request->input('tags'))->map(function ($tagName) {
            // Rechercher ou créer un tag
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            return $tag->id; // Retourne l'id du tag
        });
        // Attacher les tags à la tâche
        $task->tags()->attach($tags);
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
        $parent_tasks = Task::whereNull('parent_task')->where('owner', $userId)->get();
        
        $tags = Tag::all();
        
          // Vérifier si l'utilisateur est le propriétaire de la tâche
    $isOwner = $task->owner == $userId;

      //  $task->load('team', 'owner');
     //  dd($task);
     
      $isAdmin = false;

      // Vérifiez si la tâche est associée à une équipe
      if ($task->team_id && $task->team) {
          // Vérifiez si l'utilisateur est un administrateur de l'équipe associée à la tâche
          $isAdmin = $task->team->users()
              ->wherePivot('role', 'admin')
              ->where('user__teams.ID_user', $userId)
              ->exists();
      }

      // Si l'utilisateur n'est ni le propriétaire ni un admin, rediriger avec un message d'erreur
      //Pour la page by_user
    if (!$isOwner && !$isAdmin) {
        return back()->with('error', 'Vous n\'êtes pas autorisé à modifier cette tâche.');
    }

      $isEditable = !$task->team_id || $isAdmin; // Champs ouverts si team_id est nul ou utilisateur est admin
   // dump($isEditable);
      if (!$isEditable) {
        return view('tasks.edit_member', compact('projects', 'teams', 'parent_tasks', 'tags', 'task','isEditable'));
    }
        return view('tasks.edit', compact('projects', 'teams', 'parent_tasks', 'tags', 'task'));
    }
    
    

    
    


    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $userId = auth()->id();
    
        // Vérifiez si l'utilisateur est un administrateur de l'équipe associée à la tâche
        $isAdmin = false;
        if ($task->team_id && $task->team) {
            $isAdmin = $task->team->users()
                ->wherePivot('role', 'admin')
                ->where('user__teams.ID_user', $userId)
                ->exists();
        }
        $isEditable = !$task->team_id || $isAdmin; 

        if (!$isEditable) {
            // Validation pour les non-admins (edit_member)
            $request->validate([
                'status' => 'required|integer|in:1,2,3',
                'media.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,bmp,doc,docx,ppt,pptx,xls,xlsx,pdf',
            ]);
    
            // l'update
            $input = $request->all();
            $task->update($input);

        } else {
            // Validation pour les admins (edit)
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'team_id' => 'nullable|exists:teams,id', // Nullable pour permettre de créer une tâche sans équipe
                'owner_id' => 'nullable|exists:users,id|required_with:team_id', // Requis si team_id est présent
                'project_id' => 'required|nullable|exists:projects,id',
                'status' => 'required|integer|in:1,2,3',
                'type' => 'required|integer|in:1,2',
                'parent_task' => 'nullable|exists:tasks,id',
                'start_date' => 'required|date_format:Y-m-d\TH:i',
                'end_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:start_date',                'media.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,bmp,doc,docx,ppt,pptx,xls,xlsx,pdf',
                'media.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,bmp,doc,docx,ppt,pptx,xls,xlsx,pdf',
                'tags.*' => 'required',
            ]);
    
            // Mise à jour de la tâche
            $task->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'team_id' => $request->input('team_id'),
                'owner' => $request->input('owner_id') ?: auth()->id(), // Assigner à soi-même si aucun owner_id n'est fourni
                'project_id' => $request->input('project_id'),
                'status' => (int) $request->input('status'),
                'type' => $request->input('type'),
                'parent_task' => $request->input('parent_task'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ]);
        }
    


        
        // Gestion des fichiers media
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
    


        
       //  Traitement des tags
      // Attacher ou créer des tags
      if ($request->has('tags')) {
        // Collecter les tags soumis
        $tags = collect($request->input('tags'))->map(function ($tagName) {
            // Rechercher ou créer un tag
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            return $tag->id; // Retourne l'id du tag
        });
        // Attacher les tags à la tâche
        $task->tags()->sync($tags);
    } else {
        // Si aucun tag n'est fourni, détacher tous les tags associés
        $task->tags()->detach(); //  retire tous les tags associés à la tâche
    }

    
    

    
        // // Envoyer des notifications si la tâche est associée à une équipe ==>7elha mbaad. rani sakartha bch manakhsarch l mailtrap
        // if ($task->team_id) {
        //     $team = Team::findOrFail($task->team_id);
        //     $teamMembers = $team->users; // Récupère les utilisateurs de l'équipe
        //     Notification::send($teamMembers, new TaskUpdatedNotification($task));
        // }
    
        return redirect()->route('tasks.index')->with('success', 'Tâche mise à jour avec succès');
    }
    


public function show(Task $task)
{
    $filePath = 'task_documents/' . $task->document_path;

    $url = Storage::disk('public')->url($filePath);

    $subTasks = $task->subtasks;

    return view('tasks.show', compact('task', 'url','subTasks'));
}







public function destroy(Request $request, Task $task)
{
    $userId = auth()->id();
    $isAdmin = false;


    $team = $task->team;
   // $assignee = $task->owner;

    // Vérifiez si la tâche est associée à une équipe
    if ($task->team_id && $task->team) {
        // Vérifiez si l'utilisateur est un administrateur de l'équipe associée à la tâche
        $isAdmin = $task->team->users()
            ->wherePivot('role', 'admin')
            ->where('user__teams.ID_user', $userId)
            ->exists();
    }

    // Déterminez si la tâche est supprimable
    $isDeletable = $isAdmin || !$task->team_id;


    // Si l'utilisateur n'est pas autorisé à supprimer la tâche
    if (!$isDeletable) {
        return redirect()->back()->with('error', 'You do not have permission to delete this task.');
    }

    // Suppression de la tâche
    $task->delete();
    //dd($assignee);
    //notif
    // foreach ($team->users as $user) {
    //     $user->notify(new TaskDeletedNotification($task));
    // }
    // Rediriger vers l'URL précédente
    return redirect()->to(url()->previous())->with('success', 'Task deleted successfully'); //prevouis pour q'uil me redirige vers l'url quiest deja ouvert
}





    
public function calendar()
{
    $userId = auth()->id(); 

    // Récupérer les tâches où l'utilisateur est le propriétaire
    $ownerTasks = Task::where('owner', $userId)->get();

    // Récupérer les tâches où l'utilisateur est un membre de l'équipe associée
    $memberTasks = Task::whereHas('team.users', function($query) use ($userId) {
        $query->where('users.id', $userId);
    })->get();

    // Fusionner les deux collections de tâches
    $tasks = $ownerTasks->merge($memberTasks)->map(function($task) {
        $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF)); // Génère une couleur hexadécimale aléatoire
    
        return [
            'title' => $task->title,
            'start' => $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('Y-m-d') : null,
            'end' => $task->end_date ? \Carbon\Carbon::parse($task->end_date)->format('Y-m-d') : null,
            'description' => $task->description,
            'backgroundColor' => $color,
            'borderColor' => $color
        ];
    });
    

    return view('tasks.calendar', compact('tasks'));
}




    

 

public function showWorkloadByTeam(Request $request)
{
    // Récupérer l'utilisateur connecté
    $user = auth()->user();
    
    // Récupérer les équipes auxquelles l'utilisateur appartient
    $teams = $user->teams()->with(['users.tasks'])->get();

     // Récupérer le terme de recherche
     $search = $request->input('search', '');

    // Filtrer les tâches pour chaque utilisateur afin de ne montrer que celles de l'équipe
    foreach ($teams as $team) {
        foreach ($team->users as $user) {
            $user->tasks = $user->tasks()->where('team_id', $team->id)
            ->get();
        }
    }

   // Récupérer les équipes auxquelles l'utilisateur appartient et filtrer par titre
   $teams = $user->teams()
   ->where('name', 'like', '%' . $search . '%')
   ->with(['users.tasks'])
   ->get();

    return view('workload.index', compact('teams','search'));
}




public function showTasksByUser($id, $team_id)
{
    $user = User::findOrFail($id);
    $tasks = $user->tasks()
    ->where('team_id', $team_id)
    ->whereNull('parent_task')
    ->where('type', 1)
    ->get();

    
    return view('tasks.by_user', compact('user', 'tasks'));
}

}
    
    




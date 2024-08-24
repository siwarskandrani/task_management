<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
    //pour que seul le propriétaire de projet peut voir la lise de ses projests
    $userId = auth()->id();

    $projects = Project::with('tasks') //on a fait un appel a la relation tasks qui est dans l model project
    ->where('owner', $userId)
    ->get();    

    // Passer les projets à la vue
    return view('projects.index', compact('projects'));
    }




    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([  // Lorsque l'utilisateur soumet un formulaire sur la page de création (générée par la méthode create), les données du formulaire sont envoyées au serveur via une requête POST.Laravel capture cette requête et la rend disponible via l'objet Request
            'name' => 'required',
            'description' => 'required',

        ]);
         //Récupération de toutes les données de la requête :
         $input = $request->all() ;//les attributs elli 3amarnehom fel create lkol bch n7otouhom d variable input
         // Ajouter l'ID de l'utilisateur connecté à la requête :
        $input['owner'] = auth()->id();
         $project = Project::create($input);
         return redirect()->route('dashboard')->with('success', 'New team added successfully');
        }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

  /**
 * Show the form for editing the specified resource.
 */
public function edit(Project $project)
{
    // Vérifier si l'utilisateur connecté est le propriétaire du projet
    if (auth()->id() !== $project->owner) {
        return redirect()->route('projects.index')->with('error', 'Unauthorized access');
    }

    return view('projects.edit', compact('project'));
}



    /**
 * Update the specified resource in storage.
 */
public function update(Request $request, Project $project)
{
    // Vérifier si l'utilisateur connecté est le propriétaire du projet
    if (auth()->id() !== $project->owner) {
        return redirect()->route('projects.index')->with('error', 'Unauthorized access');
    }

    $request->validate([
        'name' => 'required',
        'description' => 'required',
    ]);

    // Récupération des données validées
    $input = $request->only(['name', 'description']);

    // Mettre à jour le projet
    $project->update($input);
    
    return redirect()->route('projects.index')->with('success', 'Project updated successfully');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'project deleted successfully');
    }
}

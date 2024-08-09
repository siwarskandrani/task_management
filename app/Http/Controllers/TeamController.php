<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\User; 
use App\Mail\TeamInvitation;
use Illuminate\Support\Facades\Mail;
class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $user = auth()->user(); //user c'est l'utilisateur connecté
    $teams = $user->teams; // Récupère toutes les équipes associées à l'utilisateur
    
    return view('teams.index', compact('teams')); // Passe l teams à la vue. teams hedhi hiya bidha $teams
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([  // Lorsque l'utilisateur soumet un formulaire sur la page de création (générée par la méthode create), les données du formulaire sont envoyées au serveur via une requête POST.Laravel capture cette requête et la rend disponible via l'objet Request
        'name' => 'required',
        'description' => 'required',
        'emails_member' => 'nullable|string',
        ]);
        //Récupération de toutes les données de la requête :
        $input = $request->only(['name', 'description']); //les attributs elli 3amarnehom fel create lkol bch n7otouhom d variable input
         //Création du produit :
         $team = Team::create($input); //create est une méthode fournie par Laravel pour insérer une nouvelle entrée dans la base de données en utilisant le modèle Eloquent==> c pas la méthode create qu'on a créé au dessus
          // Ajout de l'utilisateur créateur à la table pivot avec le rôle 'admin'
        $user = auth()->user();  //$user c t'utilisateur connecté
        $user->teams()->attach($team->id, ['role' => 'admin']); // Ajout à la table pivot user__teams. ici on a associe l'équipe nouvellement créée à cet utilisateur en utilisant la méthode attach sur la relation teams. on a fait une attach entre le user cnnecté et le team donc entre le model user et team .teams()ici c la methode l mawjoida f table user .houwa wahdou wahdou mchee 3raf elli hiya mawjouda f table user khtr parce que $user est une instance de modèle User.

          // Traitement des e-mails des membres
      // On découpe la chaîne de caractères en un tableau d'emails
         $emails = array_map('trim', explode(',', $request->input('emails_member', '')));
    
    foreach ($emails as $email) {
        if (!empty($email)) {
            $user = User::where('email', $email)->first();
            if ($user) {
                // Ajout du membre à l'équipe avec le rôle 'member'
                $team->users()->attach($user->id, ['role' => 'member']);
                
                // Envoi de l'e-mail d'invitation
                Mail::to($user->email)->send(new TeamInvitation($team));
            }
        }
    }

    // Redirection avec message de succès
    return redirect()->route('dashboard')->with('success', 'New team added successfully');
}

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        $user = auth()->user();
    
        // Vérifie si l'utilisateur est un admin 
        if ($user->teams()->wherePivot('ID_team', $team->id)->wherePivot('role', 'admin')->exists()) {
            // L'utilisateur est un admin ==> affiche formulaire:

         // Récupère les membres en eliminant admin
         $members = $team->users()->wherePivot('role', 'member')->get();


        return view('teams.edit', compact('team','members'));
        } else {
            // L'user n'est pas un admin==>pas accès
            return redirect()->route('teams.index')->with('error', 'You do not have permission to edit this team');
        }
    }
   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        // Valider les données de la requête
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'emails_member' => 'nullable|string',
        ]);
    
        // Mettre à jour les informations de l'équipe
        $team->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);
    
        // Traitement des e-mails des membres
        $emails = array_map('trim', explode(',', $request->input('emails_member', '')));
    
        // Obtenir les IDs des membres actuels
        $currentMemberIds = $team->users->pluck('id')->toArray();
    
        // Obtenir les id des new membres
        $newMemberIds = [];
        foreach ($emails as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $newMemberIds[] = $user->id;
            }
        }
    
        // Ajouter les nouveaux membres
        foreach ($newMemberIds as $userId) {
            if (!in_array($userId, $currentMemberIds)) {
                $team->users()->attach($userId, ['role' => 'member']);
            }
        }
    
        // Supprimer les membres qui ne sont plus dans la liste
        foreach ($currentMemberIds as $userId) {
            if (!in_array($userId, $newMemberIds)) {
                $team->users()->detach($userId);
            }
        }
    
        // Rediriger avec un message de succès
        return redirect()->route('teams.index')->with('success', 'Team updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        $user = auth()->user(); // Récupère l'utilisateur connecté

        // Vérifie si l'utilisateur est un admin pour l'équipe donnée
        if ($user->teams()->wherePivot('ID_team', $team->id)->wherePivot('role', 'admin')->exists()) {
            // L'utilisateur est un admin, donc on peut supprimer l'équipe
            $team->delete();
            return redirect()->route('teams.index')->with('success', 'Team deleted successfully');
        } else {
            // L'utilisateur n'est pas un admin, donc on refuse l'accès
            return redirect()->route('teams.index')->with('error', 'You do not have permission to delete this team');
        }
    }
    



}

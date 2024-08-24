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
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'emails_member' => 'nullable|string',
        ]);
    
        // Récupération des données du formulaire
        $input = $request->only(['name', 'description']);
        $team = Team::create($input);
    
        // Ajout de l'utilisateur créateur à l'équipe
        $user = auth()->user();
        $user->teams()->attach($team->id, ['role' => 'admin']);
    
        // Traitement des e-mails des membres
        $emails = array_map('trim', explode(',', $request->input('emails_member', '')));
        $invalidEmails = [];
    
        foreach ($emails as $email) {
            if (!empty($email)) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    // Ajouter les utilisateurs existants à l'équipe
                    $team->users()->attach($user->id, ['role' => 'member']);
                    // Envoyer un e-mail d'invitation
                     // Mail::to($user->email)->send(new TeamInvitation($team));
                } else {
                    // Utilisateurs non enregistrés, envoyez un e-mail d'invitation
                    $invitationLink = route('register', ['email' => $email, 'team_id' => $team->id]);
                   // Mail::to($email)->send(new TeamInvitation($team, $invitationLink));
                   $invalidEmails[] = $email;
                }
            }
        }
    
        // Message d'alerte pour les e-mails non valides
        $message = 'L\'équipe a été créée avec succès.';
        if (!empty($invalidEmails)) {
            $message .= ' Les e-mails suivants n\'ont pas de compte enregistré et ont reçu une invitation pour s\'inscrire : ' . implode(', ', $invalidEmails) . '.';
        }
        //dd($message);

        // Redirection avec message de succès
        return redirect()->route('teams.index')->with('success', $message);
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        $user = auth()->user();
        // Récupère tous les membres sauf l'admin
        $members = $team->users()->where('users.id', '!=', $user->id)->get();
        
        return view('teams.show', compact('team', 'members'));
    }
    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        $user = auth()->user();
        $allUsers = User::all(); 
        // Vérifie si l'utilisateur est un admin 
        if ($user->teams()->wherePivot('ID_team', $team->id)->wherePivot('role', 'admin')->exists()) {
            // L'utilisateur est un admin ==> affiche formulaire:

         // Récupère les membres en eliminant admin
         $members = $team->users()->wherePivot('role', 'member')->get();


        return view('teams.edit', compact('team','members','allUsers'));
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
    $invalidEmails = [];

    // Obtenir les IDs des membres actuels
    $currentMemberIds = $team->users->pluck('id')->toArray();

    // Obtenir les IDs des nouveaux membres
    $newMemberIds = [];
    foreach ($emails as $email) {
        // Valider l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $invalidEmails[] = $email;
            continue;
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            $newMemberIds[] = $user->id;
            if (!in_array($user->id, $currentMemberIds)) {
                // Ajouter les nouveaux membres qui ne sont pas déjà dans l'équipe
                $team->users()->attach($user->id, ['role' => 'member']);
                // Envoyer un e-mail d'invitation
                // Mail::to($user->email)->send(new TeamInvitation($team));
            }
        } else {
            // Utilisateurs non enregistrés, envoyez un e-mail d'invitation
            $invitationLink = route('register', ['email' => $email, 'team_id' => $team->id]);
            // Mail::to($email)->send(new TeamInvitation($team, $invitationLink));
            $invalidEmails[] = $email;
        }
    }

    // Message d'alerte pour les e-mails non valides
    $message = 'L\'équipe a été mise à jour avec succès.';
    if (!empty($invalidEmails)) {
        $message .= ' Les e-mails suivants n\'ont pas de compte enregistré et ont reçu une invitation pour s\'inscrire : ' . implode(', ', $invalidEmails) . '.';
    }

    // Rediriger avec un message de succès
    return redirect()->route('teams.index')->with('success', $message);
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
    

    public function members($teamId)
    {
        $team = Team::findOrFail($teamId);
        $members = $team->users; //la relation entre teams et users elle s'appelle users(c la ftc )
    
        return response()->json(['members' => $members]);
    }
    
    public function removeMember(Team $team, User $user)
{
    // Vérifie que l'utilisateur fait partie de l'équipe
    if ($team->users()->where('user_id', $user->id)->exists()) {
        // Supprime l'utilisateur de l'équipe
        $team->users()->detach($user->id);

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'User not found in team'], 404);
}

public function destroyMember($teamId, $memberId)
{
    $team = Team::findOrFail($teamId);
    $user = auth()->user();

    if (!$user->teams()->wherePivot('ID_team', $teamId)->wherePivot('role', 'admin')->exists()) {
        return redirect()->route('teams.show', $teamId)->with('error', 'Unauthorized action.');
    }

    // Trouver et supprimer le membre
    $team->users()->detach($memberId);

    return redirect()->route('teams.show', $teamId)->with('success', 'Member removed successfully.');
}




}

<?php
namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TeamInvitation;

class InvitationController extends Controller
{
    public function create()
    {
        $teams = auth()->user()->teams; // Assurez-vous que l'utilisateur peut voir ses équipes
        return view('invitations.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:invitations,email',
            'team_id' => 'required|exists:teams,id',
        ]);

        $token = Str::random(32);

        $invitation = Invitation::create([
            'email' => $request->input('email'),
            'team_id' => $request->input('team_id'),
            'token' => $token,
        ]);

        Mail::to($invitation->email)->send(new TeamInvitation($invitation->team, $invitation));

        return redirect()->back()->with('success', 'Invitation envoyée avec succès!');
    }

    public function index()
    {
        $invitations = Invitation::where('email', auth()->user()->email)->get();
        return view('invitations.index', compact('invitations'));
    }

    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

       
        return redirect()->route('teams.index')->with('success', 'Invitation acceptée!');
    }
}

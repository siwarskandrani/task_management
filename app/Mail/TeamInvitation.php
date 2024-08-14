<?php

namespace App\Mail;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $team;
    public $invitationLink;

    /**
     * Create a new message instance.
     *
     * @param Team $team
     * @param string|null $invitationLink
     */
    public function __construct(Team $team, $invitationLink = null)
    {
        $this->team = $team;
        $this->invitationLink = $invitationLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->invitationLink) {
            return $this->subject('Invitation to Join Team ' . $this->team->name)
                        ->view('emails.team_invitation_new_user')
                        ->with([
                            'teamName' => $this->team->name,
                            'invitationLink' => $this->invitationLink,
                        ]);
        } else {
            return $this->subject('Invitation to Join Team ' . $this->team->name)
                        ->view('emails.team_invitation_existing_user')
                        ->with([
                            'teamName' => $this->team->name,
                        ]);
        }
    }
}

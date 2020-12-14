<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvitationToJoinTeam extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;
    private  $user_exist;
    public function __construct(Invitation $invitation, $user_exist)
    {
        $this->invitation = $invitation;
        $this->user_exist = $user_exist;
    }

    public function build()
    {
        if($this->user_exist){
            $url = config('app.client_url').'/settings/teams';
            return $this->markdown('emails.invitations.invite-user')
                ->subject('Invitation to join team '.$this->invitation->team->name)
                ->with([
                    'url'=>$url
                ]);
        }else{
            $url = config('app.client_url').'/register?invitation='.$this->invitation->u_id;

            return $this->markdown('emails.invitations.invite-new-user')
                ->subject('Invitation to join team '.$this->invitation->team->name)
                ->with([
                    'url'=>$url
                ]);
        }

    }
}

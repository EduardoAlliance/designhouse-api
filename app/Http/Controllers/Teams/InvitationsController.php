<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Mail\SendInvitationToJoinTeam;
use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationsController extends Controller
{
    public function invite(Request $request, Team $team){

        $request->validate([
            'email'=>'required'
        ]);
        $user = auth()->user();
        //check if user owns the team
        if(!$user->isOwnerOfTeam($team)){
            return response()->json(['error'=>'You are not the team owner'],401);
        }
        //check if emas has a pending invitation
        if($team->hasPendingInvite($request->email)){
            return response()->json(['error'=>'Email already has a pending invite'],401);
        }

        //get user
        $recipient = User::where('email',$request->email)->first();

        if(!$recipient){
            $this->createInvitation(false,$team,$request->email);
            return response()->json([
                'message'=>'Invitation sent to user'
            ],200);
        }

        //check if the team  already has the user
        if($team->hasUser($recipient)){
            return response()->json([
                'message'=>'This user is already a team member'
            ],422);
        }

        //send the invitation to the user
        $this->createInvitation(true,$team,$request->email);
        return response()->json([
            'message'=>'Invitation sent to user'
        ],200);

    }

    public function resend(Invitation $invitation){
        //get user
        $recipient = User::where('email',$invitation->recipient_email)->first();
        //check if user owns the team
        if(!auth()->user()->isOwnerOfTeam($invitation->team)){
            return response()->json(['error'=>'You are not the team owner'],401);
        }
        Mail::to($invitation->recipient_email)
            ->send(new SendInvitationToJoinTeam($invitation,!is_null($recipient)));
        return response()->json([
            'message'=>'Invitation resend'
        ],200);
    }

    public function respond(Request $request, Invitation $invitation){

        $request->validate([
            'token'=>'required',
            'decision'=>'required'
        ]);
        //check if invitation belongs to this user
        $this->authorize('respond',$invitation);
        // check token match
        if($invitation->token !== $request->token){
            return response()->json(['message'=>'Invalid token'],401);
        }

        if($request->decision !== 'deny'){
            $invitation->team->addUserToTeam(auth()->id());
        }

        $invitation->delete();
        return response()->json(['message'=>'Success'],200);
    }

    public function destroy(Invitation $invitation){
        $this->authorize('delete',$invitation);
        $invitation->delete();
        return response()->json(['message'=>"Deleted"],200);
    }

    protected function createInvitation($user_exist, Team $team, $email){
        $invitation = Invitation::create([
            'team_id'=>$team->id,
            'sender_id'=>auth()->id(),
            'recipient_email' => $email,
            'token'=> Str::uuid(),
            'u_id'=>Str::uuid()
        ]);

        Mail::to($email)
            ->send(new SendInvitationToJoinTeam($invitation,$user_exist));
    }

}

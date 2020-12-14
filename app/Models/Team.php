<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
      'name','owner_id','slug','u_id'
    ];

    public function getRouteKeyName(){
        return 'u_id';
    }

    public function owner(){
        return $this->belongsTo(User::class,'owner_id');
    }

    public function members(){
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function designs(){
        return $this->hasMany(Design::class);
    }

    public function invitations(){
        return $this->hasMany(Invitation::class);
    }

    public function hasPendingInvite($email){
        return (bool)$this->invitations()
            ->where('recipient_email',$email)
            ->count();
    }

    public function hasUser(User $user){
        return (bool) $this->members()
            ->where('user_id',$user->id)
            ->count();
    }

    public function addUserToTeam($user_id){
        $this->members()->attach($user_id);
    }

    public function removeUserFromTeam($user_id){
        $this->members()->detach($user_id);
    }



}

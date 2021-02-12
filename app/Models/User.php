<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable, SpatialTrait;

    protected $fillable = [
        'name', 'email', 'password','tagline','about','location','available_to_hire','formatted_address',
    ];
    protected $spatialFields = [
        'location',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //relations
    public function designs(){
        return $this->hasMany(Design::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function teams(){
        return $this->belongsToMany(Team::class)->withTimestamps();
    }

    //invitations
    public function invitations(){
        return $this->hasMany(Invitation::class,'recipient_email','email');
    }

    //relationship for chat messaging

    public function chats(){
        return $this->belongsToMany(Chat::class,'participants');
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function getChatWithUser($user_id){
        $chat = $this->chats()->whereHas('participants',function($q) use ($user_id){
            $q->where('user_id',$user_id);
        })->first();
        return $chat;
    }




    // end relations

    public function ownedTeams(){
        return $this->teams()->when('owner_id',$this->id);
    }

    public function isOwnerOfTeam($team){
        return (bool)$this->teams()->where('id',$team->id)->where('owner_id',$this->id)->count();
    }

    public function sendEmailVerificationNotification(){
        $this->notify(new VerifyEmail);
    }

    public function sendPasswordResetNotification($token){
        $this->notify(new ResetPassword($token));
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}

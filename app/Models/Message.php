<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    //protected $touches=['chat'];

    use SoftDeletes;
    protected $fillable = [
        'user_id','chat_id','body','last_read'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function sender(){
        return $this->belongsTo(User::class,'user_id');
    }

}

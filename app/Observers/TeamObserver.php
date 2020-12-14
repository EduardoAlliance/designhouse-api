<?php

namespace App\Observers;

use App\Models\Team;

class TeamObserver
{

    public function created(Team $team)
    {
            $team->members()->attach(auth()->id());
    }

    public function updated(Team $team)
    {
        //
    }


    public function deleted(Team $team)
    {
        $team->members()->sync([]);
    }


    public function restored(Team $team)
    {
        //
    }


    public function forceDeleted(Team $team)
    {
        //
    }
}

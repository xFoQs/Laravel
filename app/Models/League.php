<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;

    public function teams()
    {
        return $this->hasMany(Team::class, 'league_id');
    }

    public function games()
    {
        return $this->hasManyThrough(Game::class, Team::class, 'league_id', 'team1_id', 'id', 'id');
    }
}

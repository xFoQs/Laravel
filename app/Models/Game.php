<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

    public function league()
    {
        return $this->belongsTo(League::class, 'league_id');
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'game_player', 'game_id', 'player_id');
    }


    public function goals()
    {
        return $this->hasMany(Goals::class, 'game_id');
    }



}

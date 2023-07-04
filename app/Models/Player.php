<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function seasons()
    {
        return $this->belongsToMany(Season::class, 'player_seasons')->withPivot('team_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'player_seasons', 'player_id', 'team_id')
            ->withPivot('season_id');
    }

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_player', 'player_id', 'game_id');
    }

    public function goals()
    {
        return $this->hasMany(Goals::class);
    }

    public function playerSeasons()
    {
        return $this->hasMany(PlayerSeason::class);
    }
}

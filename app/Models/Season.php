<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = [
        'name',
    ];

    public function playerSeasons()
    {
        return $this->hasMany(PlayerSeason::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'player_seasons', 'season_id', 'player_id')
            ->withPivot('team_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'player_seasons', 'season_id', 'team_id')
            ->withPivot('player_id');
    }

    public function games()
    {
        return $this->hasManyThrough(Game::class, PlayerSeason::class, 'season_id', 'team_id', 'id', 'team_id');
    }

    public function goals()
    {
        return $this->hasManyThrough(Goals::class, Game::class, 'season_id', 'game_id');
    }
}

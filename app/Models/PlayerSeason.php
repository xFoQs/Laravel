<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerSeason extends Model
{
    protected $table = 'player_seasons'; // Name of the intermediate table

    protected $fillable = ['player_id', 'season_id', 'team_id']; // Fillable attributes

    // Relationship with Player model
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    // Relationship with Season model
    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    // Relationship with Team model
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goals extends Model
{
    protected $table = 'goals';

    protected $fillable = [
        'game_id',
        'player_id',
        'minute',
        'team_id',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }


    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}

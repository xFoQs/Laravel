<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuicideGoal extends Model
{
    protected $table = 'suicidegoals';

    protected $fillable = [
        'game_id',
        'player_id',
        'minute',
        'team_id',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}

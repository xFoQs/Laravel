<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Team extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function players(){
        return $this->hasMany(Player::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class, 'team1_id')->orWhere('team2_id', $this->id);
    }


    public function league()
    {
        return $this->belongsTo(League::class, 'league_id');
    }

    public function homeGames()
    {
        return $this->hasMany(Game::class, 'team1_id');
    }

    public function awayGames()
    {
        return $this->hasMany(Game::class, 'team2_id');
    }


    public function getGamesAttribute($selectedSeasonId)
    {
        return Game::where(function($query) {
            $query->where('team1_id', $this->attributes['id'])->orWhere('team2_id', $this->attributes['id']);
        })
            ->whereNotNull('result1')
            ->where('season_id', $selectedSeasonId)
            ->count();
    }

    public function getWonAttribute($selectedSeasonId)
    {
        return Game::whereNotNull('result1')
            ->where(function($query) {
                $query->where(function($query2) {
                    $query2->where('team1_id', $this->attributes['id'])->whereRaw('result1 > result2');
                })->orWhere(function($query2) {
                    $query2->where('team2_id', $this->attributes['id'])->whereRaw('result1 < result2');
                });
            })
            ->where('season_id', $selectedSeasonId)
            ->count();
    }

    public function getTiedAttribute($selectedSeasonId)
    {
        return Game::whereNotNull('result1')
            ->whereRaw('result1 = result2')
            ->where(function($query) {
                $query->where('team1_id', $this->attributes['id'])
                    ->orWhere('team2_id', $this->attributes['id']);
            })
            ->where('season_id', $selectedSeasonId)
            ->count();
    }

    public function getLostAttribute($selectedSeasonId)
    {
        return Game::whereNotNull('result1')
            ->where(function($query) {
                $query->where(function($query2) {
                    $query2->where('team1_id', $this->attributes['id'])->whereRaw('result1 < result2');
                })->orWhere(function($query2) {
                    $query2->where('team2_id', $this->attributes['id'])->whereRaw('result1 > result2');
                });
            })
            ->where('season_id', $selectedSeasonId)
            ->count();
    }

    public function getPointsAttribute($selectedSeasonId)
    {
        return $this->getWonAttribute($selectedSeasonId) * 3 + $this->getTiedAttribute($selectedSeasonId) * 1;
    }

    public function getGoalStats($selectedSeasonId)
    {
        $teamId = $this->attributes['id'];

        $scoredGoals = Game::where('team1_id', $teamId)
            ->whereNotNull('result1')
            ->where('season_id', $selectedSeasonId)
            ->sum('result1');

        $concededGoals = Game::where('team1_id', $teamId)
            ->whereNotNull('result2')
            ->where('season_id', $selectedSeasonId)
            ->sum('result2');

        $scoredGoals += Game::where('team2_id', $teamId)
            ->whereNotNull('result2')
            ->where('season_id', $selectedSeasonId)
            ->sum('result2');

        $concededGoals += Game::where('team2_id', $teamId)
            ->whereNotNull('result1')
            ->where('season_id', $selectedSeasonId)
            ->sum('result1');

        return [
            'scored' => $scoredGoals,
            'conceded' => $concededGoals,
        ];
    }
}

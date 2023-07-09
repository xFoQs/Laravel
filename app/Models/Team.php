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

    public function getHomeGamesAttribute($selectedSeasonId)
    {
        return $this->homeGames()
            ->whereNotNull('result1')
            ->where('season_id', $selectedSeasonId)
            ->count();
    }

    public function getHomeWonAttribute($selectedSeasonId)
    {
        return $this->homeGames()
            ->whereNotNull('result1')
            ->whereRaw('result1 > result2')
            ->where('season_id', $selectedSeasonId)
            ->count();
    }

    public function getHomeTiedAttribute($selectedSeasonId)
    {
        return $this->homeGames()
            ->whereNotNull('result1')
            ->whereRaw('result1 = result2')
            ->where('season_id', $selectedSeasonId)
            ->count();
    }

    public function getHomeLostAttribute($selectedSeasonId)
    {
        return $this->homeGames()
            ->whereNotNull('result1')
            ->whereRaw('result1 < result2')
            ->where('season_id', $selectedSeasonId)
            ->count();
    }

    public function getHomePointsAttribute($selectedSeasonId)
    {
        return $this->getHomeWonAttribute($selectedSeasonId) * 3 + $this->getHomeTiedAttribute($selectedSeasonId);
    }

    public function getTotalGamesPlayedAttribute($selectedSeasonId)
    {
        return $this->games()
            ->whereNotNull('result1')
            ->where('season_id', $selectedSeasonId)
            ->count();
    }

    public function getHomeGamesPlayedAttribute($selectedSeasonId)
    {
        return $this->homeGames()
            ->whereNotNull('result1')
            ->where('season_id', $selectedSeasonId)
            ->count();
    }



    public function getHomeGoalStats($selectedSeasonId)
    {
        $teamId = $this->attributes['id'];

        $scoredGoals = $this->homeGames()
            ->whereNotNull('result1')
            ->where('season_id', $selectedSeasonId)
            ->sum('result1');

        $concededGoals = $this->homeGames()
            ->whereNotNull('result2')
            ->where('season_id', $selectedSeasonId)
            ->sum('result2');

        return [
            'scored' => $scoredGoals,
            'conceded' => $concededGoals,
        ];
    }




    public function getAwayGamesAttribute($selectedSeasonId)
    {
        return $this->awayGames()
            ->whereNotNull('result1')
            ->where('season_id', $selectedSeasonId)
            ->count();
    }

    public function getAwayWonAttribute($selectedSeasonId)
    {
        return $this->awayGames()
            ->whereNotNull('result1')
            ->whereRaw('result1 > result2')
            ->where('season_id', $selectedSeasonId)
            ->count();
    }

    public function getAwayTiedAttribute($selectedSeasonId)
    {
        return $this->awayGames()
            ->whereNotNull('result1')
            ->whereRaw('result1 = result2')
            ->where('season_id', $selectedSeasonId)
            ->count();
    }

    public function getAwayLostAttribute($selectedSeasonId)
    {
        return $this->awayGames()
            ->whereNotNull('result1')
            ->whereRaw('result1 < result2')
            ->where('season_id', $selectedSeasonId)
            ->count();
    }

    public function getAwayPointsAttribute($selectedSeasonId)
    {
        return $this->getAwayWonAttribute($selectedSeasonId) * 3 + $this->getAwayTiedAttribute($selectedSeasonId);
    }


    public function getAwayGamesPlayedAttribute($selectedSeasonId)
    {
        return $this->awayGames()
            ->whereNotNull('result1')
            ->where('season_id', $selectedSeasonId)
            ->count();
    }


    public function getAwayGoalStats($selectedSeasonId)
    {
        $teamId = $this->attributes['id'];

        $scoredGoals = $this->awayGames()
            ->whereNotNull('result1')
            ->where('season_id', $selectedSeasonId)
            ->sum('result1');

        $concededGoals = $this->awayGames()
            ->whereNotNull('result2')
            ->where('season_id', $selectedSeasonId)
            ->sum('result2');

        return [
            'scored' => $scoredGoals,
            'conceded' => $concededGoals,
        ];
    }

}

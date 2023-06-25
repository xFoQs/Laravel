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

    public function games(){
        return $this->hasMany(Game::class, 'team_id');
    }


    public function league()
    {
        return $this->belongsTo(League::class, 'league_id');
    }


    public function getGamesAttribute()
    {
        return Game::where(function($query) {
            $query->where('team1_id', $this->attributes['id'])->orWhere('team2_id', $this->attributes['id']);
        })
        ->whereNotNull('result1')
        ->count();
    }

    public function getWonAttribute()
    {
        return Game::whereNotNull('result1')
            ->where(function($query) {
                $query->where(function($query2) {
                    $query2->where('team1_id', $this->attributes['id'])->whereRaw('result1 > result2');
                })->orWhere(function($query2) {
                    $query2->where('team2_id', $this->attributes['id'])->whereRaw('result1 < result2');
                });
            })
            ->count();
    }

    public function getTiedAttribute()
    {
        return Game::whereNotNull('result1')
            ->whereRaw('result1 = result2')
            ->where(function($query) {
                $query->where('team1_id', $this->attributes['id'])
                    ->orWhere('team2_id', $this->attributes['id']);
            })
            ->count();
    }

    public function getLostAttribute()
    {
        return Game::whereNotNull('result1')
            ->where(function($query) {
                $query->where(function($query2) {
                    $query2->where('team1_id', $this->attributes['id'])->whereRaw('result1 < result2');
                })->orWhere(function($query2) {
                    $query2->where('team2_id', $this->attributes['id'])->whereRaw('result1 > result2');
                });
            })
            ->count();
    }

    public function getPointsAttribute(){
        return $this->getWonAttribute() * 3 + $this->getTiedAttribute() * 1;
    }

    public function getGoalStats()
    {
        $teamId = $this->attributes['id'];

        $scoredGoals = Game::where('team1_id', $teamId)
            ->whereNotNull('result1')
            ->sum('result1');

        $concededGoals = Game::where('team1_id', $teamId)
            ->whereNotNull('result2')
            ->sum('result2');

        $scoredGoals += Game::where('team2_id', $teamId)
            ->whereNotNull('result2')
            ->sum('result2');

        $concededGoals += Game::where('team2_id', $teamId)
            ->whereNotNull('result1')
            ->sum('result1');

        return [
            'scored' => $scoredGoals,
            'conceded' => $concededGoals,
        ];
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\League;
use App\Models\Season;
use App\Models\Goals;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $leagues = League::all();
        $seasons = Season::orderBy('id', 'desc')->get();

        $selectedLeagueId = $request->input('league', $leagues->first()->id);
        $selectedSeasonId = $request->input('season', $seasons->max('id'));

        $selectedLeague = League::find($selectedLeagueId);
        $selectedSeason = Season::find($selectedSeasonId);

        $teams = Team::where(function ($query) use ($selectedLeagueId, $selectedSeasonId) {
            $query->whereExists(function ($subQuery) use ($selectedLeagueId, $selectedSeasonId) {
                $subQuery->select(DB::raw(1))
                    ->from('games')
                    ->whereRaw('(teams.id = games.team1_id OR teams.id = games.team2_id)')
                    ->where('games.league_id', $selectedLeagueId)
                    ->where('games.season_id', $selectedSeasonId);
            });
        })
            ->get();

        $goalsCount = [];

        foreach ($teams as $team) {
            $goalsCount[$team->id] = [
                '1-15' => [
                    'scored' => 0,
                    'conceded' => 0,
                ],
                '16-30' => [
                    'scored' => 0,
                    'conceded' => 0,
                ],
                '31-45' => [
                    'scored' => 0,
                    'conceded' => 0,
                ],
                '46-60' => [
                    'scored' => 0,
                    'conceded' => 0,
                ],
                '61-75' => [
                    'scored' => 0,
                    'conceded' => 0,
                ],
                '76-90' => [
                    'scored' => 0,
                    'conceded' => 0,
                ],
            ];
        }

        $games = Game::where('league_id', $selectedLeagueId)
            ->where('season_id', $selectedSeasonId)
            ->get();

        foreach ($games as $game) {
            $team1 = Team::find($game->team1_id);
            $team2 = Team::find($game->team2_id);

            $goals = Goals::where('game_id', $game->id)->get();

            foreach ($goals as $goal) {
                $minute = $goal->minute;
                $scoringTeam = $goal->team_id;
                $opposingTeam = $team1->id === $scoringTeam ? $team2->id : $team1->id;

                if ($minute >= 1 && $minute <= 15) {
                    $goalsCount[$scoringTeam]['1-15']['scored']++;
                    $goalsCount[$opposingTeam]['1-15']['conceded']++;
                } elseif ($minute >= 16 && $minute <= 30) {
                    $goalsCount[$scoringTeam]['16-30']['scored']++;
                    $goalsCount[$opposingTeam]['16-30']['conceded']++;
                } elseif ($minute >= 31 && $minute <= 45) {
                    $goalsCount[$scoringTeam]['31-45']['scored']++;
                    $goalsCount[$opposingTeam]['31-45']['conceded']++;
                } elseif ($minute >= 46 && $minute <= 60) {
                    $goalsCount[$scoringTeam]['46-60']['scored']++;
                    $goalsCount[$opposingTeam]['46-60']['conceded']++;
                } elseif ($minute >= 61 && $minute <= 75) {
                    $goalsCount[$scoringTeam]['61-75']['scored']++;
                    $goalsCount[$opposingTeam]['61-75']['conceded']++;
                } elseif ($minute >= 76 && $minute <= 130) {
                    $goalsCount[$scoringTeam]['76-90']['scored']++;
                    $goalsCount[$opposingTeam]['76-90']['conceded']++;
                }
            }
        }

        $statistics = [];
        foreach ($teams as $team) {
            $matches = $games->filter(function ($game) use ($team) {
                return $game->team1_id == $team->id || $game->team2_id == $team->id;
            });
            $matchesCount = $matches->filter(function ($game) {
                return $game->result1 !== null && $game->result2 !== null;
            })->count();

            $totalGoalsScored = 0;
            $totalGoalsConceded = 0;
            $matchesAbove25Goals = 0;
            $matchesBelow25Goals = 0;

            foreach ($matches as $match) {
                if ($match->result1 !== null && $match->result2 !== null) {
                    if ($match->team1_id == $team->id) {
                        $totalGoalsScored += $match->result1;
                        $totalGoalsConceded += $match->result2;
                    } else {
                        $totalGoalsScored += $match->result2;
                        $totalGoalsConceded += $match->result1;
                    }

                    $totalGoals = $match->result1 + $match->result2;
                    if ($totalGoals > 2.5) {
                        $matchesAbove25Goals++;
                    } elseif ($totalGoals < 2.5) {
                        $matchesBelow25Goals++;
                    }
                }
            }

            $statistics[$team->id] = [
                'M' => $matchesCount,
                'Śr. Br' => $matchesCount > 0 ? number_format(($totalGoalsScored + $totalGoalsConceded) / $matchesCount, 2) : 0,
                'Śr. Bz' => $matchesCount > 0 ? number_format($totalGoalsScored / $matchesCount, 2) : 0,
                'Śr. Bs' => $matchesCount > 0 ? number_format($totalGoalsConceded / $matchesCount, 2) : 0,
                '>2.5' => $matchesCount > 0 ? number_format(($matchesAbove25Goals / $matchesCount) * 100, 2) : 0,
                '<2.5' => $matchesCount > 0 ? number_format(($matchesBelow25Goals / $matchesCount) * 100, 2) : 0,
            ];
        }

        $results = Game::select('team1_id', 'team2_id', 'result1', 'result2')
            ->where('league_id', $selectedLeagueId)
            ->where('season_id', $selectedSeasonId)
            ->get();

        $homeWins = $results->filter(function ($game) {
            return $game->result1 > $game->result2;
        })->count();

        $awayWins = $results->filter(function ($game) {
            return $game->result2 > $game->result1;
        })->count();

        $draws = $results->filter(function ($game) {
            return $game->result1 !== null && $game->result2 !== null && $game->result1 === $game->result2;
        })->count();

        $totalGames = $homeWins + $awayWins + $draws;

        $homeWinsPercentage = $totalGames > 0 ? ($homeWins / $totalGames) * 100 : 0;
        $drawsPercentage = $totalGames > 0 ? ($draws / $totalGames) * 100 : 0;
        $awayWinsPercentage = $totalGames > 0 ? ($awayWins / $totalGames) * 100 : 0;


// Przygotowanie danych dla wykresu kołowego
        $chartData = new Collection([
            ['label' => 'Zwycięstwa gospodarzy', 'value' => $homeWins, 'percentage' => $homeWinsPercentage],
            ['label' => 'Remisy', 'value' => $draws, 'percentage' => $drawsPercentage],
            ['label' => 'Zwycięstwa gości', 'value' => $awayWins, 'percentage' => $awayWinsPercentage],
        ]);

        $commonResults = $results->filter(function ($game) {
            return $game->result1 !== null && $game->result2 !== null;
        })
            ->groupBy(function ($game) {
                return $game->result1 . ':' . $game->result2;
            })
            ->map(function ($groupedGames) use ($totalGames) {
                $count = $groupedGames->count();
                $percentage = ($count / $totalGames) * 100;
                return [
                    'result' => $groupedGames->first()->result1 . ':' . $groupedGames->first()->result2,
                    'count' => $count,
                    'percentage' => $percentage,
                ];
            })
            ->sortByDesc('count')
            ->values();

// Konwersja wyników do formatu wymaganego przez widok
        $commonResultsLabels = $commonResults->pluck('result')->toJson();
        $commonResultsCounts = $commonResults->pluck('count')->toJson();
        $commonResultsPercentages = $commonResults->pluck('percentage')->toJson();

// Konwersja kolekcji do formatu wymaganego przez Chart.js
        $chartLabels = $chartData->pluck('label')->toJson();
        $chartValues = $chartData->pluck('value')->toJson();
        $chartPercentages = $chartData->pluck('percentage')->toJson();

        // Obliczenia dodatkowe
        $goallessMatches = $this->calculateGoallessMatches($results);
        $winByAtLeastOneGoal = $this->calculateWinByAtLeastOneGoal($results);
        $winByAtLeastTwoGoals = $this->calculateWinByAtLeastTwoGoals($results);
        $winByThreeOrMoreGoals = $this->calculateWinByThreeOrMoreGoals($results);


        return view('test4', [
            'teams' => $teams,
            'goalsCount' => $goalsCount,
            'seasons' => $seasons,
            'selectedSeasonId' => $selectedSeasonId,
            'leagues' => $leagues,
            'selectedLeagueId' => $selectedLeagueId,
            'selectedLeague' => $selectedLeague,
            'selectedSeason' => $selectedSeason,
            'statistics' => $statistics,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'chartValues' => $chartValues,
            'chartPercentages' => $chartPercentages,
            'commonResults' => $commonResults,
            'commonResultsLabels' => $commonResultsLabels,
            'commonResultsCounts' => $commonResultsCounts,
            'commonResultsPercentages' => $commonResultsPercentages,
            'goallessMatches' => $goallessMatches,
            'winByAtLeastOneGoal' => $winByAtLeastOneGoal,
            'winByAtLeastTwoGoals' => $winByAtLeastTwoGoals,
            'winByThreeOrMoreGoals' => $winByThreeOrMoreGoals,
        ]);
    }

    public function calculateGoallessMatches($results)
    {
        $goallessMatches = $results->filter(function ($game) {
            return $game->result1 !== null && $game->result2 !== null && $game->result1 === 0 && $game->result2 === 0;
        });

        $goallessMatchesCount = $goallessMatches->count();

        $filteredResults = $results->filter(function ($game) {
            return $game->result1 !== null && $game->result2 !== null;
        });

        $goallessMatchesPercentage = $filteredResults->count() > 0 ? ($goallessMatchesCount / $filteredResults->count()) * 100 : 0;

        return [
            'count' => $goallessMatchesCount,
            'percentage' => $goallessMatchesPercentage,
        ];
    }

    public function calculateWinByAtLeastOneGoal($results)
    {
        $winByAtLeastOneGoal = $results->filter(function ($game) {
            return $game->result1 !== null && $game->result2 !== null &&
                (($game->result1 > $game->result2 && $game->result1 - $game->result2 >= 1) ||
                    ($game->result2 > $game->result1 && $game->result2 - $game->result1 >= 1));
        });

        $winByAtLeastOneGoalCount = $winByAtLeastOneGoal->count();

        $filteredResults = $results->filter(function ($game) {
            return $game->result1 !== null && $game->result2 !== null;
        });

        $winByAtLeastOneGoalPercentage = $filteredResults->count() > 0 ?
            ($winByAtLeastOneGoalCount / $filteredResults->count()) * 100 : 0;

        return [
            'count' => $winByAtLeastOneGoalCount,
            'percentage' => $winByAtLeastOneGoalPercentage,
        ];
    }

    public function calculateWinByAtLeastTwoGoals($results)
    {
        $winByAtLeastTwoGoals = $results->filter(function ($game) {
            return $game->result1 !== null && $game->result2 !== null &&
                (($game->result1 > $game->result2 && $game->result1 - $game->result2 >= 2) ||
                    ($game->result2 > $game->result1 && $game->result2 - $game->result1 >= 2));
        });

        $winByAtLeastTwoGoalsCount = $winByAtLeastTwoGoals->count();

        $filteredResults = $results->filter(function ($game) {
            return $game->result1 !== null && $game->result2 !== null;
        });

        $winByAtLeastTwoGoalsPercentage = $filteredResults->count() > 0 ?
            ($winByAtLeastTwoGoalsCount / $filteredResults->count()) * 100 : 0;

        return [
            'count' => $winByAtLeastTwoGoalsCount,
            'percentage' => $winByAtLeastTwoGoalsPercentage,
        ];
    }

    public function calculateWinByThreeOrMoreGoals($results)
    {
        $winByThreeOrMoreGoals = $results->filter(function ($game) {
            return $game->result1 !== null && $game->result2 !== null &&
                (($game->result1 > $game->result2 && $game->result1 - $game->result2 >= 3) ||
                    ($game->result2 > $game->result1 && $game->result2 - $game->result1 >= 3));
        });

        $winByThreeOrMoreGoalsCount = $winByThreeOrMoreGoals->count();

        $filteredResults = $results->filter(function ($game) {
            return $game->result1 !== null && $game->result2 !== null;
        });

        $winByThreeOrMoreGoalsPercentage = $filteredResults->count() > 0 ?
            ($winByThreeOrMoreGoalsCount / $filteredResults->count()) * 100 : 0;

        return [
            'count' => $winByThreeOrMoreGoalsCount,
            'percentage' => $winByThreeOrMoreGoalsPercentage,
        ];
    }


    public function chart(Request $request)
    {

    }
}



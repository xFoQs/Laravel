@extends('layouts.client')

@section('content')
    <div class="row mt-5 justify-content-center">
        <div class="col-8 mb-4">
            <div class="card">
                <h3 class="card-header">
                    Zawodnicy
                </h3>
                <div class="card-body">
                    <form id="filter-form" action="{{ route('player.filter') }}" method="GET">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="league">Liga:</label>
                                <select name="league" id="league" class="form-control">
                                    @foreach($leagues as $league)
                                        <option value="{{ $league->id }}" {{ $selectedLeagueId == $league->id ? 'selected' : '' }}>{{ $league->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="padding-bottom: 10px;">
                                <label for="season">Sezon:</label>
                                <select name="season" id="season" class="form-control">
                                    @foreach($seasons as $season)
                                        <option value="{{ $season->id }}" {{ $selectedSeasonId == $season->id ? 'selected' : '' }}>{{ $season->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Filtruj</button>
                        </div>
                    </form>
                    <div class="table-responsive" id="standings-table">

                        <div style="display: inline-flex"><span style="font-weight: bold">{{ $selectedLeague->name }}</span> <span style="padding-left: 5px;">sezon: {{$selectedSeason->name}}</span></div>

                        <h1>Strzelcy bramek</h1>

                <table class="table table-borderless">
                    <thead class="table bg-primary" style="color: white;">
                    <tr>
                        <th scope="col">Lp.</th>
                        <th scope="col">Piłkarz</th>
                        <th scope="col">Klub</th>
                        <th scope="col">Bramki</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $index = 1;
                    @endphp
                    @foreach($players as $player)
                        <tr>
                            <th scope="row">{{ $index++ }}</th>
                            <td>{{ $player->name }} {{ $player->surname }}</td>
                            <td>{{ $player->team ? $player->team->name : '' }}</td>
                            <td  class="text-center">
    <span class="goal-count{{ $player->id }}{{ $player->goals_count > 0 ? ' text-primary' : '' }}" data-bs-toggle="modal" data-bs-target="#goalsModal{{ $player->id }}">
        @if ($player->goals_count > 0)
            <span class="badge bg-primary">{{ $player->goals_count }}</span>
        @else
            <span class="badge bg-primary">{{ $player->goals_count }}</span>
        @endif
    </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    @foreach($players as $player)
        <div class="modal fade" id="goalsModal{{ $player->id }}" tabindex="-1" aria-labelledby="goalsModalLabel{{ $player->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="goalsModalLabel{{ $player->id }}">Informacje o zawodniku</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="padding-bottom: 1rem;">
                            <div class="col-md-4">
                                <div class="form">
                                    <div>
                                        <div class="mb-4 d-flex justify-content-center">
                                            <img id="preview{{ $player->id }}" src="{{ $player->photo ? asset('img/' . $player->photo) : asset('img/man.png') }}"
                                                 alt="example placeholder" style="width: 190px; height: 200px;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Imię i Nazwisko:</strong> {{ $player->name }} {{ $player->surname }}</li>
                                    <li class="list-group-item"><strong>Data urodzenia:</strong> {{ $player->birth_date }}</li>
                                    <li class="list-group-item"><strong>Pozycja:</strong> {{ $player->position }}</li>
                                    <li class="list-group-item"><strong>Narodowość:</strong> {{ $player->country }}</li>
                                    <li class="list-group-item"><strong>Drużyna:</strong> {{ $player->team->name }}</li>
                                </ul>
                            </div>
                        </div>
                        <table class="table table-borderless">
                            <thead class="table bg-primary" style="color: white;">
                            <tr>
                                <th scope="col">Gospodarze</th>
                                <th scope="col">Wynik</th>
                                <th scope="col">Goście</th>
                                <th scope="col">Data</th>
                                <th scope="col">Liga</th>
                                <th scope="col">Bramki</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $processedGames = [];
                                $totalGoals = 0;
                            @endphp
                            @foreach($player->goals as $goal)
                                @php
                                    $game = $goal->game;
                                    $homeTeam = $game->team1;
                                    $awayTeam = $game->team2;
                                    $result1 = $game->result1;
                                    $result2 = $game->result2;
                                    $league = $game->league;
                                    $gameId = $game->id;
                                    $goalsInGame = $player->goals->where('game_id', $gameId)->where('player_id', $player->id)->count();
                                    $totalGoals =  $player->goals_count
                                @endphp
                                @if(!in_array($gameId, $processedGames))
                                    <tr>
                                        <td>{{ $homeTeam->name }}</td>
                                        <td><span class="badge bg-primary">{{ $result1 }} : {{ $result2 }}</span></td>
                                        <td>{{ $awayTeam->name }}</td>
                                        <td><span class="badge bg-success">{{ $game->start_time }}</span></td>
                                        <td>{{ $league->name }}</td>
                                        <td><span class="badge bg-info">{{ $goalsInGame }}</span></td>
                                    </tr>
                                    @php
                                        $processedGames[] = $gameId;
                                    @endphp
                                @endif
                            @endforeach
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Suma bramek:</td>
                                <td><span class="badge bg-info">{{ $totalGoals }}</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection


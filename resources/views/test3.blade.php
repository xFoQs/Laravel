@extends('layouts.client')

@section('content')
    <div class="row mt-5 justify-content-center">
        <div class="col-8 mb-4">
            <div class="card">
                <h5 class="card-header">
                    List of players
                </h5>
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
                        <div class="form-group">
                            <label for="season">Sezon:</label>
                            <select name="season" id="season" class="form-control">
                                @foreach($seasons as $season)
                                    <option value="{{ $season->id }}" {{ $selectedSeasonId == $season->id ? 'selected' : '' }}>{{ $season->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filtruj</button>
                    </div>
                </form>
                <ul class="list-group list-group-flush">
                    @foreach($players as $player)
                        <li class="list-group-item">
                            {{ $player->name }} {{ $player->surname }}
                            <span class="float-end badge bg-info">{{ $player->goals_count }}</span>
                            @if($player->team)
                                <span class="float-end me-3">({{ $player->team->name }})</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

@endsection


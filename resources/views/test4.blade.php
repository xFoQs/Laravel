@extends('layouts.client')

@section('content')
    <div class="row mt-5 justify-content-center">
        <div class="col-8 mb-4">
            <div class="card">
                <h3 class="card-header">
                    Statystyki
                </h3>
                <div class="card-body">
                    <form id="standings-form" action="{{ route('statistics') }}" method="GET">
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
                    </form>
                    <hr>
                    <div class="table-responsive" id="standings-table">
                        <table class="table table-borderless">
                            <thead class="table-primary">
                            <tr>
                                <th scope="col">Drużyna</th>
                                <th scope="col">1-15</th>
                                <th scope="col">16-30</th>
                                <th scope="col">31-45</th>
                                <th scope="col">46-60</th>
                                <th scope="col">61-75</th>
                                <th scope="col">76-90</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($teams as $team)
                                <tr>
                                    <td>{{ $team->name }}</td>
                                    <td>
                                        <span class="badge badge-success">{{ $goalsCount[$team->id]['1-15']['scored'] }}</span>
                                        <span class="badge rounded-pill badge-danger me-1">{{ $goalsCount[$team->id]['1-15']['conceded'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $goalsCount[$team->id]['16-30']['scored'] }}</span>
                                        <span class="badge rounded-pill badge-danger me-1">{{ $goalsCount[$team->id]['16-30']['conceded'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $goalsCount[$team->id]['31-45']['scored'] }}</span>
                                        <span class="badge rounded-pill badge-danger me-1">{{ $goalsCount[$team->id]['31-45']['conceded'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $goalsCount[$team->id]['46-60']['scored'] }}</span>
                                        <span class="badge rounded-pill badge-danger me-1">{{ $goalsCount[$team->id]['46-60']['conceded'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $goalsCount[$team->id]['61-75']['scored'] }}</span>
                                        <span class="badge rounded-pill badge-danger me-1">{{ $goalsCount[$team->id]['61-75']['conceded'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $goalsCount[$team->id]['76-90']['scored'] }}</span>
                                        <span class="badge rounded-pill badge-danger me-1">{{ $goalsCount[$team->id]['76-90']['conceded'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

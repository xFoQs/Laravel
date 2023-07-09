@extends('layouts.client')

@section('content')
    <div class="row mt-5 justify-content-center">
        <div class="col-8 mb-4">
            <div class="card">
                <h3 class="card-header">
                    Tabela punktowa
                </h3>
                <div class="card-body">
                    <form id="standings-form" action="{{ route('standings') }}" method="GET">
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
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Filtruj</button> <!-- Dodaj przycisk Submit -->
                    </form>
                    <hr>
                    <div class="table-responsive" id="standings-table">
                        <div style="display: inline-flex"><span style="font-weight: bold">{{ $selectedLeague->name }}</span> <span style="padding-left: 5px;">sezon: {{$selectedSeason->name}}</span></div>

                        <h3>Statystyki</h3>
                        <table class="table table-borderless">
                            <thead class="table-primary">
                            <tr>
                                <th scope="col">Lp.</th>
                                <th scope="col">Drużyna</th>
                                <th scope="col">M</th>
                                <th scope="col">Pkt</th>
                                <th scope="col">Z</th>
                                <th scope="col">R</th>
                                <th scope="col">P</th>
                                <th scope="col">Bz</th>
                                <th scope="col">Bs</th>
                                <th scope="col">RB</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($teams as $team)
                                @php
                                    $goalStats = $team->getGoalStats($selectedSeasonId);
                                    $goalDifference = $goalStats['scored'] - $goalStats['conceded'];
                                @endphp
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ $team->getTotalGamesPlayedAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getPointsAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getWonAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getTiedAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getLostAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $goalStats['scored'] }}</td>
                                    <td>{{ $goalStats['conceded'] }}</td>
                                    <td>{{ $goalDifference }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="8">
                                    <span style="font-weight: bold">M</span> - mecze, <span style="font-weight: bold">Pkt</span> - zdobyte punkty, <span style="font-weight: bold">Z</span> - zwycięstwa, <span style="font-weight: bold">R</span> - remisy, <span style="font-weight: bold">P</span> - porażki, <span style="font-weight: bold">Bz</span> - bramki zdobyte, <span style="font-weight: bold">Bs</span> - bramki stracone, <span style="font-weight: bold">RB</span> - bilans bramek (Bz - Bs)
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div style="display: inline-flex"><span style="font-weight: bold">{{ $selectedLeague->name }}</span> <span style="padding-left: 5px;">sezon: {{$selectedSeason->name}}</span></div>
                        <h3>Mecze rozegrane u siebie</h3>
                        <table class="table table-borderless">
                            <thead class="table-primary">
                            <tr>
                                <th scope="col">Lp.</th>
                                <th scope="col">Drużyna</th>
                                <th scope="col">M</th>
                                <th scope="col">Pkt</th>
                                <th scope="col">Z</th>
                                <th scope="col">R</th>
                                <th scope="col">P</th>
                                <th scope="col">Bz</th>
                                <th scope="col">Bs</th>
                                <th scope="col">RB</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($homeTeams as $team)
                                @php
                                    $goalStats = $team->getHomeGoalStats($selectedSeasonId);
                                    $goalDifference = $goalStats['scored'] - $goalStats['conceded'];
                                @endphp
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ $team->getHomeGamesPlayedAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getHomePointsAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getHomeWonAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getHomeTiedAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getHomeLostAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $goalStats['scored'] }}</td>
                                    <td>{{ $goalStats['conceded'] }}</td>
                                    <td>{{ $goalDifference }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="8">
                                    <span style="font-weight: bold">M</span> - mecze, <span style="font-weight: bold">Pkt</span> - zdobyte punkty, <span style="font-weight: bold">Z</span> - zwycięstwa, <span style="font-weight: bold">R</span> - remisy, <span style="font-weight: bold">P</span> - porażki, <span style="font-weight: bold">Bz</span> - bramki zdobyte, <span style="font-weight: bold">Bs</span> - bramki stracone, <span style="font-weight: bold">RB</span> - bilans bramek (Bz - Bs)
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div style="display: inline-flex"><span style="font-weight: bold">{{ $selectedLeague->name }}</span> <span style="padding-left: 5px;">sezon: {{$selectedSeason->name}}</span></div>
                        <h3>Mecze rozegrane na wyjeździe</h3>
                        <table class="table table-borderless">
                            <thead class="table-primary">
                            <tr>
                                <th scope="col">Lp.</th>
                                <th scope="col">Drużyna</th>
                                <th scope="col">M</th>
                                <th scope="col">Pkt</th>
                                <th scope="col">Z</th>
                                <th scope="col">R</th>
                                <th scope="col">P</th>
                                <th scope="col">Bz</th>
                                <th scope="col">Bs</th>
                                <th scope="col">RB</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($awayTeams as $team)
                                @php
                                    $goalStats = $team->getAwayGoalStats($selectedSeasonId);
                                    $goalDifference = $goalStats['scored'] - $goalStats['conceded'];
                                @endphp
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ $team->getAwayGamesPlayedAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getAwayPointsAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getAwayWonAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getAwayTiedAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getAwayLostAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $goalStats['scored'] }}</td>
                                    <td>{{ $goalStats['conceded'] }}</td>
                                    <td>{{ $goalDifference }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="8">
                                    <span style="font-weight: bold">M</span> - mecze, <span style="font-weight: bold">Pkt</span> - zdobyte punkty, <span style="font-weight: bold">Z</span> - zwycięstwa, <span style="font-weight: bold">R</span> - remisy, <span style="font-weight: bold">P</span> - porażki, <span style="font-weight: bold">Bz</span> - bramki zdobyte, <span style="font-weight: bold">Bs</span> - bramki stracone, <span style="font-weight: bold">RB</span> - bilans bramek (Bz - Bs)
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Nasłuchuj zdarzeń zmiany wartości w selectach
            document.getElementById('league').addEventListener('change', function() {
                document.getElementById('standings-form').submit(); // Wyślij formularz po zmianie wartości w select
            });

            document.getElementById('season').addEventListener('change', function() {
                document.getElementById('standings-form').submit(); // Wyślij formularz po zmianie wartości w select
            });
        </script>
    @endpush

@endsection

@extends('layouts.client')

@section('content')
    <div class="row mt-5 justify-content-center">
        <div class="col-8 mb-4">
            <div class="card">
                <h3 class="card-header">
                    Standings Table
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
                        <table class="table table-borderless">
                            <thead class="table-primary">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Drużyna</th>
                                <th scope="col">Wygrane</th>
                                <th scope="col">Remisy</th>
                                <th scope="col">Przegrane</th>
                                <th scope="col">Punkty</th>
                                <th scope="col">Bilans</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($teams as $team)
                                @php
                                    $goalStats = $team->getGoalStats($selectedSeasonId);
                                @endphp
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ $team->getWonAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getTiedAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getLostAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $team->getPointsAttribute($selectedSeasonId) }}</td>
                                    <td>{{ $goalStats['scored'] }}:{{ $goalStats['conceded'] }}</td>
                                </tr>
                            @endforeach
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

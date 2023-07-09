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
                        <div style="display: inline-flex"><span style="font-weight: bold">{{ $selectedLeague->name }}</span> <span style="padding-left: 5px;">sezon: {{$selectedSeason->name}}</span></div>

                            <h1>Czas bramek</h1>

                        <table class="table table-borderless">
                            <thead class="table bg-primary" style="color: white;">
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
                                        <span class="badge badge-danger me-1">{{ $goalsCount[$team->id]['1-15']['conceded'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $goalsCount[$team->id]['16-30']['scored'] }}</span>
                                        <span class="badge  badge-danger me-1">{{ $goalsCount[$team->id]['16-30']['conceded'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $goalsCount[$team->id]['31-45']['scored'] }}</span>
                                        <span class="badge badge-danger me-1">{{ $goalsCount[$team->id]['31-45']['conceded'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $goalsCount[$team->id]['46-60']['scored'] }}</span>
                                        <span class="badge rounded-pill badge-danger me-1">{{ $goalsCount[$team->id]['46-60']['conceded'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $goalsCount[$team->id]['61-75']['scored'] }}</span>
                                        <span class="badge badge-danger me-1">{{ $goalsCount[$team->id]['61-75']['conceded'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $goalsCount[$team->id]['76-90']['scored'] }}</span>
                                        <span class="badge badge-danger me-1">{{ $goalsCount[$team->id]['76-90']['conceded'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="8">
                                    <span class="badge badge-success"> . </span> - bramki zdobyte, <span class="badge badge-danger me-1"> . </span> - bramki stracone
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div style="display: inline-flex"><span style="font-weight: bold">{{ $selectedLeague->name }}</span> <span style="padding-left: 5px;">sezon: {{$selectedSeason->name}}</span></div>

                        <h1>Średnnia ilość bramek</h1>


                                    <table class="table table-borderless">
                                        <thead class="table bg-primary" style="color: white;">
                                        <tr>
                                            <th scope="col">Drużyna</th>
                                            <th scope="col">Mecze</th>
                                            <th scope="col">Śr. Br</th>
                                            <th scope="col">Śr. Bz</th>
                                            <th scope="col">Śr. Bs</th>
                                            <th scope="col">&gt;2.5</th>
                                            <th scope="col">&lt;2.5</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($teams as $team)
                                            <tr>
                                                <td>{{ $team->name }}</td>
                                                <td>{{ $statistics[$team->id]['M'] }}</td>
                                                <td>{{ $statistics[$team->id]['Śr. Br'] }}</td>
                                                <td>{{ $statistics[$team->id]['Śr. Bz'] }}</td>
                                                <td>{{ $statistics[$team->id]['Śr. Bs'] }}</td>
                                                <td>{{ $statistics[$team->id]['>2.5'] }}%</td>
                                                <td>{{ $statistics[$team->id]['<2.5'] }}%</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="8">
                                                <span style="font-weight: bold">M</span> - mecze, <span style="font-weight: bold">Śr. Br</span> - średnia bramek w meczach z udziałem drużyny, <span style="font-weight: bold">Śr. Bz</span> - średnia bramek zdobytych, <span style="font-weight: bold">Śr. Bs</span> - średnia bramek straconych, <span style="font-weight: bold">>2.5</span> - mecze z ilością bramek powyżej 2.5, <span style="font-weight: bold">< 2.5</span> - mecze z ilością bramek poniżej 2.5
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                        <div style="display: inline-flex"><span style="font-weight: bold">{{ $selectedLeague->name }}</span> <span style="padding-left: 5px;">sezon: {{$selectedSeason->name}}</span></div>
                        <h1 class="card-header">
                            Rozegrane mecze
                        </h1>
                        <div class="row" style="margin-right: 0px; padding-bottom: 3rem;">
                            <div class="col">
                                <canvas id="resultsChart"></canvas>
                            </div>
                            <div class="col">
                                <ul class="list-group">
                                    @php
                                        $totalValue = $chartData->sum('value');
                                    @endphp
                                    @foreach($chartData as $data)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex justify-content-start">
                                                {{ $data['label'] }}
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <span class="badge badge-primary badge-pill">{{ $data['value'] }}</span>
                                                <span class="badge badge-secondary badge-pill" style="margin-left: 10px;">{{ $totalValue !== 0 ? number_format(($data['value'] / $totalValue) * 100, 2) : 0 }}%</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div style="display: inline-flex"><span style="font-weight: bold">{{ $selectedLeague->name }}</span> <span style="padding-left: 5px;">sezon: {{$selectedSeason->name}}</span></div>

                        <h1 class="card-header">
                            Najczęstsze wyniki
                        </h1>

                        <div class="row" style="margin-right: 0px;">
                            <div class="col">
                                <table class="table table-borderless">
                                    <thead class="table bg-primary" style="color: white;">
                                    <tr>
                                        <th scope="col">Wynik</th>
                                        <th scope="col">Ilość</th>
                                        <th scope="col">Wystąpienia</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if ($commonResults && count($commonResults) > 0)
                                        @php
                                            $chunkedResults = $commonResults->chunk(ceil($commonResults->count() / 2));
                                        @endphp
                                        @foreach ($chunkedResults[0] as $result)
                                            <tr>
                                                <td>{{ $result['result'] }}</td>
                                                <td>{{ $result['count'] }}</td>
                                                <td>{{ number_format($result['percentage'], 2) }}%</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3">Brak danych</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col">
                                <table class="table table-borderless">
                                    <thead class="table bg-primary" style="color: white;">
                                    <tr>
                                        <th scope="col">Wynik</th>
                                        <th scope="col">Ilość</th>
                                        <th scope="col">Wystąpienia</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if ($commonResults && count($commonResults) > 0)
                                        @foreach ($chunkedResults[1] as $result)
                                            <tr>
                                                <td>{{ $result['result'] }}</td>
                                                <td>{{ $result['count'] }}</td>
                                                <td>{{ number_format($result['percentage'], 2) }}%</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3">Brak danych</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <h1 class="card-header">Dodatkowe statystyki</h1>

                        <table class="table table-borderless">
                            <thead class="table bg-primary" style="color: white;">
                            <tr>
                                <th scope="col">Statystyka</th>
                                <th scope="col">Ilość</th>
                                <th scope="col">Procent</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Mecze bez strzelonego gola</td>
                                <td>{{ $goallessMatches['count'] }}</td>
                                <td>{{ number_format($goallessMatches['percentage'], 2) }}%</td>
                            </tr>
                            <tr>
                                <td>Wygrane przynajmniej 1 bramką</td>
                                <td>{{ $winByAtLeastOneGoal['count'] }}</td>
                                <td>{{ number_format($winByAtLeastOneGoal['percentage'], 2) }}%</td>
                            </tr>
                            <tr>
                                <td>Wygrane przynajmniej 2 bramkami</td>
                                <td>{{ $winByAtLeastTwoGoals['count'] }}</td>
                                <td>{{ number_format($winByAtLeastTwoGoals['percentage'], 2) }}%</td>
                            </tr>
                            <tr>
                                <td>Wygrane 3 bramkami i więcej</td>
                                <td>{{ $winByThreeOrMoreGoals['count'] }}</td>
                                <td>{{ number_format($winByThreeOrMoreGoals['percentage'], 2) }}%</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var chartLabels = {!! $chartLabels !!};
        var chartValues = {!! $chartValues !!};
        var chartColors = ['rgba(82,199,21,0.9)', '#36A2EB', '#FFCE56'];

        var ctx = document.getElementById('resultsChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartValues,
                    backgroundColor: chartColors,
                }],
            },
            options: {
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var total = dataset.data.reduce(function (previousValue, currentValue) {
                                return previousValue + currentValue;
                            });
                            var currentValue = dataset.data[tooltipItem.index];
                            var percentage = parseFloat((currentValue / total * 100).toFixed(2));
                            return currentValue + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        });
    </script>

@endsection

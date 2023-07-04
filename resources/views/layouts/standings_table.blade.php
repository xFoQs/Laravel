<table class="table table-borderless">
    <thead class="table-success">
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
    <tbody class="table-body">
    @php
        $selectedSeasonId = $selectedSeasonId ?? null;
    @endphp
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

@yield('content')

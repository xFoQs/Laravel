
@extends('layouts.client')

@section('content')
    <div class="row mt-5 justify-content-center">
        <div class="col-8 mb-4">
            <div class="card">
                <h3 class="card-header">
                    Terminarz - {{ $selectedLeague->name }}, {{ $selectedSeason->name }}
                </h3>
                <div class="card-body">
                    <form id="standings-form" action="{{ route('schedule') }}" method="GET">
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

                    @php
                        $currentRound = null;
                        $firstRecord = true;
                        $polishMonths = [
                            1 => 'stycznia',
                            2 => 'lutego',
                            3 => 'marca',
                            4 => 'kwietnia',
                            5 => 'maja',
                            6 => 'czerwca',
                            7 => 'lipca',
                            8 => 'sierpnia',
                            9 => 'września',
                            10 => 'października',
                            11 => 'listopada',
                            12 => 'grudnia',
                        ];
                    @endphp

                    @foreach($games as $index => $game)
                        @php
                            $startDate = \Carbon\Carbon::parse($game->start_time);
                            $endDate = $startDate;
                            $isMultiDay = false;

                            if ($index + 1 < count($games) && $games[$index + 1]->round === $game->round) {
                                // Kolejka jest rozgrywana przez kilka dni
                                $endDate = \Carbon\Carbon::parse($games[$index + 1]->start_time);
                                $isMultiDay = true;
                            }

                            $gameDate = $startDate->format('d');
                            $gameMonth = $polishMonths[$startDate->format('n')];
                            $gameYear = $startDate->format('Y');
                        @endphp

                        @if($game->round !== $currentRound)
                            @php
                                $currentRound = $game->round;
                            @endphp

                            <h5 class="card-header" style="border-bottom: 2px solid #3b71ca;">
                                <span style="font-weight: normal; padding-right: 8px;">{{ $game->round }}</span>
                                @if($isMultiDay)
                                    @if($startDate->isBefore($endDate))
                                        {{ $startDate->format('d') }}/{{ $endDate->format('d') }}
                                    @else
                                       {{ $endDate->format('d') }}/{{ $startDate->format('d') }}
                                    @endif
                                @else
                                    {{ $gameDate }}
                                @endif
                                {{ $gameMonth }} {{ $gameYear }}
                            </h5>
                        @endif

                            <table class="table table-borderless">
                                <tbody>
                                <tr>
                                                                        <td class="text-start" style="width: 4%; text-align: left; padding: 4px;">
                                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $game->status }}">
                                            @if ($game->status == 'Nierozegrany')
                                                <i class="fa-solid fa-user-clock"></i>
                                            @elseif ($game->status == 'Koniec')
                                                <i class="fa-solid fa-calendar-check"></i>
                                            @else
                                                {{ $game->status }}
                                            @endif
                                        </span>
                                    </td>
                                    <td style="width: 32%; text-align: right; padding: 4px;">{{ $game->team1->name }}</td>
                                    <td style="width: 8%; text-align: center; padding: 4px;">
                                        <a href="game_data?game_id={{ $game->id }}" target="_blank" style="color: #0043de;">
        <span class="badge bg-primary">
            <strong>{{ $game->result1 !== null ? $game->result1 : '?' }}</strong>
        </span>
                                            :
                                            <span class="badge bg-primary">
            <strong>{{ $game->result2 !== null ? $game->result2 : '?' }}</strong>
        </span>
                                        </a>
                                    </td>
                                    <td style="width: 32%; text-align: left; padding: 4px;">{{ $game->team2->name }}</td>
                                    <td class="text-end" style="width: 22%; text-align: right; padding: 4px; font-size: 13px;">
                                        @php
                                            $startDateTime = \Carbon\Carbon::parse($game->start_time);
                                            $formattedDate = $startDateTime->day . ' ' . $polishMonths[$startDateTime->month] . ' ' . $startDateTime->year . ', <strong>' . $startDateTime->format('H:i') . '</strong>';
                                        @endphp
                                        {!! $formattedDate !!}
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

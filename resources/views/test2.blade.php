@extends('layouts.client')

@section('content')
    <div class="row mt-5 justify-content-center">
        <div class="col-8 mb-4">
            <div class="card">
                <h3 class="card-header">
                    Standings Table
                </h3>
                <div class="card-body">
                    <div class="table-responsive">
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
                            <tbody>
                            @foreach($teams as $team)
                                @php
                                    $goalStats = $team->getGoalStats();
                                @endphp
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ $team->won }}</td>
                                    <td>{{ $team->tied }}</td>
                                    <td>{{ $team->lost }}</td>
                                    <td>{{ $team->points }}</td>
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
@endsection

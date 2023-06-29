@extends('layouts.client')

@section('content')
    <style>
        .button-panel {
            display: flex;
        }

        .panel-button {
            position: relative;
            flex: 1;
            background-color: #f1f1f1;
            color: #4f4f4f;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .hide-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
        }

        .panel-button.active {
            background-color: #4f4f4f;
            color: #ffffff;
        }

        .panel-button.active .hide-icon {
            display: none;
        }
    </style>
    <div class="row mt-5 justify-content-center">
        <div class="col-8 mb-4">
            <div class="card">
                <h5 class="card-header">
                    List of players
                </h5>
                <ul class="list-group list-group-flush">
                    @foreach($players as $player)
                        <li class="list-group-item">{{ $player->name }} {{ $player->surname }} <span class="float-end badge bg-info">{{ $player->team->name }}</span></li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>

    <div class="button-panel">
        <button class="panel-button active">Przycisk 1<span class="hide-icon"><i class="fa-solid fa-minus-square"></i></span></button>
        <button class="panel-button">Przycisk 2<span class="hide-icon"><i class="fa-solid fa-minus-square"></i></span></button>
        <button class="panel-button">Przycisk 3<span class="hide-icon"><i class="fa-solid fa-minus-square"></i></span></button>
    </div>
@endsection


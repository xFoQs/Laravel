<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" type="text/css" href="css/admindashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchpanes/2.1.2/css/searchPanes.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.2/css/select.bootstrap4.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css" rel="stylesheet" />

    <!-- Sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .table {
            background-color: rgba(255, 255, 255, 0.5); /* Tło z przejrzystością 0.5 */
        }
    </style>

    <style>

        #example_filter input[type="search"] {
            width: 100%; /* Ustaw szerokość pola wyszukiwania na 100% szerokości elementu example_filter */
            height: 40px;
        }
    </style>


    <style>
        .nav-item.dropdown {
            position: relative;
        }

        .nav-item.dropdown .dropdown-toggle {
            cursor: pointer;
        }

        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
        }

        .nav-item.dropdown .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 999;
        }
    </style>

</head>

<body id="body-pd">

<header class="header" id="header">
    <div class="header_toggle">
        <i class='bx bx-menu' id="header-toggle"></i>
    </div>


    <div class="d-flex align-items-center justify-content-between">

        <a href="/livegame" data-bs-toggle="tooltip" data-bs-placement="top" title="Relacja live" style="padding-right: 1.5rem;">
            <i class="fas fa-headset"></i>
            <span class="badge rounded-pill badge-notification bg-danger"><span id="matchCount"></span></span>
        </a>

        <ul class="navbar-nav">
            <!-- Avatar -->
            <li class="nav-item dropdown">
                <div class="dropdown-toggle d-flex align-items-center">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img (31).webp" class="rounded-circle"
                         height="22" alt="Avatar" loading="lazy" />
                </div>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#myProfileModal">Mój Profil</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#passwordResetModal">Zmień Hasło</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('page') }}">Wyloguj</a>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="d-flex align-items-center m-1">
            <div class="vertical-line mx-3"></div>
            <b style="padding-right: 2px;">Witaj, </b> {{ auth()->user()->name }} !
        </div>

    </div>
</header>

    @extends('layouts.sidebar')

    <div id="content" style="padding-top:5rem; height:100%;">

        <div class="contenthead" style="display: flex; align-items: stretch; margin-bottom: 1rem; margin-top: 2rem; align-content: center;">
            <h1 style="margin-right: 1rem;">Zawodnicy</h1>

            <!-- Button trigger modal -->

            <button type="button" class="btn btn-success btn-rounded" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="height: 50px; width: 50px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                <i class="fas fa-plus" style="font-size: 20px;"></i>
            </button>


        </div>

        @if (Session::has('success'))
            <script>
                Swal.fire(
                    'Gratulacje!',
                    'Dodałeś zawodnika!',
                    'success'
                )
            </script>
        @endif

        @if (Session::has('update'))
            <script>
                Swal.fire(
                    'Gratulacje!',
                    'Zaaktualizowałeś dane zawodnika!',
                    'success'
                )
            </script>
        @endif

    </div>

    <br>

    <table id="example" class="table table-borderless" style="width:100%; padding-top: 1rem;">
        <thead class="table p-3 mb-2 bg-primary" style="color: white;">
            <tr>
                <th>#</th>
                <th>Zawodnik</th>
                <th>Data urodzenia</th>
                <th>Pozycja</th>
                <th>Klub</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($players as $player)
            @php
                $localizedDate = \Carbon\Carbon::parse($player->birth_date)->locale('pl_PL');
                $currentTeam = $player->team; // Pobranie aktualnego klubu zawodnika
                $lastSeason = $player->seasons->last(); // Pobranie ostatniego sezonu, w którym grał zawodnik
            @endphp
            <tr data-entry-id="{{ $player->id }}">
                <td class="align-middle">
                    {{ $loop->iteration }}
                </td>
                <td class="align-middle">
                    @if ($player->photo)
                        <img src="{{ asset('img/' . $player->photo) }}" alt="{{ $player->name }} {{ $player->surname }}" style="max-width: 40px; max-height: 40px; margin-right: 10px;">
                    @else
                        <img src="img/man.png" alt="{{ $player->name }} {{ $player->surname }}" style="max-width: 40px; max-height: 40px; margin-right: 10px;">
                    @endif
                    {{ $player->name }} {{ $player->surname }}
                </td>
                <td class="align-middle text-center">
                    <div class="d-flex align-items-center">
                       {{ date('d.m.Y', strtotime($player->birth_date)) }}
                    </div>
                </td>
                <td class="align-middle">
                    @if ($player->position)
                       {{ $player->position }}
                    @else
                        Brak pozycji
                    @endif
                </td>
                <td class="align-middle">
                    @if ($currentTeam)
                        <div class="d-flex align-items-center">
                            @if ($player->team->photo)
                                <img src="/img/{{ $player->team->photo }}" alt="{{ $player->team->name }}" style="max-width: 35px; max-height: 45px; margin-right: 10px;">
                            @else
                                <img src="/img/brak.webp" alt="Brak herbu klubu" style="max-width: 35px; max-height: 45px; margin-right: 10px;">
                            @endif
                            {{ $player->team->name }}
                        </div>
                    @else
                        Brak klubu
                    @endif
                </td>
                <td>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editplayer{{$player->id}}" title="Edycja">
                        <i class="fa-solid fa-pen-to-square" style="color:white;"></i>
                    </button>

                    <button class="btn btn-danger btn-sm delete" id="{{ $player->id }}" title="Usuń">
                        <i class="fa-solid fa-trash" style="color:white;"></i>
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


@foreach($users as $user)
    <div class="modal" id="myProfileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="myProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myProfileModalLabel">Mój Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form">
                                <div>
                                    <div class="mb-4 d-flex justify-content-center">
                                        <img id="preview{{$user->id}}" src="{{ $user->photo ? asset('img/' . $user->photo) : asset('img/236831.png') }}"
                                             alt="example placeholder" style="width: 180px; height: 200px;"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Imię i Nazwisko:</strong> {{ $user->name }}<span> </span>{{ $user->surname }}</li>
                                <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                                <li class="list-group-item"><strong>Rola:</strong> {{ $user->role->name }}</li>
                            </ul>
                        </div>

                        <div class="col-12">
                            <div class="modal-footer">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Dodaj zawodnika</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row gx-2 gy-1"  method="POST" action="{{ route('player.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form">
                                    <div>
                                        <div class="mb-2 d-flex justify-content-center">
                                            <img id="preview" src="{{ asset('img/man.png') }}"
                                                 alt="example placeholder" style="width: 190px; height: 200px;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row gx-2">
                                    <div class="col-md-4">
                                        <div class="form mb-2">
                                            <input type="text" class="form-control" name="name" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Podaj imię')" value="" required placeholder="Imię" />
                                            <label class="form-label" style="color:#444;"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form mb-2">
                                            <input type="text" class="form-control" name="surname" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Podaj nazwisko')" value="" required  placeholder="Nazwisko" />
                                            <label class="form-label" style="color:#444;"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form mb-2">
                                            <input type="date" class="form-control" name="birth_date" value="">
                                            <label for="validationCustom02" class="form-label" style="color:#444;"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-2">
                                    <div class="col-md-6">
                                        <div class="form mb-2">
                                            <select name="country" class="select2 form-control is-valid border rounded" data-width="100%" placeholder="Kraj">
                                                <option value="" selected disabled>Wybierz kraj</option>
                                                <option value="Polska">Polska</option>
                                                <option value="Ukraina">Ukraina</option>
                                                <option value="Niemcy">Niemcy</option>
                                                <option value="Hiszpania">Hiszpania</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form mb-2">
                                            <select name="position" class="select2 form-control is-valid border rounded" data-width="100%" placeholder="Pozycja">
                                                <option value="" selected disabled>Wybierz Pozycje</option>
                                                <option value="Napastnik">Napastnik</option>
                                                <option value="Pomocnik">Pomocnik</option>
                                                <option value="Obrońca">Obrońca</option>
                                                <option value="Bramkarz">Bramkarz</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form">
                                            <label class="form-label" for="customFile"></label>
                                            <input type="file" class="form-control" id="customFile" name="customFile" onchange="previewFile(event)" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4" style="border-top: 1px solid #ccc;">
                        <div class="col-md-12 mt-3">
                            <div class="d-flex align-items-center mb-3" style="margin: auto;">
                                <h6 class="me-3">Kariera Zawodnika</h6>
                                <button type="button" class="btn btn-success btn-floating" id="carieeradd">
                                    +
                                </button>
                            </div>
                        </div>
                        <table class="table" id="seasons-table-add">
                            <thead>
                            <tr>
                                <th>Nazwa sezonu</th>
                                <th>Klub</th>
                                <th>Akcje</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="col-12">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Zamknij</button>
                                <button type="submit" name="add" value="add"
                                    class="btn btn-primary">Wyślij</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    @foreach($players as $player)
        <div class="modal editplayer" id="editplayer{{$player->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" data-player-id="{{$player->id}}" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Edytuj zawodnika</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row gx-3" method="POST" action="{{route('player.update')}}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $player->id }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form">
                                        <div>
                                            <div class="mb-4 d-flex justify-content-center">
                                                <img id="preview{{$player->id}}" src="{{ $player->photo ? asset('img/' . $player->photo) : asset('img/man.png') }}"
                                                     alt="example placeholder" style="width: 190px; height: 200px;"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row gx-2">
                                        <div class="col-md-4">
                                            <div class="form">
                                                <input type="text" class="form-control" name="name" value="{{$player->name}}" required placeholder="Podaj imię" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Podaj imię')"/>
                                                <label for="validationCustom01" class="form-label"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form">
                                                <input type="text" class="form-control" name="surname" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Podaj nazwisko')" value="{{$player->surname}}" required placeholder="Podaj nazwisko"/>
                                                <label for="validationCustom02" class="form-label"></label>
                                                <div class="invalid-feedback">Nazwisko jest wymagane!</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form">
                                                <input type="date" class="form-control" name="birth_date" value="{{$player->birth_date}}" placeholder="Podaj datę urodzenia">
                                                <label class="form-label"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gx-2">
                                        <div class="col-md-6">
                                            <div class="form">
                                                <select name="position" class="select2 form-control is-valid border rounded" data-width="100%" placeholder="Pozycja">
                                                    <option value="Napastnik" {{ $player->position == 'Napastnik' ? 'selected' : '' }}>
                                                        Napastnik
                                                    </option>
                                                    <option value="Pomocnik" {{ $player->position == 'Pomocnik' ? 'selected' : '' }}>
                                                        Pomocnik
                                                    </option>
                                                    <option value="Obrońca" {{ $player->position == 'Obrońca' ? 'selected' : '' }}>
                                                        Obrońca
                                                    </option>
                                                    <option value="Bramkarz" {{ $player->position == 'Bramkarz' ? 'selected' : '' }}>
                                                        Bramkarz
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form">
                                                <select name="country" class="select2 form-control is-valid border rounded" data-width="100%" placeholder="Kraj">
                                                    <option value="">Wybierz kraj</option>
                                                    <option value="Polska" {{ $player->country == 'Polska' ? 'selected' : '' }}>Polska</option>
                                                    <option value="Ukraina" {{ $player->country == 'Ukraina' ? 'selected' : '' }}>Ukraina</option>
                                                    <option value="Niemcy" {{ $player->country == 'Niemcy' ? 'selected' : '' }}>Niemcy</option>
                                                    <option value="Hiszpania" {{ $player->country == 'Hiszpania' ? 'selected' : '' }}>Hiszpania</option>
                                                </select>
                                            </div>
                                        </div>
                                    <div class="row gx-2">
                                        <div class="col-md-12">
                                            <div class="form">
                                                <label class="form-label" for="customFile{{$player->id}}"></label>
                                                <input type="file" class="form-control" id="customFile{{$player->id}}" name="customFile" onchange="previewFile2(event, {{$player->id}})" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4" style="border-top: 1px solid #ccc;">
                            <div class="col-md-12 mt-3">
                                <div class="d-flex align-items-center mb-3" style="margin: auto;">
                                    <h6 class="me-3">Kariera Zawodnika</h6>
                                    <button type="button" class="btn btn-success btn-floating add-season-row" data-player-id="{{$player->id}}">
                                        +
                                    </button>
                                </div>
                            </div>
                            <table class="table" id="seasons-table{{$player->id}}">
                                <thead>
                                <tr>
                                    <th>Nazwa sezonu</th>
                                    <th>Klub</th>
                                    <th>Akcje</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($player->seasons->sortByDesc('id') as $key => $season)
                                    <tr>
                                        <td>
                                            <select name="season_name[{{$player->id}}][]" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" required>
                                                <option value="">--Wybierz sezon--</option>
                                                @foreach($seasons as $s)
                                                    <option value="{{$s->id}}" {{$season->id == $s->id ? 'selected' : ''}}>
                                                        {{$s->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="season_team_id[{{$player->id}}][]" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" required>
                                                <option value="">--Wybierz klub--</option>
                                                @foreach($teams as $team)
                                                    <option value="{{$team->id}}" {{$season->pivot->team_id == $team->id ? 'selected' : ''}}>
                                                        {{$team->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger delete-season-row">Usuń</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="col-12">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                                    <button type="submit" name="add" value="add" class="btn btn-primary">Wyślij</button>
                                </div>
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

<div class="modal fade" id="passwordResetModal" tabindex="-1" aria-labelledby="passwordResetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordResetModalLabel">Zmiana hasła</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="passwordResetForm">
                    @csrf

                    <!-- Current Password input -->
                    <div class="mb-4">
                        <label for="currentPassword" class="form-label">Aktualne hasło</label>
                        <input type="password" id="currentPassword" name="current_password" class="form-control" required />
                    </div>

                    <!-- New Password input -->
                    <div class="mb-4">
                        <label for="newPassword" class="form-label">Nowe hasło</label>
                        <input type="password" id="newPassword" name="new_password" class="form-control" required />
                    </div>

                    <!-- Confirm Password input -->
                    <div class="mb-4">
                        <label for="confirmPassword" class="form-label">Potwierdź hasło</label>
                        <input type="password" id="confirmPassword" name="new_password_confirmation" class="form-control" required />
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Zmień hasło</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.datatables.net/searchpanes/2.1.2/js/dataTables.searchPanes.min.js"></script>
    <script src="https://cdn.datatables.net/searchpanes/2.1.2/js/searchPanes.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.6.2/js/dataTables.select.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>


    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (() => {
            'use strict';

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation');

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms).forEach((form) => {
                form.addEventListener('submit', (event) => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>

<script>
    function previewFile2(event, playerId) {
        var preview = document.getElementById("preview" + playerId);
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "{{ asset('img/man.png') }}";
        }
    }
</script>

    <script>
        // Inicjalizacja select2 po otwarciu modala
        $('#staticBackdrop').on('shown.bs.modal', function() {
            $(this).find('.select2').select2({
                dropdownParent: $(this)

            });

        });

        // Dodawanie wiersza na początek tabeli
        $('#carieeradd').on('click', function() {
            var newRow = '<tr>' +
                '<td>' +
                '<select name="season_name[]" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" required>' +
                '<option value="" disabled selected>--wybierz sezon--</option>' +
                '@foreach($seasons as $season)' +
                '<option value="{{$season->id}}">{{$season->name}}</option>' +
                '@endforeach' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select name="season_team_id[]" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" required>' +
                '<option value="" disabled selected>Wybierz klub</option>' +
                '@foreach($teams as $team)' +
                '<option value="{{$team->id}}">{{$team->name}}</option>' +
                '@endforeach' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<button type="button" class="btn btn-sm btn-danger delete-season-row">Usuń</button>' +
                '</td>' +
                '</tr>';

            $('#seasons-table-add tbody').prepend(newRow); // Użyj prepend(), aby dodać nowy wiersz na początku tabeli

            // Zainicjuj ponownie select2 dla nowego wiersza
            $('#seasons-table-add tbody tr:first-child .select2').select2({
                dropdownParent: $('#staticBackdrop')
            });
        });

        // Usuwanie wiersza z tabeli
        $(document).on('click', '.delete-season-row', function() {
            $(this).closest('tr').remove();
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.add-season-row', function() {
                var playerId = $(this).data('player-id');
                var newRow = '<tr>' +
                    '<td><select name="season_name[' + playerId + '][]" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" required>' +
                    '<option value="">--Wybierz sezon--</option>' + // Dodaj domyślny tekst "--Wybierz sezon--"
                    '@foreach($seasons as $season)' +
                    '<option value="{{$season->id}}" ' +
                    '@foreach($player->seasons as $playerSeason)' +
                    '@if($playerSeason->season_id == $season->id)' +
                    'selected' +
                    '@endif' +
                    '@endforeach' +
                    '>{{$season->name}}</option>' +
                    '@endforeach' +
                    '</select></td>' +
                    '<td><select name="season_team_id[' + playerId + '][]" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" required>' +
                    '<option value="">--Wybierz klub--</option>' + // Dodaj domyślny tekst "--Wybierz klub--"
                    '@foreach($teams as $team)' +
                    '<option value="{{$team->id}}">{{$team->name}}</option>' +
                    '@endforeach' +
                    '</select></td>' +
                    '<td><button type="button" class="btn btn-sm btn-danger delete-season-row">Usuń</button></td>' +
                    '</tr>';

                $('#seasons-table' + playerId + ' tbody').prepend(newRow);

                // Zainicjuj ponownie select2 dla nowego wiersza
                $('#seasons-table' + playerId + ' tbody tr:first-child .select2').select2({
                    dropdownParent: $('#editplayer' + playerId)
                });
            });

            $(document).on('click', '.delete-season-row', function() {
                $(this).closest('tr').remove();
            });

            $('.modal').on('shown.bs.modal', function() {
                $(this).find('.select2').select2({
                    dropdownParent: $(this)
                });
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Zdarzenie dla modala "editplayer"
            $('.editplayer').on('shown.bs.modal', function() {
                var playerId = $(this).data('player-id');
                $(this).find('.select2').select2({
                    dropdownParent: $(this)
                });
                // Wykonaj inne operacje na podstawie playerId
            });

            // Zdarzenie dla modala "staticBackdrop"
            $('#staticBackdrop').on('shown.bs.modal', function() {
                $(this).find('.select2').select2({
                    dropdownParent: $(this)
                });
            });
        });
    </script>

    <script>
        $(".delete").click(function() {
            Swal.fire({
                title: 'Jesteś pewny?',
                text: "Nie będziesz mógł tego cofnąć!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tal, usuń!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('player.delete')}}",
                        type: "GET",
                        dataType: 'json',
                        data: {
                            event_id: $(this).attr('id'),
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Zawodnik został usunięty!",
                                text: "Naciśnij przycisk aby przeładować stronę",
                                icon: "success",
                                showConfirmButton: true
                            }).then((result) => {
                                location.reload();
                            });
                        },
                        error: function(error) {
                            Swal.fire('Nie udało sie usunąć zawodnika!', '', 'error');
                        },
                    })
                }
            })
        });
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                paging: true,
                searchPanes: {
                    collapse: false
                },
                dom: 'PBfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: 'Kopiuj', // Zmienione "Copy" na "Kopiuj"
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV', // Zmienione "CSV" na "CSV"
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel', // Zmienione "Excel" na "Excel"
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF', // Zmienione "Pdf" na "Pdf"
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Drukuj', // Zmienione "Print" na "Drukuj"
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    }
                ],
                language: {
                    searchPanes: {
                        clearMessage: 'Wyczyść',
                        collapse: {
                            0: 'Pokaż',
                            _: 'Pokaż wszystko (%d)',
                        },
                        count: '{total}',
                        emptyPanes: 'Brak danych do wyświetlenia',
                        loadMessage: 'Ładowanie filtrów...',
                        title: 'Filtry aktywne - %d',
                        viewTotal: false, // Usunięcie komunikatu "filtered from 30 total entries"
                    },
                    paginate: {
                        first: 'Pierwsza',
                        previous: 'Poprzednia',
                        next: 'Następna',
                        last: 'Ostatnia',
                    },
                    info: '',
                    infoFiltered: "",
                    search: '',
                },
                columnDefs: [
                    {

                        searchPanes: {
                            show: true,
                        },
                        targets: [1, 2, 3, 4],

                    },

                    {
                        searchPanes: {
                            show: false,
                        },
                        targets: [5],
                    },

                ],
            });

            table.searchPanes.container().prependTo('#searchPanesContainer');
            table.searchPanes.resizePanes();

            // Funkcja do odświeżania tabeli
            function refreshTable() {
                table.draw();
            }

            refreshTable();

            // Znajdź pole wyszukiwania DataTables
            var searchInput = $('input[type="search"]');

            // Ustaw atrybut "placeholder" na wartość "Szukaj..."
            searchInput.attr('placeholder', 'Szukaj...');

            // Dodaj ikonę lupy do pola wyszukiwania
            searchInput.before('<i class="fas fa-search" style="color: gray; position: absolute; left: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>');

            // Stylizacja ikony i pola wyszukiwania
            searchInput.parent().css('position', 'relative');
            searchInput.css('padding-left', '25px');
        });
    </script>

<script>
    $(document).ready(function() {
        $('.toggle-match').on('click', function () {
            var gameId = $(this).data('game-id');
            var storedGames = JSON.parse(getCookie('selectedGames')) || [];

            var index = storedGames.indexOf(gameId);
            if (index === -1) {
                // Dodaj do pamięci podręcznej
                storedGames.push(gameId);
            } else {
                // Usuń z pamięci podręcznej
                storedGames.splice(index, 1);
            }

            // Zapisz zmiany w pliku cookie
            setCookie('selectedGames', JSON.stringify(storedGames));

            // Aktualizuj ikony dodawania/usuwania meczu
            updateMatchIcons();

            // Aktualizuj liczbę meczów w przycisku "Prowadź relacje"
            updateMatchCount();
        });

        function updateMatchIcons() {
            var storedGames = JSON.parse(getCookie('selectedGames')) || [];
            $('.toggle-match').each(function () {
                var gameId = $(this).data('game-id');
                var plusIcon = $(this).find('.plus-icon');
                var minusIcon = $(this).find('.minus-icon');

                if (storedGames.indexOf(gameId) === -1) {
                    plusIcon.removeClass('hidden');
                    minusIcon.addClass('hidden');
                } else {
                    plusIcon.addClass('hidden');
                    minusIcon.removeClass('hidden');
                }
            });
        }

        function updateMatchCount() {
            var storedGames = JSON.parse(getCookie('selectedGames')) || [];
            var matchCount = storedGames.length;
            $('#matchCount').text('(' + matchCount + ')');
        }

        // Funkcja do pobierania wartości pliku cookie
        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length === 2) {
                return parts.pop().split(";").shift();
            }
            return null;
        }

        // Funkcja do ustawiania pliku cookie
        function setCookie(name, value) {
            var date = new Date();
            date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000)); // Ważność pliku cookie - 1 rok
            var expires = "expires=" + date.toUTCString();
            document.cookie = name + "=" + value + "; " + expires + "; path=/";
        }

        // Wywołaj funkcję updateMatchIcons() przy starcie strony, aby zaktualizować ikony
        updateMatchIcons();
        updateMatchCount();
    });
</script>

<script>
    function previewFile(event) {
        var preview = document.getElementById('preview');
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "{{ asset('img/brak.webp') }}";
        }
    }
</script>

<script>
    function previewFile2(event, teamId) {
        var preview = document.getElementById("preview" + teamId);
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "{{ asset('img/brak.webp') }}";
        }
    }
</script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            allowClear: true, // Ta opcja umożliwia wyczyszczenie wyboru
            closeOnSelect: true, // Zamknij rozwijaną listę po wyborze
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const passwordResetForm = document.getElementById("passwordResetForm");

        passwordResetForm.addEventListener("submit", function(event) {
            event.preventDefault();

            const formData = new FormData(passwordResetForm);

            fetch("/change-password", {
                method: "POST",
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.password_changed) {
                        Swal.fire({
                            title: "Success",
                            text: data.message,
                            icon: "success",
                        }).then(function() {
                            $('#passwordResetModal').modal('hide');
                        });

                        passwordResetForm.reset();
                    } else if (data.password_error) {
                        Swal.fire({
                            title: "Error",
                            text: data.message,
                            icon: "error",
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        });
    });
</script>


<script src="js/admin.js"></script>


</body>

</html>


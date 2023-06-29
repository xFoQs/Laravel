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


</head>

<body id="body-pd">

    <header class="header" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
        <div>
            <b>Witaj</b> {{ auth()->user()->name }} !
        </div>
    </header>

    @extends('layouts.sidebar')

    <div id="content" style="padding-top:5rem; height:100%;">

        <div class="contenthead" style="display:flex; justify-content:space-between; margin-bottom:1rem;margin-top: 2rem;">
            <h2 style="padding-right:2rem;">Zawodnicy</h2>

            <!-- Button trigger modal -->

                <button type="button" class="btn btn-outline-primary btn-rounded" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop" style="height: 45px;width: 170px;">
                    Dodaj zawodnika
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

    <table id="example" class="table table-bordered" style="width:100%">
        <thead>
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
                <td>{{ $loop->iteration }}</td>
                <td>{{ $player->name }} {{ $player->surname }}</td>
                <td>{{ date('d.m.Y', strtotime($player->birth_date)) }}</td>
                <td>{{ $player->position }}</td>
                <td>{{$player->team->name}}
{{--                    @if ($player->playerSeasons()->exists())--}}
{{--                        @php--}}
{{--                            $lastPlayerSeason = $player->playerSeasons->last();--}}
{{--                            $seasonName = $lastPlayerSeason->season->name;--}}
{{--                            $season = $seasonName;--}}
{{--                        @endphp--}}
{{--                        {{ $lastPlayerSeason->team->name }}--}}
{{--                    @else--}}
{{--                        Brak klubu--}}
{{--                    @endif--}}
                </td>
{{--                <td>--}}
{{--                    @php--}}
{{--                        $lastPlayerSeason = $player->playerSeasons->last();--}}
{{--                        $seasonName = $lastPlayerSeason->season->name;--}}
{{--                        $season = $seasonName;--}}
{{--                    @endphp--}}
{{--                    {{$season}}--}}
{{--                </td>--}}
                <td>
                    <a data-bs-toggle="modal" data-bs-target="#editplayer{{$player->id}}"><i
                            class="fa-solid fa-pen-to-square" style="color:#4f4f4f; padding-right: 0.5rem;"></i></a>
                    <a href="#" class="delete" id="{{ $player->id }}"><i class="fa-solid fa-trash"
                                                                         style="color:#4f4f4f;"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

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
                    <form class="row g-3"  method="POST" action="{{ route('player.store') }}">
                        @csrf
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" class="form-control" name="name"  onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Podaj imię')"
                                    value="" required />
                                <label class="form-label" style="color:#444;">Imię</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" class="form-control" name="surname" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Podaj nazwisko')"
                                    value="" required />
                                <label class="form-label" style="color:#444;">Nazwisko</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="date" class="form-control" name="birth_date" value="">
                                <label for="validationCustom02" class="form-label" style="color:#444;">Data urodzenia</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form">
                                <select name="position" class="select2 form-control is-valid border rounded" data-width="100%" placeholder="Pozycja">
                                    <option value="" selected disabled>-- Wybierz Pozycje --</option>
                                    <option value="Napastnik">
                                        Napastnik
                                    </option>
                                    <option value="Pomocnik">
                                        Pomocnik
                                    </option>
                                    <option value="Obrońca">
                                        Obrońca
                                    </option>
                                    <option value="Bramkarz">
                                        Bramkarz
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form">
                                <select name="country" class="select2 form-control is-valid border rounded" data-width="100%" placeholder="Kraj">
                                    <option value="" selected disabled>Wybierz kraj</option>
                                    <option value="Polska"> Polska</option>
                                    <option value="Ukraina"> Ukraina</option>
                                    <option value="Niemcy"> Niemcy</option>
                                    <option value="Hiszpania"> Hiszpania</option>
                                </select>
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
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="add" value="add"
                                    class="btn btn-primary">Send</button>
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
                        <form class="row gx-3" method="POST" action="{{route('player.update')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $player->id }}">
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
                                @foreach($player->seasons as $key => $season)
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
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="add" value="add" class="btn btn-primary">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


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
                paging: false,
                searchPanes: true,
                dom: 'PBfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'print',
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
                            _: 'Pokaż wszystko (%d)'
                        },
                        count: '{total}',
                        countFiltered: '{shown} ({total})',
                        emptyPanes: 'Brak danych do wyświetlenia',
                        loadMessage: 'Ładowanie filtrów...',
                        title: 'Filtry aktywne - %d'
                    },
                },
                columnDefs: [
                    {
                        searchPanes: {
                            show: true
                        },
                        targets: [1, 2, 3, 4]
                    },
                    {
                        searchPanes: {
                            show: false
                        },
                        targets: [5]
                    }
                ]
            });

            table.searchPanes.container().prependTo('#searchPanesContainer');
            table.searchPanes.resizePanes();

            // Funkcja do odświeżania tabeli
            function refreshTable() {
                table.draw();
            }

            refreshTable();
        });

    </script>


<script src="js/admin.js"></script>


</body>

</html>


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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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

    <div class="contenthead" style="display:flex; justify-content:space-between; margin-bottom:1rem; margin-top: 2rem">

        <div><h2 style="padding-right:2rem;">Zarządzaj meczami</h2></div>
        <!-- Button trigger modal -->
        <div>
            <button type="button" class="btn btn-outline-primary btn-rectangle" data-bs-toggle="modal"
                    data-bs-target="#staticBackdrop">
                Dodaj Mecz
            </button>

            <a href="/livegame" class="btn btn-outline-primary btn-rectangle">
                Prowadź relacje <span id="matchCount"></span>
            </a>

        </div>
    </div>

    @if (Session::has('success'))
        <script>
            Swal.fire(
                'Gratulacje!',
                'Dodałeś mecz!',
                'success'
            )
        </script>
    @endif

    @if (Session::has('update'))
        <script>
            Swal.fire(
                'Gratulacje!',
                'Zaaktualizowałeś dane o meczu!',
                'success'
            )
        </script>
    @endif

</div>

<br>
<div style="width: 100%; margin: 0 auto;">
    <table id="example" class="table table-bordered display" style="width:100%">
        <thead>
        <tr>
            <th>No</th>
            <th>Nazwa</th>
            <th>Liga</th>
            <th>Kolejka</th>
            <th>Data</th>
            <th>Godzina</th>
            <th>Sezon</th>
            <th>Wynik Meczu</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($games as $game)
            @php
                $localizedDate = \Carbon\Carbon::parse($game->start_time)->locale('pl_PL');
                $localizedTime = \Carbon\Carbon::parse($game->start_time)->format('H:i');
            @endphp
            <tr class="searchPanes-value" data-entry-id="{{ $game->id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $game->team1->name }} - {{ $game->team2->name }}</td>
                <td>{{ $game->league->name }}</td>
                <td>{{ explode('-', $game->round)[0] }}</td>
                <td>{{ $localizedDate->isoFormat('D MMMM YYYY') }}</td>
                <td>{{ $localizedTime }}</td>
                <td>{{ $game->season->name ?? 'Brak sezonu' }}</td>
                <td>
                    @if ($game->result1 !== null && $game->result2 !== null)
                        {{ $game->result1 }}:{{ $game->result2 }}
                    @endif
                </td>
                <td>
                    <a class="toggle-match" data-game-id="{{ $game->id }}">
                        @if (in_array($game->id, $selectedGames))
                            <i class="fa-solid fa-minus-circle minus-icon" style="color:#4f4f4f; padding-right: 0.5rem;" title="Usuń z relacji"></i>
                            <i class="fa-solid fa-plus-circle plus-icon hidden" style="color:#4f4f4f; padding-right: 0.5rem;" title="Dodaj do relacji"></i>
                        @else
                            <i class="fa-solid fa-minus-circle minus-icon hidden" style="color:#4f4f4f; padding-right: 0.5rem;"></i>
                            <i class="fa-solid fa-plus-circle plus-icon" style="color:#4f4f4f; padding-right: 0.5rem;"></i>
                        @endif
                    </a>
                    <a data-bs-toggle="modal" data-bs-target="#edit_game_{{ $game->id }}" data-game-id="{{ $game->id }}">
                        <i class="fa-solid fa-pen-to-square" style="color:#4f4f4f; padding-right: 0.5rem;"></i>
                    </a>
                    <a href="#" class="delete" id="{{ $game->id }}">
                        <i class="fa-solid fa-trash" style="color:#4f4f4f;"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Dodaj mecz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3"  method="POST" action="{{ route('games.store') }}">
                    @csrf
                    <div class="col-md-4">
                        <div class="form">
                            <select id="season_id" name="season_id" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" required>
                                <option value="">Wybierz sezon</option>
                                @foreach ($seasons as $season)
                                    <option value="{{ $season->id }}">{{ $season->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form">
                            <select id="league_id" name="league_id" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" onchange="handleLeagueChange(this)" required>
                                <option value="" selected>-- Wybierz Ligę --</option>
                                @foreach($leagues as $id => $league)
                                    <option value="{{ $id }}">{{ $league }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form">
                            <select id="round" name="round" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" required>
                                <option value="" selected>-- Wybierz Kolejkę --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form">
                            <select id="team1_id" name="team1_id" class="form-control border rounded border-1 select2" data-width="100%" placeholder="Gospodarze" oninvalid="this.setCustomValidity('Wybierz Klub')" required>
                                <!-- Opcje będą dynamicznie dodawane za pomocą JavaScript -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form">
                            <select id="team2_id" name="team2_id" class="form-control border rounded border-1 select2" data-width="100%" placeholder="Gospodarze" oninvalid="this.setCustomValidity('Wybierz Klub')" required>
                                <!-- Opcje będą dynamicznie dodawane za pomocą JavaScript -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-outline datetimepicker">
                            <input type="datetime-local" class="form-control datetime" name="start_time" value="" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Wybierz Date')" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-outline">
                            <input type="text" class="form-control" name="result1" value="" />
                            <label for="result1" class="form-label">Wynik Gospodarzy</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-outline">
                            <input type="text" class="form-control" name="result2" value=""/>
                            <label for="result2" class="form-label">Wynik Gości</label>
                        </div>
                    </div>
                    <div class="col-12" style="padding-top: 1rem">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Zatwierdz')"/>
                            <label class="form-check-label" for="invalidCheck">Zatwierdz</label>
                        </div>
                    </div>
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


<!-- Modal -->
@foreach($games as $game)
    <div class="modal editgame" data-game_id="{{$game->id}}" id="edit_game_{{ $game->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edytuj mecz</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3"  method="POST" action="{{ route('games.update', $game->id)}}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $game->id }}">
                        <div class="col-md-4">
                            <div class="form">
                                <select id="season_id" name="season_id" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" required>
                                    <option value="">Wybierz sezon</option>
                                    @foreach ($seasons as $season)
                                        <option value="{{ $season->id }}" {{ $game->season_id == $season->id ? 'selected' : '' }}>{{ $season->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form">
                                <select id="league_id_edit_{{ $game->id }}" name="league_id_edit" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%"  data-game-id="{{ $game->id }}" required>
                                    <option value="" selected>-- Wybierz Ligę --</option>
                                    @foreach($leagues as $id => $league)
                                        <option value="{{ $id }}" @if($id == old('league_id_edit', isset($game) ? $game->league_id : '')) selected @endif>{{ $league }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form">
                                <select id="round_edit_{{ $game->id }}" name="round_edit" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" required>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form">
                                <select name="team1_id_edit" id="team1_id_edit_{{ $game->id }}" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" placeholder="Gospodarze" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Wybierz Klub')" required>
                                    <!-- Puste opcje - zostaną uzupełnione dynamicznie -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form">
                                <select name="team2_id_edit" id="team2_id_edit_{{ $game->id }}" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" placeholder="Goście" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Wybierz Klub')" required>
                                    <!-- Puste opcje - zostaną uzupełnione dynamicznie -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline datetimepicker">
                                <input type="datetime-local" class="form-control datetime" name="start_time" id="start_time" value="{{ old('start_time', $game->start_time) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" class="form-control" name="result1" value="{{ old('result1', $game->result1) }}" id="result1"/>
                                <label for="result1" class="form-label">Wynik Gospodarzy</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" class="form-control" name="result2"
                                       value="{{ old('result2', $game->result2) }}" id="result2"/>
                                <label for="result2" class="form-label">Wynik Gości</label>
                            </div>
                        </div>
                        <div class="col-12" style="padding-top: 1rem">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck"
                                       required/>
                                <label class="form-check-label">Zatwierdz</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <!-- <button class="btn btn-primary" type="submit">Submit form</button> -->
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



{{--<script>--}}
{{--    $('.selectpicker').selectpicker({--}}
{{--        style: 'btn-bg: rgba(0,0,0,0)',--}}
{{--    });--}}
{{--</script>--}}


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
    $(document).ready(function() {
        $('.select2').select2();

        // Zdarzenie dla modala "editgame"
        $('.editgame').on('shown.bs.modal', function() {
            console.log("kurwo");
            var gameId = $(this).data('game_id');
            console.log(gameId);
            $(this).find('.select2').select2({
                dropdownParent: $(this)
            });
            handleLeagueEditChange(gameId,true);
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
    function handleLeagueChange(selectElement) {

        var leagueId = selectElement.value;
        var roundSelectElement = document.getElementById('round');

        // Wyczyść opcje w select kolejek
        roundSelectElement.innerHTML = '<option value="" selected>-- Wybierz Kolejkę --</option>';

        if (leagueId) {
            // Pobierz drużyny dla wybranej ligi za pomocą AJAX
            $.ajax({
                url: '/get-teams-by-league',
                type: 'GET',
                data: { league_id: leagueId },
                dataType: 'json',
                    success: function(response) {
                        if (response && response.length) {
                            var teamsInLeague = response;
                            var numberOfTeams = teamsInLeague.length;

                            // Oblicz liczbę kolejek
                            var numberOfRounds = (numberOfTeams - 1) * 2;

                            // Pobierz nazwę ligi
                            var selectedLeagueName = selectElement.options[selectElement.selectedIndex].text;

                            // Dodaj opcje kolejek do selecta
                            for (var i = 1; i <= numberOfRounds; i++) {
                                var option = document.createElement('option');
                                option.value = i + '. kolejka - ' + selectedLeagueName;
                                option.textContent = i + '. kolejka - ' + selectedLeagueName;
                                roundSelectElement.appendChild(option);
                            }

                            console.log(numberOfRounds);
                        }

                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

        }

        // Twój kod obsługujący wybieranie drużyn
        var selectedLeagueId = leagueId;
        var team1Select = $('#team1_id');
        var team2Select = $('#team2_id');

        if (selectedLeagueId === '') {
            team1Select.empty().append('<option value="" selected>-- Wybierz Klub --</option>');
            team2Select.empty().append('<option value="" selected>-- Wybierz Klub --</option>');
            return;
        }


        $.ajax({
            url: '/get-teams-by-league',
            type: 'GET',
            data: { league_id: selectedLeagueId },
            dataType: 'json',
            success: function(response) {
                team1Select.empty().append('<option value="" selected>-- Wybierz Klub --</option>');
                team2Select.empty().append('<option value="" selected>-- Wybierz Klub --</option>');

                response.forEach(function(team) {
                    var option1 = new Option(team.name, team.id);
                    var option2 = new Option(team.name, team.id);

                    team1Select.append(option1);
                    team2Select.append(option2);
                });

                team1Select.trigger('change');
                team2Select.trigger('change');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    $(document).ready(function() {
        $('.select2').select2();

        $('#team1_id').on('select2:select', function(e) {
            var selectedTeamId = e.params.data.id;
            $('#team2_id option').each(function() {
                var option = $(this);
                option.prop('disabled', false);

                if (option.val() === selectedTeamId) {
                    option.prop('disabled', true);
                }
            });
        });

        $('#team2_id').on('select2:select', function(e) {
            var selectedTeamId = e.params.data.id;
            $('#team1_id option').each(function() {
                var option = $(this);
                option.prop('disabled', false);

                if (option.val() === selectedTeamId) {
                    option.prop('disabled', true);
                }
            });
        });
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
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
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
                    targets: [2, 3, 4, 6]
                },
                {
                    searchPanes: {
                        show: false
                    },
                    targets: [5, 7]
                }
            ],
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


<script>
    function handleLeagueEditChange(gameId, replace) {
        var leagueId = $('#league_id_edit_' + gameId).val();

        if (!leagueId) {
            return;
        }

        var team1Select = $('#team1_id_edit_' + gameId);
        var team2Select = $('#team2_id_edit_' + gameId);
        var roundSelectElement = $('#round_edit_' + gameId);

        console.log(gameId);
        console.log(leagueId);
        console.log(roundSelectElement);

        // Pobierz drużyny dla wybranej ligi za pomocą AJAX
        $.ajax({
            url: '/get-teams-by-league-edit',
            type: 'GET',
            data: {league_id_edit: leagueId},
            dataType: 'json',
            success: function (response) {
                team1Select.empty().append('<option value="" selected>-- Wybierz Klub --</option>');
                team2Select.empty().append('<option value="" selected>-- Wybierz Klub --</option>');

                response.forEach(function (team) {
                    var option1 = new Option(team.name, team.id);
                    var option2 = new Option(team.name, team.id);

                    team1Select.append(option1);
                    team2Select.append(option2);
                });

                // Zaznacz domyślną drużynę na podstawie danych z tabeli games
                var selectedTeam1Id = "";
                var selectedTeam2Id = "";

                @if(isset($game))
                    selectedTeam1Id = "{{ $game->team1_id }}";
                selectedTeam2Id = "{{ $game->team2_id }}";
                @endif

                if (gameId && gameId !== '') {
                    // Pobierz dane meczu za pomocą AJAX
                    $.ajax({
                        url: '/get-game-teams',
                        type: 'GET',
                        data: {game_id: gameId},
                        dataType: 'json',
                        success: function (response) {
                            if (response && response.team1_id && response.team2_id) {
                                selectedTeam1Id = response.team1_id;
                                selectedTeam2Id = response.team2_id;
                            }

                            team1Select.val(selectedTeam1Id).trigger('change');
                            team2Select.val(selectedTeam2Id).trigger('change');
                        },
                        error: function (xhr, status, error) {
                            console.error(error);
                        }
                    });
                } else {
                    team1Select.val(selectedTeam1Id).trigger('change');
                    team2Select.val(selectedTeam2Id).trigger('change');
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });

        if (leagueId > 0) {
            // Pobierz drużyny dla wybranej ligi za pomocą AJAX
            $.ajax({
                url: '/get-teams-by-league-edit',
                type: 'GET',
                data: {league_id_edit: leagueId},
                dataType: 'json',
                success: function (response) {
                    if (response && response.length) {
                        var teamsInLeague = response;
                        var numberOfTeams = teamsInLeague.length;

                        // Oblicz liczbę kolejek
                        var numberOfRounds = (numberOfTeams - 1) * 2;

                        // Pobierz nazwę ligi
                        var selectedLeagueName = $('#league_id_edit_' + gameId + ' option:selected').text();

                        // Pobierz wartość z kolumny 'round' dla edytowanego meczu
                        var roundValue = ""; // Zmienna, do której zostanie przypisana wartość z kolumny 'round'

                        if (gameId && gameId !== '') {
                            // Pobierz dane meczu za pomocą AJAX
                            $.ajax({
                                url: '/get-game-teams',
                                type: 'GET',
                                data: {game_id: gameId},
                                dataType: 'json',
                                success: function (response) {
                                    if (response && response.round) {
                                        roundValue = response.round;
                                    }

                                    populateRoundSelect(roundValue);
                                },
                                error: function (xhr, status, error) {
                                    console.error(error);
                                }
                            });
                        } else {
                            populateRoundSelect(roundValue);
                        }

                        function populateRoundSelect(roundValue) {
                            // Pobierz select dla odpowiedniego gameId
                            var roundSelectElement = $('#round_edit_' + gameId);

                            // Usuń wszystkie opcje z selecta
                            roundSelectElement.empty();

                            // Dodaj placeholder "Wybierz kolejkę"
                            roundSelectElement.append($('<option></option>').text('-- Wybierz kolejkę --'));

                            for (var i = 1; i <= numberOfRounds; i++) {
                                var roundOption = i + '. kolejka - ' + selectedLeagueName;
                                var option = $('<option></option>').val(roundOption).text(roundOption);

                                // Jeśli wartość opcji jest równa wartości z kolumny 'round' dla edytowanego meczu, ustaw ją jako wybraną
                                if (roundOption === roundValue) {
                                    option.attr('selected', 'selected');
                                }

                                roundSelectElement.append(option);
                                console.log(roundValue);
                            }
                        }
                    }
                },
            });
        }

        function disableSelectedTeams() {
            var team1SelectedValue = $('#team1_id_edit_' + gameId).val();
            var team2SelectedValue = $('#team2_id_edit_' + gameId).val();

            // Włącz wszystkie opcje w obu selectach
            $('#team1_id_edit_' + gameId + ' option, #team2_id_edit_' + gameId + ' option').prop('disabled', false);

            // Wyłącz opcje w drugim selectcie, które są już wybrane w pierwszym selectcie
            if (team1SelectedValue) {
                $('#team2_id_edit_' + gameId + ' option[value="' + team1SelectedValue + '"]').prop('disabled', true);
            }

            // Wyłącz opcje w pierwszym selectcie, które są już wybrane w drugim selectcie
            if (team2SelectedValue) {
                $('#team1_id_edit_' + gameId + ' option[value="' + team2SelectedValue + '"]').prop('disabled', true);
            }
        }

        // Wywołaj funkcję disableSelectedTeams przy wyborze wartości w selectach team1_id_edit i team2_id_edit
        $('#team1_id_edit_' + gameId + ', #team2_id_edit_' + gameId).on('change', function() {
            disableSelectedTeams();
        });
    }

    $(document).ready(function() {
        @foreach($games as $game)
        var leagueEditElement = $('#league_id_edit_' + {{$game->id}});

        if (leagueEditElement.length) {
            leagueEditElement.on('change', function() {
                handleLeagueEditChange({{$game->id}}, false);
            });
        }
        @endforeach
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
            confirmButtonText: 'Tak, usuń!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('game.delete')}}",
                    type: "GET",
                    dataType: 'json',
                    data: {
                        event_id: $(this).attr('id'),
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Mecz został usunięty!",
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


<script src="js/admin.js"></script>

</body>

</html>


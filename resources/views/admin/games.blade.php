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

<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div> <a href="#" class="nav_logo"> <i class='bx bx-football nav_logo-icon'></i> <span
                    class="nav_logo-name">MatchMaster</span> </a>
            <div class="nav_list"> <a href="/dashboard" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i>
                    <span class="nav_name">Dashboard</span> </a> <a href="/players" class="nav_link"> <i
                        class='bx bx-user nav_icon'></i> <span class="nav_name">Zawodnicy</span> </a> <a href="" class="nav_link active"> <i class='bx bxs-grid nav_icon'></i> <span
                        class="nav_name">Drużyny</span> </a> <a href="" class="nav_link"> <i
                        class='bx bx-bookmark nav_icon'></i> <span class="nav_name">Bookmark</span> </a> <a
                    href="#" class="nav_link"> <i class='bx bx-folder nav_icon'></i> <span
                        class="nav_name">Files</span> </a>
                <a href="#" class="nav_link"> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span
                        class="nav_name">Stats</span> </a>
            </div>
        </div> <a href="{{ route('logout') }}" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span
                class="nav_name">SignOut</span> </a>
    </nav>
</div>

<div id="content" style="padding-top:5rem; height:100%;">

    <div class="contenthead" style="display:flex; justify-content:space-between; margin-bottom:1rem;">
        <h3 style="padding-right:2rem;">Mecze</h3>

        <!-- Button trigger modal -->

        <button type="button" class="btn btn-outline-primary btn-rounded" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop" style="height: 45px;width: 170px;">
            Dodaj Mecz
        </button>


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

<table id="example" class="table table-striped" style="width:100%">
    <thead>
    <tr>
        <th>No</th>
        <th>Klub</th>
        <th>Klub</th>
        <th>Liga</th>
        <th>Termin</th>
        <th>Wynik Gospodarzy</th>
        <th>Wynik Gościi</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($games as $game)
        @php
            $localizedDate = \Carbon\Carbon::parse($game->start_time)->locale('pl_PL');
        @endphp
        <tr data-entry-id="{{ $game->id }}">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $game->team1->name }}</td>
            <td>{{ $game->team2->name }}</td>
            <td>{{ $game->league->name }}</td>
            <td>{{ $localizedDate->isoFormat('D MMMM YYYY') }}</td>
            <td>{{ $game->result1 }}</td>
            <td>{{ $game->result2 }}</td>
            <td>
                <a data-bs-toggle="modal" data-bs-target="#edit_game_{{ $game->id }}">
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
                    <div class="col-md-6">
                        <div class="form">
                            <select id="league_id" name="league_id" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" onchange="handleLeagueChange(this)" required>
                                <option value="" selected>-- Wybierz Ligę --</option>
                                @foreach($leagues as $id => $league)
                                    <option value="{{ $id }}">{{ $league }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
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
    <div class="modal fade" id="edit_game_{{ $game->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                        <div class="col-md-6">
                            <div class="form">
                                <select name="team1_id" id="team1_id" class="form-control selectpicker border rounded border-1" data-live-search="true" data-width="100%" placeholder="Gospodarze" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Wybierz Klub')" required>
                                    @foreach($teams as $id => $team)
                                        <option value="{{ $id }}" {{ (in_array($id, old('team', [])) || isset($game) && $game->team1->id == $id) ? 'selected' : '' }}>{{ $team }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form">
                                <select name="team2_id" id="team2_id" class="form-control selectpicker border rounded border-1" data-live-search="true" data-width="100%" placeholder="Goście" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Wybierz Klub')" required>
                                    @foreach($teams as $id => $team)
                                        <option value="{{ $id }}" {{ (in_array($id, old('team', [])) || isset($game) && $game->team2->id == $id) ? 'selected' : '' }}>{{ $team }}</option>
                                    @endforeach
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
                                <input type="text" class="form-control" name="result1"
                                       value="{{ old('result1', $game->result1) }}" id="result1"/>
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

{{--<script>--}}
{{--    $('.selectpicker').selectpicker({--}}
{{--        style: 'btn-bg: rgba(0,0,0,0)',--}}
{{--    });--}}
{{--</script>--}}

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

                        // Dodaj opcje kolejek do selecta
                        for (var i = 1; i <= numberOfRounds; i++) {
                            var option = document.createElement('option');
                            option.value = i;
                            option.textContent = i + '. kolejka';
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

<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('.select2').select2({
            dropdownParent: $('#staticBackdrop')
        });
    });
</script>

<script src="js/admin.js"></script>

</body>

</html>


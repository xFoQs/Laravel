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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>

        .col-12.col-md-4 {
            padding: 1px;
        }
        .tak{
            color: black;
        }

        .panel {
            height: 3rem;
            width: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: space-around;
            background-color: white;
            box-shadow: 0 0 2px 0 rgba(48, 48, 48, 0.1), 0 4px 4px 0 rgba(48, 48, 48, 0.1);
            cursor: pointer;
        }
        .panel.active {
            background-color: #386bc0;
            color: #fff;
        }
        .panel .btn {
            margin-right: 5px;
        }

        .panel.active .toggle-match .tak {
            color: #fff;
        }


        .btn-group-table td {
            padding: 1px;
            margin: 0;
            box-shadow: 0 0 2px 0 rgba(48, 48, 48, 0.1), 0 4px 4px 0 rgba(48, 48, 48, 0.1);
        }

        .btn-group{
            box-shadow: 0 0 2px 0 rgba(48, 48, 48, 0.1), 0 4px 4px 0 rgba(48, 48, 48, 0.1);
            padding: 1px;
        }
        .btn-group input[type="radio"] {
            display: none;
        }


        .btn-group-table input[type="radio"] {
            display: none;
        }

        .btn-group-table label.btn-secondary {
            background-color: white;
            color: #4f4f4f;
            width: 100%;
            padding: 12px 10px;
            text-align: center;
        }

        label.btn-secondary {
            background-color: white;
            color: #4f4f4f;
            width: 100%;
            margin: 0px;
            padding: 12px 10px;
            text-align: center;
        }

        .btn-group-table label.btn-secondary:hover {
            background-color: lightgray;
        }

        .btn-group-table label.btn-secondary input[type="radio"]:checked:before {
            opacity: 1;
        }

        .btn-group-table label.btn-secondary span {
            z-index: 1;
        }

        .btn-group-table label.btn-secondary.active {
            background-color: blue;
            color: white;
        }

        .time-input {
            width: 100%;
            padding: 10px;
        }

        .select2-selection__rendered {
            line-height: 43px !important;
        }
        .select2-container .select2-selection--single {
            height: 45px !important;
        }
        .select2-selection__arrow {
            height: 45px !important;
        }


    </style>

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

        <div><h2 style="padding-right:2rem;">Prowadzenie relacji live</h2></div>
        <!-- Button trigger modal -->
        <div>

        </div>
    </div>


</div>

<br>
<div style="width: 100%;">
    <div class="row" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 15px;">
        @foreach ($games as $key => $game)
            <div class="col-12 col-md-4">
                <div class="panel" data-game-id="{{ $game->id }}">
                    <div style="width: 70%;"><span style="font-size: 14px;">{{ $game->team1->name }} -- {{ $game->team2->name }}</span></div>
                    <div><a class="toggle-match" data-game-id="{{ $game->id }}">
                            <span class="tak" style="font-size: 14px;">Usuń</span>
                        </a></div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div id="game-data-container" style="width: 100%; display: flex; justify-content: center;">
    <!-- Tutaj zostaną wyświetlone dane meczu -->
</div>

<div style="display: flex; padding-top: 3rem; justify-content: space-between; padding-bottom: 15rem;">
    <div>
        <form id="goal-form" action="{{ route('goals.store') }}" method="POST">
            @csrf
            <input type="hidden" name="game_id" value="">
            <div id="form-section" style="display: none; align-content: center; padding: 1px; margin: 1px; padding-left: 2rem;">
                <table class="btn-group btn-group-table" data-toggle="buttons">
                    <tr>
                        <td>
                            <label class="btn btn-secondary">
                                <input type="radio" name="options" value="1">
                                <span>Informacje</span>
                            </label>
                        </td>
                        <td>
                            <label class="btn btn-secondary">
                                <input type="radio" name="options" value="2">
                                <span>Bramka</span>
                            </label>
                        </td>
                        <td>
                            <label class="btn btn-secondary">
                                <input type="radio" name="options" value="3">
                                <span>Żółta kartka</span>
                            </label>
                        </td>
                        <td>
                            <label class="btn btn-secondary">
                                <input type="radio" name="options" value="4">
                                <span>2x Żółta kartka</span>
                            </label>
                        </td>
                        <td>
                            <label class="btn btn-secondary">
                                <input type="radio" name="options" value="5">
                                <span>Niewykorzystany rzut karny</span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <label class="btn btn-secondary" style="width: 100%;">
                                <input type="radio" name="options" value="6">
                                <span>Bramka samobójcza</span>
                            </label>
                        </td>
                        <td colspan="3">
                            <label class="btn btn-secondary" style="width: 100%;">
                                <input type="radio" name="options" value="7">
                                <span>Zmiana</span>
                            </label>
                        </td>
                    </tr>
                </table>

                <div id="team_action" style="padding-top: 1rem; display: none;">
                    <div class="input-group">
                        <label class="form-check-label" style="display: block; margin-bottom: 0.5rem;">
                            <input type="radio" class="form-check-input time-input" name="team" value="" required>
                        </label>
                        <label class="form-check-label" style="display: block;">
                            <input type="radio" class="form-check-input time-input" name="team" value="" required>
                        </label>
                    </div>
                </div>

                <div class="time-input-container" style="padding-top: 1rem;">
                    <input type="text" id="minute_input" class="time-input" name="minute" placeholder="Minuta" value="" required>
                </div>

                <div class="input-group" style="margin-top: 1rem; padding-bottom: 1rem;">
                    <select id="player_select" class="form-control" name="player_id">
                        <option></option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Wyślij</button>
            </div>
        </form>

        </div>

    <div id="preview-section" style="display: none; align-content: center;">
        <h1>Podgląd</h1>

    </div>

    <div>

        </div>
    </div>
</div>



</div>

<script src="js/admin.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        var activeGameId;
        $('.panel').click(function() {
            $('.panel').removeClass('active');
            $(this).addClass('active');

            activeGameId = $(this).data('game-id');

            // Aktualizuj dane w divie "game-data-container"
            updateGameData(activeGameId);

            $('#form-section').show();
            $('#preview-section').show();

            // Sprawdź, czy opcja radio z drużyną została zaznaczona po zmianie panelu
        });

        function updateGameData(activeGameId) {
            // Pobierz dane meczu z serwera
            $.ajax({
                url: '/admin/livegame/' + activeGameId, // Endpoint do pobrania danych meczu
                method: 'GET',
                data: { activeGameId: activeGameId },
                success: function(response) {
                    // Zaktualizuj zawartość diva "game-data-container" na podstawie odpowiedzi z serwera
                    var gameDataContainer = $('#game-data-container');
                    $('input[name="game_id"]').val(activeGameId);
                    gameDataContainer.html(`
                    <div style="padding-top: 3rem; display: flex; align-items: center; justify-content: center;">
                        <div class="match" style="width: 90%;">
                            <div class="match-header">
                                <h2 class="match-tournament">${response.league}   (${response.season})</h2>
                                <span class="match-tournament">${response.round}</span>
                            </div>
                            <div class="match-content">
                                <div class="column">
                                    <div class="team">
                                        <h2 class="team-name">${response.team1Name}</h2>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="match-details">
                                        <div class="match-score">
                                            <span class="match-score-number">${response.result1 !== null ? response.result1 : '0'}</span>
                                            <span class="match-score-divider">:</span>
                                            <span class="match-score-number">${response.result2 !== null ? response.result2 : '0'}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="team">
                                        <div class="team-logo">
                                            <h2 class="team-name">${response.team2Name}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                    var previewSection = $('#preview-section');
                    var previewContent = '';

                    response.goals.sort(function(a, b) {
                        return b.minute - a.minute;
                    });

                    response.goals.forEach(function(goal) {
                        var teamName = goal.team_id === response.team1Id ? response.team1Name : response.team2Name;
                        var playerName = goal.player && goal.player.name ? goal.player.name : 'Nieznany';
                        var playerSurname = goal.player && goal.player.surname ? goal.player.surname : 'Strzelec';
                        var minute = goal.minute !== null ? goal.minute : '';
                        var goalId = goal.id;

                        previewContent += `
                        <div class="preview" style="padding: 4px; display: flex; justify-content: space-between;">
                            <div style="padding-right:4rem;">
                                <span style="font-size: 16px; font-weight: bold">${minute}'</span>
                                <span class="badge badge-primary" style="font-size: 14px;">Bramka</span> w drużynie
                                <span class="badge badge-secondary" style="font-size: 14px;">${teamName}</span> dla
                                <span class="badge badge-primary" style="font-size: 14px;">${playerName} ${playerSurname}</span>
                            </div>
                            <button class="btn btn-danger btn-sm" data-goal-id="${goalId}" onclick="deleteGoal(this)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    `;
                    });

                    previewSection.html(previewContent);
                    previewSection.show();

                    var gameSelect = $('#team_action');
                    gameSelect.html(`
                    <div class="input-group" style="display: flex; justify-content: space-around;">
                        <label class="form-check-label" style="display: block; margin-bottom: 0.5rem;">
                            <input type="radio" class="form-check-input time-input" name="team" value="${response.team1Id}" required>
                            ${response.team1Name}
                        </label>
                        <label class="form-check-label" style="display: block;">
                            <input type="radio" class="form-check-input time-input" name="team" value="${response.team2Id}" required>
                            ${response.team2Name}
                        </label>
                    </div>
                `);

                    var selectedTeamRadio = $('input[name="team"]:checked');
                    if (selectedTeamRadio.length === 0) {
                        var playerSelect = $('#player_select');
                        console.log("kurwo");
                        playerSelect.empty(); // Wyczyść Select2 przed dodaniem nowych opcji
                    }
                    // Jeśli wybrany jest klub, załaduj zawodników z wybranego klubu do Select2
                    var selectedTeam = $('input[name="team"]:checked').val();
                    var selectedSeason = response.seasonID; // Pobierz seasonID z odpowiedzi
                    console.log(selectedTeam);
                    if (selectedTeam) {
                        loadPlayers(selectedTeam, selectedSeason); // Przekazanie selectedSeason jako drugi argument
                    }

                    // Obsługa zdarzenia zmiany wybranego klubu
                    $('input[name="team"]').change(function() {
                        var selectedTeam = $(this).val();
                        loadPlayers(selectedTeam, selectedSeason); // Przekazanie selectedSeason jako drugi argument
                    });

                    // Funkcja do ładowania zawodników z wybranego klubu
                    function loadPlayers(teamId, seasonId) {
                        $.ajax({
                            url: '/players/' + teamId + '/' + seasonId, // Ścieżka do endpointu, który zwraca zawodników danego klubu i sezonu
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                var playerSelect = $('#player_select');
                                playerSelect.empty(); // Wyczyść Select2 przed dodaniem nowych opcji

                                // Dodaj opcję "Wybierz zawodnika" jako domyślną opcję wybraną
                                var defaultOption = new Option('Wybierz zawodnika', '');
                                playerSelect.append(defaultOption);

                                // Dodaj nowe opcje do Select2 na podstawie danych zawodników
                                $.each(response.players, function(index, player) {
                                    var option = new Option(player.name + ' ' + player.surname, player.id);
                                    playerSelect.append(option);
                                });

                                // Zaktualizuj Select2
                                playerSelect.val('').trigger('change');
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    }

                    // Inicjalizacja Select2 dla zawodników
                    $('#player_select').select2({
                        placeholder: 'Wybierz zawodnika',
                        allowClear: true
                    });
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });

        }

        $('.toggle-match').click(function() {
            var gameId = $(this).data('game-id');
            var storedGames = JSON.parse(getCookie('selectedGames')) || [];

            var index = storedGames.indexOf(gameId);
            if (index !== -1) {
                // Usuń z pamięci podręcznej
                storedGames.splice(index, 1);

                // Zapisz zmiany w pliku cookie
                setCookie('selectedGames', JSON.stringify(storedGames));

                location.reload();

                // Usuń sekcję HTML dla usuniętej gry
                var removedSection = $(this).closest('.col-12');
                var wasActive = removedSection.hasClass('active');
                removedSection.fadeOut(400, function() {
                    removedSection.remove();

                    if (wasActive && storedGames.length > 0) {
                        // Sprawdź czy usunięte id jest aktywne
                        var activeGameId = $('.panel.active').data('game-id');
                        if (!activeGameId || storedGames.indexOf(activeGameId) === -1) {
                            // Wyszukaj pierwsze aktywne id gry
                            activeGameId = storedGames[0];
                            $('.panel[data-game-id="' + activeGameId + '"]').addClass('active');
                        }

                        // Zaktualizuj dane w divie "game-data-container"
                        updateGameData(activeGameId);
                    } else if (wasActive && storedGames.length === 0) {
                        $('#game-data-container').html('<div class="no-game-selected">Brak wybranych meczów wróć na listę meczów i dodaj mecze do relacjonowania za pomocą ikonki plusa</div>');
                    }
                });
            }
        });

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

        var form = document.getElementById('goal-form');

        // Nasłuchuj zdarzenie przesłania formularza
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Zapobiegaj domyślnemu zachowaniu formularza (przeładowaniu strony)

            // Utwórz obiekt FormData z danymi formularza
            var formData = new FormData(form);

            // Wyślij żądanie AJAX za pomocą metody fetch
            fetch('/goals', {
                method: 'POST',
                body: formData
            })
                .then(function(response) {
                    if (response.ok) {
                        var minuteInput = document.getElementById('minute_input');
                        minuteInput.value = '';
                        updateGameData(activeGameId);

                        // Przykład: Wyświetl komunikat o sukcesie
                        var successMessage = document.getElementById('successMessage');
                        successMessage.textContent = 'Dane zostały zapisane.';
                        successMessage.style.display = 'block';
                    } else {
                        // Żądanie zakończone błędem
                        console.error('Błąd żądania AJAX:', response.status);
                    }
                })
                .catch(function(error) {
                    // Błąd w trakcie wykonywania żądania
                    console.error('Błąd żądania AJAX:', error);
                });
        });


        function deleteGoal(button) {
            var goalId = button.getAttribute('data-goal-id');

            // Wykonaj żądanie AJAX do usunięcia bramki
            $.ajax({
                url: '/delete-goal/' + goalId,  // Zmień ścieżkę URL na odpowiednią dla twojej aplikacji
                type: 'GET',  // Lub użyj metody 'DELETE', jeśli serwer obsługuje taką metodę
                success: function(response) {

                    updateGameData(activeGameId);

                    // Sprawdź, czy goalId istnieje w gameData.goals
                    var goalIndex = gameData.goals.findIndex(function(goal) {
                        return goal.id === goalId;
                    });

                    if (goalIndex !== -1) {
                        // Usuń bramkę z gameData.goals
                        gameData.goals.splice(goalIndex, 1);

                        // Zaktualizuj wynik w zależności od drużyny, której dotyczyła bramka
                        if (gameData.team1Id === gameData.goals[goalIndex].team_id) {
                            gameData.result1--;
                        } else {
                            gameData.result2--;
                        }

                        // Tutaj możesz wykonać dodatkowe operacje po usunięciu bramki

                        // Zaktualizuj widok HTML na podstawie zaktualizowanych danych
                        updatePreviewSection();
                        updateGameData();
                    }
                },
                error: function(xhr, status, error) {
                    // Obsłuż błąd, jeśli wystąpił
                    console.log(error);
                }
            });
        }
        $(document).on('click', '.btn-danger', function() {
            deleteGoal(this);
        });
    });
</script>

<script>
    const labels = document.querySelectorAll('.btn-group-table label.btn-secondary');

    labels.forEach((label) => {
        label.addEventListener('click', () => {
            labels.forEach((otherLabel) => {
                otherLabel.classList.remove('active');
            });

            label.classList.add('active');
        });
    });

</script>


<script>
    $(document).ready(function() {
        // ...

        $('.btn-group-table input[type="radio"]').change(function() {
            var selectedOption = $('.btn-group-table input[type="radio"]:checked').val();

            if (selectedOption === '2' || selectedOption === '3' || selectedOption === '4' || selectedOption === '5' || selectedOption === '6') {
                $('#team_action').show();
            } else {
                $('#team_action').hide();
            }
        });


        $('input[name="team"]').change(function() {
            var selectedTeam = $(this).val();
            if (selectedTeam) {
                $('#player_select').show(); // Pokaż pole wyboru
            } else {
                $('#player_select').hide(); // Ukryj pole wyboru
            }
        });

        // ...
    });
</script>


</body>

</html>

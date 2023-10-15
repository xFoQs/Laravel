<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Game Data</title>

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
        .timeline-wrapper {
            background-color: rgb(255, 255, 255);
            padding: 20px 0;
            box-shadow: 0 0 2px 0 rgba(48, 48, 48, 0.1), 0 4px 4px 0 rgba(48, 48, 48, 0.1);
            /*   border-top: 0; */
        }

        .timeline {
            position: relative;
            padding: 30px 0;
        }

        .timeline:before {
            content: " ";
            height: 100%;
            display: block;
            border-right: 1px dashed rgba(0, 0, 0, 0.2);
            position: absolute;
            top: 0;
            width: 1px;
            left: calc(50% - 3px);
        }

        .timeline__point {
            position: absolute;
            width: 5px;
            height: 5px;
            border: 1px solid black;
            border-radius: 100px;
            left: calc(50% - 5px);
        }

        .timeline__point--start {
            top: 0;
        }

        .timeline__point--end {
            bottom: 0;
        }

        .timeline__time {
            text-align: center;
        }

        .timeline__item {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 0;
        }

        .timeline__item-event {
            flex-basis: 240px;
            font-size: 14px;
        }

        .timeline__item-time {
            flex-basis: 40px;
            text-align: center;
            border-radius: 20%;
            background-color: #fbfbfb;
            box-shadow: 0 0 2px 0 rgba(48, 48, 48, 0.1), 0 4px 4px 0 rgba(48, 48, 48, 0.1);
            z-index: 100;
        }

        .timeline__item-event--home {
            text-align: right;
            padding: 0 20px;
        }

        .timeline__item-event--away {
            text-align: left;
            padding: 0 20px;
        }

    </style>

</head>
<body>
<div id="game-data-container" style="width: 100%; display: flex; justify-content: center;">


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
    // Funkcja, która pobierze i wyświetli dane dla określonego game_id za pomocą AJAX
    function updateGameData(gameId) {
        // Pobierz dane meczu z serwera za pomocą AJAX
        $.ajax({
            url: '/gamedata/' + gameId, // Endpoint do pobrania danych meczu
            method: 'GET',
            success: function(response) {
                // Zaktualizuj zawartość diva "game-data-container" na podstawie odpowiedzi z serwera
                var gameDataContainer = $('#game-data-container');
                // Tworzymy kontener zawierający wszystkie dane meczu na podstawie odpowiedzi z serwera
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
                                        <div class="team-logo">
                                            <img src="/img/${response.team1Logo !== null ? response.team1Logo : 'brak.webp'}" alt="Team 1 Logo" style="width: 90px; height: 100px;">
                                        </div>
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
                                        <div class="match-status">${response.status}</div>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="team">
                                        <div class="team-logo">
                                            <img src="/img/${response.team2Logo !== null ? response.team2Logo : 'brak.webp'}" alt="Team 2 Logo" style="width: 80px; height: 90px;">
                                        </div>
                                        <h2 class="team-name">${response.team2Name}</h2>
                                    </div>
                                </div>

                            </div>
<div class="timeline-wrapper">
        <div class="timeline">

        </div>
    </div>
                        </div>
                    </div>
                `);

                generateTimeline(
                    response,
                    response.goals,
                    response.yellowCards,
                    response.yellowCards2,
                    response.missedpenalty,
                    response.suicidegoals,
                    response.change
                );


                function generateTimeline(response, goals, yellowCards, yellowCards2, missedPenalties, suicideGoals, changes) {
                    var events = goals.concat(yellowCards, yellowCards2, missedPenalties, suicideGoals, changes);

                    // Sortujemy wydarzenia chronologicznie względem minuty
                    events.sort(function(a, b) {
                        return a.minute - b.minute;
                    });

                    var timeline = $('.timeline'); // Wybieramy element o klasie 'timeline'

                    // Czyszczenie wcześniejszych wydarzeń z timeline
                    timeline.empty();

                    // Dodajemy wydarzenia do timeline
                    events.forEach(function(event) {
                        var eventType = '';

                        if (goals.includes(event)) {
                            eventType = '<img src="https://polskieligi.net/images/goal.png" alt="Bramka" style="width: 16px; height: 16px;"> <span style="line-height: 1; font-size: 12px; color: #b2b2b2;">Bramka</span>';
                        } else if (yellowCards.includes(event)) {
                            eventType = '<img src="https://polskieligi.net/images/yellow.png" alt="ZółtaKartka" style="width: 16px; height: 16px;"> <span style="line-height: 1; font-size: 12px; color: #b2b2b2;">Zółta kartka</span>';
                        } else if (yellowCards2.includes(event)) {
                            eventType = '<img src="https://polskieligi.net/images/red.png" alt="CzerwonaKartka" style="width: 16px; height: 16px;"> <span style="line-height: 1; font-size: 12px; color: #b2b2b2;">Czerwona kartka</span>';
                        } else if (missedPenalties.includes(event)) {
                            eventType = '<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1f/Missed_penalty_icon.svg/16px-Missed_penalty_icon.svg.png" alt="NiestrzelonyKarny" style="width: 16px; height: 16px;"> <span style="line-height: 1; font-size: 12px; color: #b2b2b2;">Niestrzelony karny</span>';
                        } else if (suicideGoals.includes(event)) {
                            eventType = '<img src="https://polskieligi.net/images/self_goal.png" alt="BramkaSamobójcza" style="width: 16px; height: 16px;"> <span style="line-height: 1; font-size: 12px; color: #b2b2b2;">Bramka samobójcza</span>';
                        } else if (changes.includes(event)) {
                            eventType = '<img src="https://polskieligi.net/images/shuffle.png" alt="Zmiana" style="width: 16px; height: 16px;"> <span style="line-height: 1; font-size: 12px; color: #b2b2b2;">Zmiana</span>';
                        }

                        var listItem = $('<div class="timeline__item"></div>');
                        var homeEvent = $('<div class="timeline__item-event timeline__item-event--home"></div>');
                        var awayEvent = $('<div class="timeline__item-event timeline__item-event--away"></div>');
                        var eventTime = $('<div class="timeline__item-time"></div>');


                        if (eventType === 'Zmiana') {
                            listItem.addClass('timeline__item--replace');
                            homeEvent.append(`<div>${event.player1.name} <i class="fa fa-arrow-down"></i></div>`);
                            awayEvent.append(`<div>${event.player2.name} <i class="fa fa-arrow-up"></i></div>`);
                            eventTime.text(event.minute + "'");
                        } else {
                            if (eventType === 'Bramka') {
                                homeEvent.append('<div>Bramka</div>');
                            } else {
                                homeEvent.append(`<div>${eventType}</div>`);
                            }

                            if (event.player && event.player.name) {
                                homeEvent.append(`<div>${event.player.name} ${event.player.surname}</div>`);
                            }
                            eventTime.text(event.minute + "'");
                        }

// Sprawdzamy, czy team_id jest z drużyny po lewej (response.team1Id) czy prawej (response.team2Id)
                        if (event.team_id === response.team1Id) {
                            listItem.append(homeEvent);
                            listItem.append(eventTime);
                            listItem.append(awayEvent);
                        } else if (event.team_id === response.team2Id) {
                            // Tutaj zamieniamy miejscami zawartość homeEvent i awayEvent, aby przypisać poprawne klasy.
                            listItem.append(awayEvent);
                            listItem.append(eventTime);
                            listItem.append(homeEvent.css('text-align', 'left')); // Ustawienie stylu CSS text-align na left dla elementu homeEvent
                        }

                        timeline.append(listItem);
                    });
                }

            },

            error: function() {
                // Obsłuż ewentualne błędy zapytania
                console.error('Wystąpił błąd podczas pobierania danych meczu.');
            }
        });
    }


    // Pobierz ID meczu z URL
    const urlParams = new URLSearchParams(window.location.search);
    const gameId = urlParams.get('game_id');

    if (gameId) {
        updateGameData(gameId);
    }

        // Cykliczne odświeżanie co minutę (60000 milisekund)
        setInterval(function() {
            console.log('robie');
            updateGameData(gameId);
        }, 60000);

    });
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/admindashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

</head>

<body id="body-pd">

<header class="header" id="header">
    <div class="header_toggle">
        <i class='bx bx-menu' id="header-toggle"></i>
    </div>

    <div class="d-flex align-items-center justify-content-between">
        <a href="/livegame" data-bs-toggle="tooltip" data-bs-placement="top" title="Relacja live">
            <i class="fas fa-headset"></i>
            <span class="badge rounded-pill badge-notification bg-danger"><span id="matchCount"></span></span>
        </a>


        <div class="d-flex align-items-center m-1">
            <div class="vertical-line mx-3"></div>
            <b style="padding-right: 2px;">Witaj, </b> {{ auth()->user()->name }} !
        </div>
    </div>
</header>

    @extends('layouts.sidebar')


    <div id="content" style="padding-top:2rem;">
        <h3>Main Contener</h3>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        WITAJ W PANELU ADMINA, KIEDYŚ COŚ TUTAJ BĘDZIE :D
    </div>



<script src="js/admin.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"></script>


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


</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style2.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">

    <script src="https://kit.fontawesome.com/efdb8104ef.js" crossorigin="anonymous"></script>
    <title>Responsive Football Clab Page</title>
</head>
<body>
<header>
    <div class="navbar">
        <div class="container flex-container">
            <div class="hamburger">
                <div class="bar"></div>
            </div>
            <ul>
                <li><a href="#hero">Strona Główna</a></li>
                <li><a href="{{ route('test') }}">Rozgrywki</a></li>
                <li><a href="{{ route('tournament') }}">Turniej</a></li>
                <li><a href="#matches">Nadchodzące mecze</a></li>
                <li><a href="#footer">Kontakt</a></li>
            </ul>
            <div class="login">
                <a target="_blank" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i>Login</a>
            </div>
        </div>
    </div>
    <section id="hero">
        <div class="container flex-container">
            <div class="col-2">
                <h1>Stwórz <br> Własny turniej <span></span></h1>
                <p>Dołącz do zabawy</p>
                <div class="common-btn">
                    <a target="_blank" href="{{ route('tournament') }}">Typowanie<i class="fas fa-arrow-right"></i></a>
                </div>
                <p></p>
            </div>
            <div class="col-2">
                <img src="img/hero.png" alt="">
            </div>
        </div>
    </section>
</header>
<!-- ---------main-------- -->
<main>
    <section id="topPlayers">

        <div class="container">

            <div class="match-fixture" style="width: 100%; text-align: center;">
                <div class="title">
                    <h2>"Nadchodzące Gwiazdy Piłki: Zawodnicy, na Których Warto Zwrócić Uwagę" </h2>
                </div>
            </div>
            <div class="players">
                @php
                    use App\Models\Player;
                    use Illuminate\Support\Facades\DB;

                    $players = Player::select('players.id', 'players.name', 'players.surname', 'players.birth_date', DB::raw('COUNT(goals.id) as goals_count'), 'teams.name as team_name')
                        ->leftJoin('goals', 'players.id', '=', 'goals.player_id')
                        ->leftJoin('teams', 'players.team_id', '=', 'teams.id')
                        ->where('goals.season_id', 2)
                        ->groupBy('players.id', 'players.name', 'players.surname', 'players.birth_date', 'teams.name')
                        ->orderByDesc('goals_count')
                        ->take(12)
                        ->get();
                @endphp

                @foreach ($players as $player)
                    <div class="player">
                        <div class="players-img">
                            <img src="players/player-{{ $player->id }}.png" alt="">
                        </div>
                        <div class="players-info">
                            <h1>{{ $player->name }} {{ $player->surname }}  ({{ \Carbon\Carbon::parse($player->birth_date)->age }} lat)</h1>
                            <p> Zawodnik grający w zespole {{ $player->team_name }} strzelił w tym sezonie już <strong>{{ $player->goals_count }} goli</strong></p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ---------highlight-section-------- -->
    <section id="highlights">
        <div class="container flex-container">
            <div class="col-2">
                <h1>Wszystkie Mecze <br> Śledz rozgrywki piłkarskie</h1>
                <p>Witaj na naszej stronie, poświęconej pasji i rywalizacji na najniższych szczeblach polskiego futbolu! Jesteśmy dumni, że możemy dostarczyć Ci najświeższe informacje i wyniki z rozgrywek ligowych, w których błyskają młodzi zawodnicy, a miłośnicy piłki nożnej łączą swoje siły, by wesprzeć swoje lokalne drużyny.</p>
                <div class="common-btn">
                    <a target="_blank" href="{{ route('test') }}">Wyniki<i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-2">
                <img src="img/page2.jpg" alt="">
            </div>
        </div>
    </section>
    <!-- ---- -----highlight-section-------- -->

    <!-- ----------match-fixture---------- -->
    <section id="matches">
        <div class="container flex-container">
            <div class="match-fixture">
                <div class="title">
                    <h2>Nadchodzące mecze</h2>
                </div>
                <div class="table">
                    @php
                        // Obecna data
                        $now = now();

                        // Sortuj mecze po dacie rozpoczęcia
                        $sortedGames = $games->sortBy('start_time');

                        // Sprawdź, czy istnieją nadchodzące mecze w tym tygodniu
                        $hasUpcomingGames = false;
                    @endphp

                    <table>
                        @foreach ($sortedGames as $game)
                            @php
                                // Data rozpoczęcia meczu
                                $gameStartTime = \Carbon\Carbon::parse($game->start_time);

                                // Sprawdź, czy mecz jest w bieżącym tygodniu i data rozpoczęcia jest większa lub równa dzisiejszej dacie
                                if ($gameStartTime->isSameWeek($now, 'isoWeek') && $gameStartTime >= $now) {
                                    // Mecz jest w bieżącym tygodniu i jeszcze się nie rozpoczął
                                    $hasUpcomingGames = true;
                                }
                            @endphp
                        @endforeach

                        @if ($hasUpcomingGames)
                            @foreach ($sortedGames as $game)
                                @php
                                    // Data rozpoczęcia meczu
                                    $gameStartTime = \Carbon\Carbon::parse($game->start_time);

                                    // Sprawdź, czy mecz jest w bieżącym tygodniu i data rozpoczęcia jest większa lub równa dzisiejszej dacie
                                    if ($gameStartTime->isSameWeek($now, 'isoWeek') && $gameStartTime >= $now) {
                                        // Mecz jest w bieżącym tygodniu i jeszcze się nie rozpoczął
                                @endphp
                                <tr>
                                    <td>{{ $game->team1->name }}</td>
                                    <td>VS</td>
                                    <td>{{ $game->team2->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($game->start_time)->isoFormat('D MMMM YYYY H:mm') }}</td>
                                    <td>{{ $game->round }}</td>
                                    <td>
                                        <a href="game_data?game_id={{ $game->id }}" target="_blank" style="color: #00ffff;">
                                            <strong> Zobacz więcej </strong>
                                        </a>
                                    </td>
                                </tr>
                                @php
                                    }
                                @endphp
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">Brak nadchodzących meczy</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- ----------match-fixture---------- -->
    <section id="recent-tweets">
        <div class="title">
            <h2>Recent Tweets</h2>
        </div>
        <div class="container">
            <div class="flex-container">
                <div class="col-3">
                    <div class="tweetBody">
                        <div class="tweet-title">
                            <h3>Lionel Messi</h3>
                            <i class="fas fa-share"></i>
                        </div>
                        <p>Reaching this historic milestone of 644 goals with the same club gives me a lot of joy, but what is really more important is being able to give something back to the kids struggling with their health.
                        </p>
                    </div>
                    <hr>
                    <div class="bottom">
                        <div class="name">
                            <p>@liomessi</p>
                            <small>3 july, 2021</small>
                        </div>
                        <i class="fab fa-twitter"></i>
                    </div>
                </div>
                <div class="col-3">
                    <div class="tweetBody">
                        <div class="tweet-title">
                            <h3>Neymar Jr</h3>
                            <i class="fas fa-share"></i>
                        </div>
                        <p>Reaching this historic milestone of 644 goals with the same club gives me a lot of joy, but what is really more important is being able to give something back to the kids struggling with their health.
                        </p>
                    </div>
                    <hr>
                    <div class="bottom">
                        <div class="name">
                            <p>@neimarjr</p>
                            <small>11 july, 2021</small>
                        </div>
                        <i class="fab fa-twitter"></i>
                    </div>
                </div>
                <div class="col-3">
                    <div class="tweetBody">
                        <div class="tweet-title">
                            <h3>Cristiano Ronaldo</h3>
                            <i class="fas fa-share"></i>
                        </div>
                        <p>Reaching this historic milestone of 644 goals with the same club gives me a lot of joy, but what is really more important is being able to give something back to the kids struggling with their health.
                        </p>
                    </div>
                    <hr>
                    <div class="bottom">
                        <div class="name">
                            <p>@ronaldo</p>
                            <small>21 july, 2021</small>
                        </div>
                        <i class="fab fa-twitter"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- ---------main-------- -->

<!-- ------footer------- -->
<footer id="footer">
    <div class="container">
        <img src="images/Logo2.png" alt="">
        <div class="social">
            <a target="_blank" href="https://www.facebook.com/khayrulislam.66/"><i class="fab fa-facebook-f"></i></a>
            <a href="#"></a><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-google-plus-g"></i></a>
            <a href="#"><i class="fab fa-whatsapp"></i></a>
        </div>
        <p>Copyright &copy; 2023 by </p>
    </div>
</footer>
<!-- ------footer------- -->

<script>
    const hamburger = document.querySelector('.hamburger');
    const menu = document.querySelector('.navbar ul')
    hamburger.addEventListener('click', ()=>{
        menu.classList.toggle('showMenu')
        hamburger.classList.toggle('close')
    })
    const navbar = document.querySelector('header .navbar');
    document.addEventListener('scroll', ()=>{
        var navbar_under = window.scrollY
        if(navbar_under > 250){
            navbar.classList.add('showBg')
        }else
        {
            navbar.classList.remove('showBg')
        }
    })
</script>
</body>
</html>

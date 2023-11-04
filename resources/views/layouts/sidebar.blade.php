<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div> <a href="#" class="nav_logo"> <i class='bx bx-football nav_logo-icon'></i> <span
                    class="nav_logo-name">PolskaPiłka</span> </a>
            <div class="nav_list"> <a href="/dashboard" class="nav_link"> <i class='bx bx-home-alt nav_icon'></i>
                    <span class="nav_name">Dashboard</span> </a> <a href="/players" class="nav_link"> <i
                        class='bx bx-user nav_icon'></i> <span class="nav_name">Zawodnicy</span> </a> <a href="/games" class="nav_link"> <i class='bx bxs-grid nav_icon'></i> <span
                        class="nav_name">Mecze</span> </a> <a href="/teams" class="nav_link"> <i
                        class='bx bx-bookmark nav_icon'></i> <span class="nav_name">Drużyny</span> </a> <a
                    href="/seasons" class="nav_link"> <i class='bx bx-calendar nav_icon'></i> <span
                        class="nav_name">Sezony</span> </a>
                <a href="/leagues" class="nav_link"> <i class='bx bxs-right-arrow-square nav_icon'></i> <span
                        class="nav_name">Ligi</span> </a>
                @auth
                    @php
                        $user = Auth::user(); // Pobranie zalogowanego użytkownika
                    @endphp
                    @if ($user->role_id == 1)
                        <a href="/users" class="nav_link"> <i class='bx bx-user-pin nav_icon'></i> <span class="nav_name">Użytkownicy</span> </a>
                    @endif
                @endauth
            </div>
        </div> <a href="{{ route('page') }}" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span
                class="nav_name">Wyloguj</span> </a>
    </nav>
</div>

@yield('content')

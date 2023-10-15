<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div> <a href="#" class="nav_logo"> <i class='bx bx-football nav_logo-icon'></i> <span
                    class="nav_logo-name">MatchMaster</span> </a>
            <div class="nav_list"> <a href="/dashboard" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i>
                    <span class="nav_name">Dashboard</span> </a> <a href="/players" class="nav_link"> <i
                        class='bx bx-user nav_icon'></i> <span class="nav_name">Zawodnicy</span> </a> <a href="/games" class="nav_link"> <i class='bx bxs-grid nav_icon'></i> <span
                        class="nav_name">Mecze</span> </a> <a href="/teams" class="nav_link"> <i
                        class='bx bx-bookmark nav_icon'></i> <span class="nav_name">Drużyny</span> </a> <a
                    href="#" class="nav_link"> <i class='bx bx-folder nav_icon'></i> <span
                        class="nav_name">Files</span> </a>
                <a href="#" class="nav_link"> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span
                        class="nav_name">Stats</span> </a>
            </div>
        </div> <a href="{{ route('page') }}" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span
                class="nav_name">SignOut</span> </a>
    </nav>
</div>

@yield('content')

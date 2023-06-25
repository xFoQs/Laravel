<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/admindashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

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
                <div class="nav_list"> <a href="/" class="nav_link active"> <i
                            class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name ">Dashboard</span> </a> <a href="/players" class="nav_link"> <i
                            class='bx bx-user nav_icon'></i> <span class="nav_name">Zawodnicy</span> </a> <a href="/games"
                        class="nav_link"> <i class='bx bxs-grid nav_icon'></i> <span
                            class="nav_name">Mecze</span> </a> <a href="/teams" class="nav_link"> <i
                            class='bx bx-bookmark nav_icon'></i> <span class="nav_name">Drużyny</span> </a> <a
                        href="#" class="nav_link"> <i class='bx bx-folder nav_icon'></i> <span
                            class="nav_name">Files</span> </a>
                    <a href="#" class="nav_link"> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span
                            class="nav_name">Stats</span> </a>
                </div>
            </div> <a href="{{ route('logout') }}" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span
                    class="nav_name">SignOut</span> </a>
        </nav>
    </div>

    <div id="content" style="padding-top:2rem;">
        <h3>Main Contener</h3>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        You are Logged In
    </div>

    <script src="js/admin.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"></script>

</body>

</html>

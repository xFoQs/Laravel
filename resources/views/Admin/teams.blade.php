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
            <div class="nav_list"> <a href="/dashboard" class="nav_link"> <i
                        class='bx bx-grid-alt nav_icon'></i>
                    <span class="nav_name ">Dashboard</span> </a> <a href="/players" class="nav_link"> <i
                        class='bx bx-user nav_icon'></i> <span class="nav_name">Zawodnicy</span> </a> <a href="#" class="nav_link active">
                    <i class='bx bxs-grid nav_icon'></i> <span
                        class="nav_name">Drużyny</span> </a> <a href="/" class="nav_link"> <i
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

<div id="content" style="padding-top:5rem;">

    <div id="content" style="padding-top:5rem; height:100%;">

        <div class="contenthead" style="display:flex; justify-content:space-between; margin-bottom:1rem;">
            <h3 style="padding-right:2rem;">Mecze</h3>

            <!-- Button trigger modal -->

            <button type="button" class="btn btn-outline-primary btn-rounded" data-bs-toggle="modal"
                    data-bs-target="#staticBackdrop" style="height: 45px;width: 170px;">
                Dodaj Mecz
            </button>


        </div>


    </div>

    <br>

    <table id="example" class="table table-striped" style="width:100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Club name</th>
            <th>Club name</th>
            <th>score1</th>
            <th>score2</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($games as $game)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $game->club_name }}</td>
                <td>{{ $game->club_name}}</td>
                <td>{{ $game->result1 }}</td>
                <td>{{ $game->result2 }}</td>
                <td>
                    <a data-bs-toggle="modal" data-bs-target="#"><i
                            class="fa-solid fa-pen-to-square" style="color:#4f4f4f; padding-right: 0.5rem;"></i></a>
                    <a href="#" class="delete" id=""><i class="fa-solid fa-trash"
                                                                         style="color:#4f4f4f;"></i></a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

</div>

<script src="js/admin.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>

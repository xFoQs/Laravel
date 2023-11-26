<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        rel="stylesheet"
    />
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        rel="stylesheet"
    />
    <!-- MDB -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css"
        rel="stylesheet"
    />

    <title>Games</title>


    <style>

        ul.navbar-nav {
            display: flex;
            column-gap: 5rem;
            background-color: #3498db;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 10px; /* Dodaj padding do zwiększenia rozmiaru */
            margin: 0; /* Resetuj margines */
        }

        ul.navbar-nav li {
            list-style: none;
        }

        ul.navbar-nav li a {
            text-decoration: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }


    </style>

</head>

<body>
<ul style="column-gap: 5rem;" class="bg-primary navbar-nav d-flex justify-content-center flex-row align-items-center">
    <li class="nav-item">
        <a class="nav-link text-white" href="{{ route('page') }}">Strona główna</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-white" href="{{ route('test') }}">Terminarz</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-white" href="{{ route('test3') }}">Zawodnicy</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-white" href="{{ route('test2') }}">Tabele Punktowe</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-white" href="{{ route('test4') }}">Statystyki</a>
    </li>
</ul>
<div class="container">

    @yield('content')
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

-->

<!-- MDB -->
<script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"
></script>
</body>
</html>

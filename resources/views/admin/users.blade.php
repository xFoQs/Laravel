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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchpanes/2.1.2/css/searchPanes.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.2/css/select.bootstrap4.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css" rel="stylesheet" />

    <!-- Sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>

        div.card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            padding: 20px;
        }
    </style>

    <style>
        div.dataTables_wrapper div.dataTables_filter {
            text-align: left;
        }
    </style>

    <style>

        #example_filter input[type="search"] {
            width: 100%; /* Ustaw szerokość pola wyszukiwania na 100% szerokości elementu example_filter */
            height: 40px;
        }
    </style>

    <style>
        .nav-item.dropdown {
            position: relative;
        }

        .nav-item.dropdown .dropdown-toggle {
            cursor: pointer;
        }

        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
        }

        .nav-item.dropdown .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 999;
        }
    </style>

</head>

<body id="body-pd">

<header class="header" id="header">
    <div class="header_toggle">
        <i class='bx bx-menu' id="header-toggle"></i>
    </div>


    <div class="d-flex align-items-center justify-content-between">

        <a href="/livegame" data-bs-toggle="tooltip" data-bs-placement="top" title="Relacja live" style="padding-right: 1.5rem;">
            <i class="fas fa-headset"></i>
            <span class="badge rounded-pill badge-notification bg-danger"><span id="matchCount"></span></span>
        </a>

        <ul class="navbar-nav">
            <!-- Avatar -->
            <li class="nav-item dropdown">
                <div class="dropdown-toggle d-flex align-items-center">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img (31).webp" class="rounded-circle"
                         height="22" alt="Avatar" loading="lazy" />
                </div>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#myProfileModal">Mój Profil</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#passwordResetModal">Zmień Hasło</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('page') }}">Wyloguj</a>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="d-flex align-items-center m-1">
            <div class="vertical-line mx-3"></div>
            <b style="padding-right: 2px;">Witaj, </b> {{ auth()->user()->name }} !
        </div>

    </div>
</header>

@extends('layouts.sidebar')

<div id="content" style="padding-top:6rem; height:100%;">

    <div class="card">

        <div class="contenthead" style="display: flex; align-items: stretch; margin-bottom: 1rem; margin-top: 2rem; align-content: center;">
            <h1 style="margin-right: 1rem;">Użytkownicy</h1>

        <!-- Button trigger modal -->

        <button type="button" class="btn btn-success btn-rounded" onclick="window.open('{{ route('register') }}', '_blank')" style="height: 50px; width: 50px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
            <i class="fas fa-user-plus" style="font-size: 20px;"></i>
        </button>


    </div>

        <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
            <thead class="table bg-primary" style="color: white;">
            <tr>
                <th>#</th>
                <th>Imię i Nazwisko</th>
                <th>Email</th>
                <th>Rola</th>
                <th>Akcje</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)

                <tr data-entry-id="{{ $user->id }}">
                    <td class="align-middle">
                        {{ $loop->iteration }}
                    </td>
                    <td class="align-middle">
                        {{$user->name}} {{$user->surname}}
                    </td>
                    <td class="align-middle">
                        {{$user->email}}
                    </td>
                    <td class="align-middle">
                        <span class="badge bg-info">{{ $user->role->name }}</span>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edituser{{$user->id}}" title="Edycja">
                            <i class="fa-solid fa-pen-to-square" style="color:white;"></i>
                        </button>

                        <button class="btn btn-danger btn-sm delete" id="{{ $user->id }}" title="Usuń">
                            <i class="fa-solid fa-trash" style="color:white;"></i>
                        </button>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

    </div>

    @if (Session::has('success'))
        <script>
            Swal.fire(
                'Gratulacje!',
                'Dodałeś Użytkownika!',
                'success'
            )
        </script>
    @endif

    @if (Session::has('update'))
        <script>
            Swal.fire(
                'Gratulacje!',
                'Zaaktualizowałeś dane użytkownika!',
                'success'
            )
        </script>
    @endif

</div>

<br>

{{--<!-- Modal -->--}}
{{--<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"--}}
{{--     aria-labelledby="staticBackdropLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog modal-dialog-centered modal-lg">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title" id="staticBackdropLabel">Dodaj Użytkownika</h5>--}}
{{--                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                <form class="row gx-3" method="POST" action="{{ route('league.store') }}">--}}
{{--                    @csrf--}}
{{--                    <div class="col-md-12">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <div class="form">--}}
{{--                                <input type="text" class="form-control" name="name"--}}
{{--                                       value="" required placeholder="Podaj nazwę Ligi" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Podaj nazwę ligi')" />--}}
{{--                                <label for="validationCustom01" class="form-label"></label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="col-12">--}}
{{--                        <!-- <button class="btn btn-primary" type="submit">Submit form</button> -->--}}
{{--                        <div class="modal-footer">--}}
{{--                            <button type="button" class="btn btn-secondary"--}}
{{--                                    data-bs-dismiss="modal">Zamknij</button>--}}
{{--                            <button type="submit" name="add" value="add"--}}
{{--                                    class="btn btn-primary">Wyślij</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}


<!-- Modal -->
@foreach($users as $user)
    <div class="modal edituser" id="edituser{{$user->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" data-player-id="{{$user->id}}"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edytuj Użytkownika</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row gx-3" method="POST" action="{{ route('user.updateRole', ['id' => $user->id]) }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="col-md-4">
                            <div class="form">
                                <div>
                                    <div class="mb-4 d-flex justify-content-center">
                                        <img id="preview{{$user->id}}" src="{{ $user->photo ? asset('img/' . $user->photo) : asset('img/236831.png') }}"
                                             alt="example placeholder" style="width: 180px; height: 200px;"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Imię i Nazwisko:</strong> {{ $user->name }}<span> </span>{{ $user->surname }}</li>
                                <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                                <li class="list-group-item"><strong>Rola:</strong> {{ $user->role->name }}</li>
                            </ul>
                            <label for="role" class="form-label" style="padding-top: 2rem;"><strong>Wybierz nową rolę:</strong></label>
                            <select name="role" id="role" class="form-select">
                                <option value="1" {{ $user->role_id == 1 ? 'selected' : '' }}>Administrator</option>
                                <option value="2" {{ $user->role_id == 2 ? 'selected' : '' }}>Pracownik</option>
                            </select>
                        </div>


                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                            <button type="submit" id="update" value="add" class="btn btn-primary">Wyślij</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach


@foreach($users as $user)
    <div class="modal" id="myProfileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="myProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myProfileModalLabel">Mój Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form">
                                <div>
                                    <div class="mb-4 d-flex justify-content-center">
                                        <img id="preview{{$user->id}}" src="{{ $user->photo ? asset('img/' . $user->photo) : asset('img/236831.png') }}"
                                             alt="example placeholder" style="width: 180px; height: 200px;"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Imię i Nazwisko:</strong> {{ $user->name }}<span> </span>{{ $user->surname }}</li>
                                <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                                <li class="list-group-item"><strong>Rola:</strong> {{ $user->role->name }}</li>
                            </ul>
                        </div>

                        <div class="col-12">
                            <div class="modal-footer">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

<div class="modal fade" id="passwordResetModal" tabindex="-1" aria-labelledby="passwordResetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordResetModalLabel">Zmiana hasła</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="passwordResetForm">
                    @csrf

                    <!-- Current Password input -->
                    <div class="mb-4">
                        <label for="currentPassword" class="form-label">Aktualne hasło</label>
                        <input type="password" id="currentPassword" name="current_password" class="form-control" required />
                    </div>

                    <!-- New Password input -->
                    <div class="mb-4">
                        <label for="newPassword" class="form-label">Nowe hasło</label>
                        <input type="password" id="newPassword" name="new_password" class="form-control" required />
                    </div>

                    <!-- Confirm Password input -->
                    <div class="mb-4">
                        <label for="confirmPassword" class="form-label">Potwierdź hasło</label>
                        <input type="password" id="confirmPassword" name="new_password_confirmation" class="form-control" required />
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-lg btn-block" value="Zmień hasło">Zmień hasło</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.datatables.net/searchpanes/2.1.2/js/dataTables.searchPanes.min.js"></script>
<script src="https://cdn.datatables.net/searchpanes/2.1.2/js/searchPanes.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/select/1.6.2/js/dataTables.select.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const passwordResetForm = document.getElementById("passwordResetForm");

        passwordResetForm.addEventListener("submit", function(event) {
            event.preventDefault();

            const formData = new FormData(passwordResetForm);

            fetch("/change-password", {
                method: "POST",
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.password_changed) {
                        Swal.fire({
                            title: "Success",
                            text: data.message,
                            icon: "success",
                        });

                        passwordResetForm.reset();
                    } else if (data.password_error) {
                        Swal.fire({
                            title: "Error",
                            text: data.message,
                            icon: "error",
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        });
    });
</script>

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (() => {
        'use strict';

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation');

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms).forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

<script>
    $(document).ready(function() {
        // Inicjalizacja DataTables z domyślnymi opcjami
        var table = $('#example').DataTable({
            lengthChange: false,
            language: {
                paginate: {
                    first: 'Pierwsza',
                    previous: 'Poprzednia',
                    next: 'Następna',
                    last: 'Ostatnia',
                },
                info: '',
                infoFiltered: "",
                search: '',
            },
            dom: '<"top"lf>t<"bottom"ip>',
        });

        // Znajdź pole wyszukiwania DataTables
        var searchInput = $('input[type="search"]');

        // Ustaw atrybut "placeholder" na wartość "Szukaj..."
        searchInput.attr('placeholder', 'Szukaj...');

        // Dodaj ikonę lupy do pola wyszukiwania
        searchInput.before('<i class="fas fa-search" style="color: gray; position: absolute; left: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>');

        // Stylizacja ikony i pola wyszukiwania
        searchInput.parent().css('position', 'relative');
        searchInput.css('padding-left', '25px');
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
                    url: "{{ route('users.delete')}}",
                    type: "GET",
                    dataType: 'json',
                    data: {
                        event_id: $(this).attr('id'),
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Użytkownik został usunięty!",
                            text: "Naciśnij przycisk aby przeładować stronę",
                            icon: "success",
                            showConfirmButton: true
                        }).then((result) => {
                            location.reload();
                        });
                    },
                    error: function(error) {
                        Swal.fire('Nie udało sie usunąć użytkownika!', '', 'error');
                    },
                })
            }
        })
    });
</script>



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


<script src="js/admin.js"></script>


</body>

</html>


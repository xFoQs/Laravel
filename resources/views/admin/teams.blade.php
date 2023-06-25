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
            <div class="nav_list"> <a href="/dashboard" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i>
                    <span class="nav_name">Dashboard</span> </a> <a href="/players" class="nav_link"> <i
                        class='bx bx-user nav_icon'></i> <span class="nav_name">Zawodnicy</span> </a> <a href="/games" class="nav_link"> <i class='bx bxs-grid nav_icon'></i> <span
                        class="nav_name">Mecze</span> </a> <a href="" class="nav_link active"> <i
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

<div id="content" style="padding-top:5rem; height:100%;">

    <div class="contenthead" style="display:flex; justify-content:space-between; margin-bottom:1rem;margin-top: 2rem;">
        <h2 style="padding-right:2rem;">Drużyny</h2>

        <!-- Button trigger modal -->

        <button type="button" class="btn btn-outline-primary btn-rounded" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop" style="height: 45px;width: 170px;">
            Dodaj drużyne
        </button>


    </div>

    @if (Session::has('success'))
        <script>
            Swal.fire(
                'Gratulacje!',
                'Dodałeś zawodnika!',
                'success'
            )
        </script>
    @endif

    @if (Session::has('update'))
        <script>
            Swal.fire(
                'Gratulacje!',
                'Zaaktualizowałeś dane zawodnika!',
                'success'
            )
        </script>
    @endif

</div>

<br>

<table id="example" class="table table-bordered" style="width:100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Drużyna</th>
        <th>Liga</th>
        <th>Akcje</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($teams as $team)

        <tr data-entry-id="{{ $team->id }}">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $team->name }}</td>
            <td>{{ $team->league->name }}</td>
            <td>
                <a data-bs-toggle="modal" data-bs-target="#editteam{{$team->id}}"><i
                        class="fa-solid fa-pen-to-square" style="color:#4f4f4f; padding-right: 0.5rem;"></i></a>
                <a href="#" class="delete" id="{{ $team->id }}"><i class="fa-solid fa-trash"
                                                                     style="color:#4f4f4f;"></i></a>
            </td>
        </tr>
    @endforeach

    </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Dodaj drużynę</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row gx-3" method="POST" action="{{ route('team.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-4">
                        <div class="form">
                            <div>
                                <div class="mb-4 d-flex justify-content-center">
                                    <img id="preview" src="{{ asset('img/brak.webp') }}"
                                         alt="example placeholder" style="width: 160px; height: 200px;" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="col-md-12">
                            <div class="form">
                                <input type="text" class="form-control" name="name"
                                       value="" required placeholder="Podaj nazwę drużyny" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Podaj nazwę drużyny')" />
                                <label for="validationCustom01" class="form-label"></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form">
                                <select id="league_id" name="league_id" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" required>
                                    <option value="" selected disabled>-- Wybierz Klub --</option>
                                    @foreach($leagues as $league)
                                        <option value="{{ $league->id }}" {{ old('league_id') == $league->id ? 'selected' : '' }}>
                                            {{ $league->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12" style="padding-bottom: 1rem;">
                            <div class="form">
                                <label class="form-label" for="customFile"></label>
                                <input type="file" class="form-control" id="customFile" name="customFile" onchange="previewFile(event)" />
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <!-- <button class="btn btn-primary" type="submit">Submit form</button> -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add" value="add"
                                    class="btn btn-primary">Send</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<!-- Modal -->
@foreach($teams as $team)
    <div class="modal editteam" id="editteam{{$team->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" data-player-id="{{$team->id}}"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edytuj drużynę</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row gx-3" method="POST" action="{{route('team.update')}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $team->id }}">
                        <div class="col-md-4">
                            <div class="form">
                                <div>
                                    <div class="mb-4 d-flex justify-content-center">
                                        <img id="preview{{$team->id}}" src="{{ $team->photo ? asset('img/' . $team->photo) : asset('img/brak.webp') }}"
                                             alt="example placeholder" style="width: 160px; height: 200px;"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="col-md-12">
                                <div class="form">
                                    <input type="text" class="form-control" name="name"
                                           value="{{$team->name}}" required placeholder="Podaj nazwę" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Podaj nazwę drużyny')" />
                                    <label for="validationCustom01" class="form-label"></label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form">
                                    <select id="league_id" name="league_id" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" data-game-id="{{ $team->id }}" required>
                                        <option value="" selected>-- Wybierz Ligę --</option>
                                        @foreach($leagues as $league)
                                            <option value="{{ $league->id }}" @if($league->id == old('league_id', isset($team) ? $team->league_id : '')) selected @endif>{{ $league->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" style="padding-bottom: 1rem;">
                                <div class="form">
                                    <label class="form-label" for="customFile{{$team->id}}"></label>
                                    <input type="file" class="form-control" id="customFile{{$team->id}}" name="customFile" onchange="previewFile2(event, {{$team->id}})" />
                                </div>
                            </div>
                            <div class="col-md-12" style="padding-bottom: 1rem;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="deletePhoto{{$team->id}}" name="deletePhoto">
                                    <label class="form-check-label" for="deletePhoto{{$team->id}}">
                                        Usuń zdjęcie
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                                <button type="submit" id="update" value="add" class="btn btn-primary">Wyślij</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach


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
    function previewFile(event) {
        var preview = document.getElementById('preview');
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "{{ asset('img/brak.webp') }}";
        }
    }
</script>

<script>
    function previewFile2(event, teamId) {
        var preview = document.getElementById("preview" + teamId);
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "{{ asset('img/brak.webp') }}";
        }
    }
</script>

<script>
    $(document).ready(function() {
        $('.select2').select2();

        // Zdarzenie dla modala "editplayer"
        $('.editteam').on('shown.bs.modal', function() {
            var playerId = $(this).data('player-id');
            $(this).find('.select2').select2({
                dropdownParent: $(this)
            });
            // Wykonaj inne operacje na podstawie playerId
        });

        // Zdarzenie dla modala "staticBackdrop"
        $('#staticBackdrop').on('shown.bs.modal', function() {
            $(this).find('.select2').select2({
                dropdownParent: $(this)
            });
        });
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
            confirmButtonText: 'Tal, usuń!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('team.delete')}}",
                    type: "GET",
                    dataType: 'json',
                    data: {
                        event_id: $(this).attr('id'),
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Drużyna została usunięta!",
                            text: "Naciśnij przycisk aby przeładować stronę",
                            icon: "success",
                            showConfirmButton: true
                        }).then((result) => {
                            location.reload();
                        });
                    },
                    error: function(error) {
                        Swal.fire('Nie udało sie usunąć drużyny!', '', 'error');
                    },
                })
            }
        })
    });
</script>

<script>
    $(document).ready(function() {
        var table = $('#example').DataTable({
            paging: true,
            searchPanes: true,
            dom: 'PBfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                }
            ],
            language: {
                searchPanes: {
                    clearMessage: 'Wyczyść',
                    collapse: {
                        0: 'Pokaż',
                        _: 'Pokaż wszystko (%d)'
                    },
                    count: '{total}',
                    countFiltered: '{shown} ({total})',
                    emptyPanes: 'Brak danych do wyświetlenia',
                    loadMessage: 'Ładowanie filtrów...',
                    title: 'Filtry aktywne - %d'
                },
            },
            columnDefs: [
                {
                    searchPanes: {
                        show: true
                    },
                    targets: [1, 2]
                },
                {
                    searchPanes: {
                        show: false
                    },
                    targets: [3]
                }
            ]
        });

        table.searchPanes.container().prependTo('#searchPanesContainer');
        table.searchPanes.resizePanes();


        // Funkcja do odświeżania tabeli
        function refreshTable() {
            table.draw();
        }

        refreshTable();
    });

</script>


<script src="js/admin.js"></script>


</body>

</html>


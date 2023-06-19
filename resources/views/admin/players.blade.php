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
                        <span class="nav_name">Dashboard</span> </a> <a href="" class="nav_link active"> <i
                            class='bx bx-user nav_icon'></i> <span class="nav_name">Zawodnicy</span> </a> <a href="/games"
                        class="nav_link"> <i class='bx bxs-grid nav_icon'></i> <span
                            class="nav_name">Drużyny</span> </a> <a href="" class="nav_link"> <i
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

    <div id="content" style="padding-top:5rem; height:100%;">

        <div class="contenthead" style="display:flex; justify-content:space-between; margin-bottom:1rem;">
            <h3 style="padding-right:2rem;">Zawodnicy</h3>

            <!-- Button trigger modal -->

                <button type="button" class="btn btn-outline-primary btn-rounded" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop" style="height: 45px;width: 170px;">
                    Dodaj zawodnika
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

    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Surname</th>
                <th>Birthday</th>
                <th>Position</th>
                <th>Club</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($players as $player)
                <tr data-entry-id="{{ $player->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $player->name }}</td>
                    <td>{{ $player->surname }}</td>
                    <td>{{ $player->birth_date }}</td>
                    <td>{{ $player->position }}</td>
                    <td>{{ $player->team_name }}</td>
                    <td>
                        <a data-bs-toggle="modal" data-bs-target="#editplayer{{$player->id}}"><i
                                class="fa-solid fa-pen-to-square" style="color:#4f4f4f; padding-right: 0.5rem;"></i></a>
                        <a href="#" class="delete" id="{{ $player->id }}"><i class="fa-solid fa-trash"
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
                    <h5 class="modal-title" id="staticBackdropLabel">Dodaj zawodnika</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3"  method="POST" action="{{ route('player.store') }}">
                        @csrf
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" class="form-control" name="name"  onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Podaj imię')"
                                    value="" required />
                                <label class="form-label">Imię</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" class="form-control" name="surname" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Podaj nazwisko')"
                                    value="" required />
                                <label class="form-label">Nazwisko</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="date" class="form-control" name="birth_date" value="">
                                <label for="validationCustom02" class="form-label">Data urodzenia</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form">
                                <select name="position" class="select2 form-control is-valid border rounded" data-width="100%" placeholder="Pozycja">
                                    <option value="" selected disabled>-- Wybierz Pozycje --</option>
                                    <option value="Napastnik">
                                        Napastnik
                                    </option>
                                    <option value="Pomocnik">
                                        Pomocnik
                                    </option>
                                    <option value="Obrońca">
                                        Obrońca
                                    </option>
                                    <option value="Bramkarz">
                                        Bramkarz
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form">
                                <select name="team_id" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Wybierz Klub')" required>
                                    <option value="" selected disabled>-- Wybierz Klub --</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->name }}" {{ old('team_id') == $team->name ? 'selected' : '' }}>{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12" style="padding-top:1rem">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck"
                                    required onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Zatwierdz')"/>
                                <label class="form-check-label" for="invalidCheck">Zatwierdz</label>
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
       @foreach($players as $player)
       <div class="modal editplayer" id="editplayer{{$player->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" data-player-id="{{$player->id}}"
       aria-labelledby="staticBackdropLabel" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-lg">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="staticBackdropLabel">Edytuj zawodnika</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                   <form class="row gx-3 gy-0" method="POST" action="{{route('player.update')}}">
                       @csrf
                       <input type="hidden" name="id" value="{{ $player->id }}">
                       <div class="col-md-4">
                           <div class="form">
                               <input type="text" class="form-control" name="name"
                                   value="{{$player->name}}" required placeholder="Podaj imię" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Podaj imię')"/>
                               <label for="validationCustom01" class="form-label"></label>
                           </div>
                       </div>
                       <div class="col-md-4">
                           <div class="form">
                               <input type="text" class="form-control" name="surname" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Podaj nazwisko')"
                                   value="{{$player->surname}}" required placeholder="Podaj nazwisko"/>
                               <label for="validationCustom02" class="form-label"></label>
                               <div class="invalid-feedback">Nazwisko jest wymagane!</div>
                           </div>
                       </div>
                       <div class="col-md-4">
                           <div class="form">
                               <input type="date" class="form-control" name="birth_date" value="{{$player->birth_date}}" placeholder="Podaj datę urodzenia">
                               <label class="form-label"></label>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="form">
                               <select name="position" class="select2 form-control is-valid border rounded" data-width="100%" placeholder="Pozycja">
                                   <option value="Napastnik" {{ $player->position == 'Napastnik' ? 'selected' : '' }}>
                                       Napastnik
                                   </option>
                                   <option value="Pomocnik" {{ $player->position == 'Pomocnik' ? 'selected' : '' }}>
                                       Pomocnik
                                   </option>
                                   <option value="Obrońca" {{ $player->position == 'Obrońca' ? 'selected' : '' }}>
                                       Obrońca
                                   </option>
                                   <option value="Bramkarz" {{ $player->position == 'Bramkarz' ? 'selected' : '' }}>
                                       Bramkarz
                                   </option>
                               </select>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="form">
                               <select name="team_id" class="form-control select2 border rounded border-1" data-live-search="true" data-width="100%" placeholder="Wybierz Klub" onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Wybierz Klub')" required>
                                   <option value="{{ $player->team_name }}" selected hidden>{{$player->team_name}}</option>
                                   @foreach($teams as $team)
                                       <option value="{{ $team->name }}" {{ $player->team_name == $team->name ? 'selected' : '' }}>{{ $team->name }}</option>
                                   @endforeach
                               </select>
                               <div class="invalid-feedback">Klub jest wymagany!</div>
                           </div>
                       </div>
                       <div class="col-12" style="padding-top:1rem">
                           <div class="form-check form-switch">
                               <input class="form-check-input" type="checkbox" value="" id="invalidCheck"
                                   required onchange="setCustomValidity('')" oninvalid="this.setCustomValidity('Zatwierdz')"/>
                               <label class="form-check-label" for="invalidCheck">Zatwierdz</label>


                           </div>
                       </div>

                       <div class="col-12">
                           <!-- <button class="btn btn-primary" type="submit">Submit form</button> -->
                           <div class="modal-footer">
                               <button type="button" class="btn btn-secondary"
                                   data-bs-dismiss="modal">Close</button>
                               <button type="submit" id="update" value="add"
                                   class="btn btn-primary">Send</button>
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

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
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
            $('.select2').select2();

            // Zdarzenie dla modala "editplayer"
            $('.editplayer').on('shown.bs.modal', function() {
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
                        url: "{{ route('player.delete')}}",
                        type: "GET",
                        dataType: 'json',
                        data: {
                            event_id: $(this).attr('id'),
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Zawodnik został usunięty!",
                                text: "Naciśnij przycisk aby przeładować stronę",
                                icon: "success",
                                showConfirmButton: true
                            }).then((result) => {
                                location.reload();
                            });
                        },
                        error: function(error) {
                            Swal.fire('Nie udało sie usunąć zawodnika!', '', 'error');
                        },
                    })
                }
            })
        });
    </script>


<script src="js/admin.js"></script>


</body>

</html>


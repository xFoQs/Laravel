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
                <th>Id</th>
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
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $player->name }}</td>
                    <td>{{ $player->surname }}</td>
                    <td>{{ $player->birthday }}</td>
                    <td>{{ $player->position }}</td>
                    <td>{{ $player->club_name }}</td>
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
                    <form class="row g-3 needs-validation" novalidate method="POST" action="{{ url('players') }}">
                        @csrf
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" class="form-control" name="name" id="validationCustom01"
                                    value="" required />
                                <label for="validationCustom01" class="form-label">First name</label>
                                <div class="invalid-feedback">Imie jest wymagane!</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" class="form-control" name="surname" id="validationCustom02"
                                    value="" required />
                                <label for="validationCustom02" class="form-label">Last name</label>
                                <div class="invalid-feedback">Nazwisko jest wymagane!</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="date" class="form-control" name="birthday" value="">
                                <label for="validationCustom02" class="form-label">Birthday</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <select name="position" class="form-select" placeholder="Position" required>
                                    <option value="Pozycja" disabled selected hidden>Pozycja</option>
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
                                <div class="invalid-feedback">Klub jest wymagany!</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <select name="club" class="form-select" required>
                                    <option value="" disabled selected hidden>Klub</option>
                                    @foreach ($clubs as $club)
                                        <option value="{{ $club->club_name }}">{{ $club->club_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Klub jest wymagany!</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck"
                                    required />
                                <label class="form-check-label" for="invalidCheck">Zatwierdz</label>
                                <div class="invalid-feedback">Musisz zatwierdzić dodanie</div>

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
       <div class="modal fade" id="editplayer{{$player->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
       aria-labelledby="staticBackdropLabel" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-lg">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="staticBackdropLabel">Edytuj zawodnika</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                   <form class="row gx-3 gy-0 needs-validation" novalidate method="POST" action="{{route('player.update')}}">
                       @csrf
                       @method('POST')
                       <input type="hidden" name="id" value="{{ $player->id }}">
                       <div class="col-md-4">
                           <div class="form">
                               <input type="text" class="form-control" name="e_name" id="validationCustom01"
                                   value="{{$player->name}}" required placeholder="Podaj imię"/>
                               <label for="validationCustom01" class="form-label"></label>
                               <div class="invalid-feedback">Imie jest wymagane!</div>
                           </div>
                       </div>
                       <div class="col-md-4">
                           <div class="form">
                               <input type="text" class="form-control" name="e_surname" id="validationCustom02"
                                   value="{{$player->surname}}" required placeholder="Podaj nazwisko"/>
                               <label for="validationCustom02" class="form-label"></label>
                               <div class="invalid-feedback">Nazwisko jest wymagane!</div>
                           </div>
                       </div>
                       <div class="col-md-4">
                           <div class="form">
                               <input type="date" class="form-control" name="e_birthday" value="{{$player->birthday}}" placeholder="Podaj datę urodzenia">
                               <label for="validationCustom02" class="form-label"></label>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="form">
                               <select name="e_position" class="form-select" placeholder="Pozycja" required>
                                   <option value="{{$player->position}}" selected hidden>{{$player->position}}</option>
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
                               <div class="invalid-feedback">Klub jest wymagany!</div>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="form">
                               <select name="e_club" class="form-select" required>
                                   <option value="{{ $club->club_name }}" selected hidden>{{$club->club_name}}</option>
                                   @foreach ($clubs as $club)
                                       <option value="{{ $club->club_name }}">{{ $club->club_name }}</option>
                                   @endforeach
                               </select>
                               <div class="invalid-feedback">Klub jest wymagany!</div>
                           </div>
                       </div>
                       <div class="col-12">
                           <div class="form-check form-switch" style="padding-top:1rem;">
                               <input class="form-check-input" type="checkbox" value="" id="invalidCheck"
                                   required />
                               <label class="form-check-label" for="invalidCheck">Zatwierdz</label>
                               <div class="invalid-feedback">Musisz zatwierdzić dodanie</div>

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

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

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
                        url: "{{ route('player.delete') }}",
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


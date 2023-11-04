<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rejestracja</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>


@if (Session::has('success'))
    <script>
        Swal.fire(
            'Gratulacje!',
            'Dodałeś użytkownika!',
            'success'
        )
    </script>
@endif

<section class="vh-100" style="background-color: white;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-xl-10">
                <div class="card" style="border-radius: 1rem; background-color:#fbfbfb;">
                    <div class="row g-0" style="align-items:center;">
                        <div class="col-md-6 col-lg-5 d-none d-md-block" style="padding-left:1rem;">
                            <img src="img/referee.png" alt="login form" class="img-fluid"
                                 style="border-radius: 1rem 0 0 1rem;" />
                        </div>
                        <div class="col-md-6 col-lg-7 d-flex align-items-center">
                            <div class="card-body p-4 p-lg-5 text-black">
                                <form action="{{ route('register.post') }}" method="POST">
                                    @csrf
                                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Rejestracja</h5>
                                    <div class="form-outline mb-4">
                                        <input type="text" id="name" class="form-control form-control-lg" name="name" required />
                                        <label class="form-label" for="name">Imię</label>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <input type="text" id="surname" class="form-control form-control-lg" name="surname" required />
                                        <label class="form-label" for="surname">Nazwisko</label>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <input type="email" id="email" class="form-control form-control-lg" name="email" required />
                                        <label class="form-label" for="email">Adres email</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="password" id="password" class="form-control form-control-lg" name="password" required />
                                        <label class="form-label" for="password">Hasło</label>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <select name="role" id="role" class="form-select form-control-lg">
                                            <option value="2">Użytkownik</option>
                                            <option value="1">Administrator</option>
                                        </select>
                                    </div>
                                    <div class="pt-1 mb-4">
                                        <button class="btn btn-dark btn-lg btn-block" type="submit">Zarejestruj</button>
                                    </div>
                                    @if (Session::has('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif
                                    @if ($errors->has('name') || $errors->has('surname') || $errors->has('email') || $errors->has('password'))
                                        <div class="alert alert-danger" role="alert" style="padding:10px 20px 10px 20px;">
                                            Proszę uzupełnić wszystkie pola formularza.
                                        </div>
                                    @endif
                                    @if ($errors->has('email'))
                                        <div class="alert alert-danger" role="alert" style="padding:10px 20px 10px 20px;">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                    @if ($errors->has('password'))
                                        <div class="alert alert-danger" role="alert" style="padding:10px 20px 10px 20px;">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MDB -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"></script>
</body>
</html>

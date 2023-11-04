<?php

  namespace App\Http\Controllers\Admin;

  use App\Http\Controllers\Controller;
  use App\Http\Controllers\response;
  use App\Models\User;
  use Hash;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
  use Session;

  class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {

        return view('auth.login');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration()
    {
        return view('admin.registration');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
                        ->withSuccess('Zostałeś zalogowany');
        }

        return redirect("login")->withError('Niepoprawny email lub hasło');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
      public function postRegistration(Request $request)
      {
          $request->validate([
              'name' => 'required',
              'surname' => 'required',
              'email' => 'required|email|unique:users',
              'password' => 'required|min:6', // Hasło musi mieć co najmniej 6 znaków
              'role' => 'required|in:1,2', // Upewnij się, że jest 1 lub 2
          ], [
              'email.unique' => 'Email jest już zajęty.',
              'password.min' => 'Hasło musi zawierać co najmniej 6 znaków.',
          ]);

          try {
              // Tworzenie nowego użytkownika
              $user = new User();
              $user->name = $request->name;
              $user->surname = $request->surname;
              $user->email = $request->email;
              $user->password = Hash::make($request->password);
              $user->role_id = $request->role; // Przypisanie roli bez mapowania

              $user->save();

              return redirect()->intended('registration')->withSuccess('Gratulacje! Utworzyłeś konto');
          } catch (\Exception $e) {
              if (strpos($e->getMessage(), 'Integrity constraint violation') !== false) {
                  // Obsługa wyjątku w przypadku, gdy adres email jest już zajęty
                  return back()->withErrors(['email' => 'Ten adres email jest już używany.'])->withInput();
              } else {
                  // Obsługa innych błędów
                  return back()->withErrors(['generic' => 'Wystąpił błąd podczas rejestracji.'])->withInput();
              }
          }
      }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        if(Auth::check()){
            return view('admin.dashboard');
        }

        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{


    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'rol' => ['required', 'string', 'in:academia,profesor,alumno'],
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    
   public function register(Request $request)
    {
        
        // Validar los datos
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'rol' => ['required', 'string', 'in:academia,profesor,alumno'],
        ]);

        if ($validator->fails()) {
            \Log::error('Validación fallida', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

    
        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'premium' => false,
            'email_verified_at' => null, // Asegurar que no está verificado
        ]);
         \Log::info('Usuario creado', ['id' => $user->id, 'email' => $user->email]);
        // Disparar evento de registro (envía email de verificación)
        event(new Registered($user));
        $user->sendEmailVerificationNotification();
        \Log::info('Email de verificación enviado');
        return redirect()->route('verification.notice');
    }





    protected function create(array $data)
    {
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'rol' => $data['rol'],
            'premium' => false,
            'email_verified_at' => null,
        ]);


        event(new Registered($user));
    
        return $user;
    }


    protected function redirectTo()
    {
        return '/'; // Página de inicio
    }

}

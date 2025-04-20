<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
            'ident' => ['nullable', 'string', 'max:255'], //
            'numero_censo' => ['nullable', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'rol' => ['required', 'string', 'in:admin,academia,profesor,alumno'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'codigo_postal' => ['nullable', 'string', 'max:10'],
            'localidad' => ['nullable', 'string', 'max:255'],
            'provincia' => ['nullable', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        
        return User::create([
            'ident' => strip_tags($data['ident']),
            'name' => strip_tags($data['name']),
            'email' => strip_tags($data['email']),
            'telefono' => isset($data['telefono']) ? strip_tags($data['telefono']) : null,
            'password' => Hash::make($data['password']),
            'rol' => strip_tags($data['rol']),
            'activo' => isset($data['activo']) ? true : false,
            'premium' => isset($data['premium']) ? true : false,
            'numero_censo' => isset($data['numero_censo']) ? strip_tags($data['numero_censo']) : null,
            'direccion' => isset($data['direccion']) ? strip_tags($data['direccion']) : null,
            'codigo_postal' => isset($data['codigo_postal']) ? strip_tags($data['codigo_postal']) : null,
            'localidad' => isset($data['localidad']) ? strip_tags($data['localidad']) : null,
            'provincia' => isset($data['provincia']) ? strip_tags($data['provincia']) : null,
        ]);

    }
    protected function redirectTo()
    {
        return '/'; // Página de inicio
    }

}

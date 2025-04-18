@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                    
                        <!-- Campo Identificación -->
                        <div class="row mb-3">
                            <label for="ident" class="col-md-4 col-form-label text-md-end">{{ __('Nombre') }}</label>
                            <div class="col-md-6">
                                <input id="ident" type="text" class="form-control @error('ident') is-invalid @enderror" name="ident" value="{{ old('ident') }}" required>
                                @error('ident')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    


                        <!-- Campo Nombre -->
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    
                        <!-- Campo Email -->
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    
                        <!-- Campo Teléfono -->
                        <div class="row mb-3">
                            <label for="telefono" class="col-md-4 col-form-label text-md-end">{{ __('Teléfono') }}</label>
                            <div class="col-md-6">
                                <input id="telefono" type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" value="{{ old('telefono') }}" required>
                                @error('telefono')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    
                        <!-- Campo Contraseña -->
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    
                        <!-- Campo Confirmar Contraseña -->
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                    
                        <!-- Campo Rol -->
                        <div class="row mb-3">
                            <label for="rol" class="col-md-4 col-form-label text-md-end">{{ __('Rol') }}</label>
                            <div class="col-md-6">
                                <select id="rol" class="form-control @error('rol') is-invalid @enderror" name="rol" required>
                                    
                                    <option value="academia">Academia</option>
                                    <option value="profesor">Profesor</option>
                                    <option value="alumno">Alumno</option>
                                </select>
                                @error('rol')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    


                        <!-- Campo Localidad -->
                        <div class="row mb-3">
                            <label for="localidad" class="col-md-4 col-form-label text-md-end">{{ __('Localidad') }}</label>
                            <div class="col-md-6">
                                <input id="localidad" type="text" class="form-control @error('localidad') is-invalid @enderror" 
                                    name="localidad" value="{{ old('localidad') }}" >
                                @error('localidad')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Campo Provincia -->
                        <div class="row mb-3">
                            <label for="provincia" class="col-md-4 col-form-label text-md-end">{{ __('Provincia') }}</label>
                            <div class="col-md-6">
                                <input id="provincia" type="text" class="form-control @error('provincia') is-invalid @enderror" 
                                    name="provincia" value="{{ old('provincia') }}" required>
                                @error('provincia')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>                        

                        <!-- Campo Número de Censo -->
                        <div class="row mb-3">
                            <label for="numero_censo" class="col-md-4 col-form-label text-md-end">{{ __('Número de Censo (solo academias)') }}</label>
                            <div class="col-md-6">
                                <input id="numero_censo" type="text" class="form-control @error('numero_censo') is-invalid @enderror" 
                                    name="numero_censo" value="{{ old('numero_censo') }}" >
                                @error('numero_censo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Campo Dirección -->
                        <div class="row mb-3">
                            <label for="direccion" class="col-md-4 col-form-label text-md-end">{{ __('Dirección (solo academias)') }}</label>
                            <div class="col-md-6">
                                <input id="direccion" type="text" class="form-control @error('direccion') is-invalid @enderror" 
                                    name="direccion" value="{{ old('direccion') }}" >
                                @error('direccion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Campo Código Postal -->
                        <div class="row mb-3">
                            <label for="codigo_postal" class="col-md-4 col-form-label text-md-end">{{ __('Código Postal (solo academias)') }}</label>
                            <div class="col-md-6">
                                <input id="codigo_postal" type="text" class="form-control @error('codigo_postal') is-invalid @enderror" 
                                    name="codigo_postal" value="{{ old('codigo_postal') }}" >
                                @error('codigo_postal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Campo Activo -->
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo" checked>
                                    <label class="form-check-label" for="activo">
                                        {{ __('Activo') }}
                                    </label>
                                </div>
                                @error('activo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    
                        <!-- Campo Premium -->
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="premium" name="premium">
                                    <label class="form-check-label" for="premium">
                                        {{ __('Premium') }}
                                    </label>
                                </div>
                                @error('premium')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
  

                        <!-- Botón de Registro -->
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

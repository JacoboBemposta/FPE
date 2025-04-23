@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <h3 class="mb-0 text-center">{{ __('Registro') }}</h3>
                </div>

                <div class="card-body px-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row g-3">
                            <!-- Campos principales -->
                            <div class="col-12">
                                <label for="name" class="form-label">{{ __('Nombre') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="telefono" class="form-label">{{ __('Teléfono') }}</label>
                                <input id="telefono" type="text" class="form-control @error('telefono') is-invalid @enderror" 
                                       name="telefono" value="{{ old('telefono') }}" required>
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password-confirm" class="form-label">{{ __('Confirmar Contraseña') }}</label>
                                <input id="password-confirm" type="password" class="form-control" 
                                       name="password_confirmation" required>
                            </div>

                            <div class="col-md-6">
                                <label for="localidad" class="form-label">{{ __('Localidad') }}</label>
                                <input id="localidad" type="text" class="form-control @error('localidad') is-invalid @enderror" 
                                       name="localidad" value="{{ old('localidad') }}">
                                @error('localidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="provincia" class="form-label">{{ __('Provincia') }}</label>
                                <input id="provincia" type="text" class="form-control @error('provincia') is-invalid @enderror" 
                                       name="provincia" value="{{ old('provincia') }}" required>
                                @error('provincia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="rol" class="form-label">{{ __('Rol') }}</label>
                                <select id="rol" class="form-select @error('rol') is-invalid @enderror" name="rol" required>
                                    <option value="" disabled selected>Selecciona tu rol</option>
                                    <option value="academia">Academia</option>
                                    <option value="profesor">Docente</option>
                                    <option value="alumno">Alumno</option>
                                </select>
                                @error('rol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Campos condicionales para Academia (ocultos inicialmente) -->
                            <div id="academiaFields" class="d-none">
                                <div class="col-12">
                                    <label for="ident" class="form-label">{{ __('Nombre de la Academia') }}</label>
                                    <input id="ident" type="text" class="form-control @error('ident') is-invalid @enderror" 
                                           name="ident" value="{{ old('ident') }}">
                                    @error('ident')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="numero_censo" class="form-label">{{ __('Número de Censo') }}</label>
                                    <input id="numero_censo" type="text" class="form-control @error('numero_censo') is-invalid @enderror" 
                                           name="numero_censo" value="{{ old('numero_censo') }}">
                                    @error('numero_censo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="direccion" class="form-label">{{ __('Dirección') }}</label>
                                    <input id="direccion" type="text" class="form-control @error('direccion') is-invalid @enderror" 
                                           name="direccion" value="{{ old('direccion') }}">
                                    @error('direccion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="codigo_postal" class="form-label">{{ __('Código Postal') }}</label>
                                    <input id="codigo_postal" type="text" class="form-control @error('codigo_postal') is-invalid @enderror" 
                                           name="codigo_postal" value="{{ old('codigo_postal') }}">
                                    @error('codigo_postal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    {{ __('Registrarse') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



                        <!-- Campo Activo -->
                        {{-- <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check"> --}}
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo" checked hjidden>
                                    {{-- <input id="activo" type="checkbox" class="form-check-input @error('activo') is-invalid @enderror" 
                                           name="activo" value="{{ old('activo') }}" checked>
                                    {{-- <label class="form-check-label" for="activo">
                                        {{ __('Activo') }}
                                    </label>
                                </div>
                                @error('activo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}
                    
                        <!-- Campo Premium -->
                        {{-- <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check"> --}}
                                    <input class="form-check-input" type="checkbox" id="premium" name="premium" hidden>
                                    {{-- <label class="form-check-label" for="premium">
                                        {{ __('Premium') }}
                                    </label>
                                </div>
                                @error('premium')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}
  

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rolSelect = document.getElementById('rol');
        const academiaFields = document.getElementById('academiaFields');

        function toggleAcademiaFields() {
            // Mostrar solo si se selecciona Academia
            academiaFields.classList.toggle('d-none', rolSelect.value !== 'academia');
        }

        // Event listeners
        rolSelect.addEventListener('change', toggleAcademiaFields);
        
        // Ejecutar al cargar para estado inicial
        toggleAcademiaFields();
    });
</script>

<style>
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .card-header {
        background: linear-gradient(45deg, #4e73df, #224abe);
        border-bottom: none;
    }
    
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        border-color: #4e73df;
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
        border: none;
        padding: 12px 30px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    
    #academiaFields {
        transition: opacity 0.3s ease;
    }
</style>
@endsection
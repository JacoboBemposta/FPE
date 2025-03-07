@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Todos los Cursos</h1>
    
    <!-- Formulario de búsqueda (opcional si quieres filtrar) -->
    <form method="GET" action="{{ route('cursos.index') }}" class="mb-4">
        <div class="form-row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Buscar curso..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="familia_profesional" class="form-control">
                    <option value="">Seleccione Familia Profesional</option>
                    @foreach($familias_profesionales as $familia)
                        <option value="{{ $familia->id }}" {{ request('familia_profesional') == $familia->id ? 'selected' : '' }}>
                            {{ $familia->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <!-- Tabla de cursos -->
    <table class="table table-striped">
        <thead class="thead-dark" style="background-color: #0056b3; color: white;">
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Familia Profesional</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cursos as $curso)
            <tr>
                <td>{{ $curso->codigo }}</td>
                <td>{{ $curso->nombre }}</td>
                <td>{{ $curso->familia->nombre }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

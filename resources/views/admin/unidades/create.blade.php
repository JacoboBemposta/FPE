@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Unidad Formativa para el módulo <strong>{{ $modulo->nombre }}</strong></h2>

    <form action="{{ route('modulos.unidades.store') }}" method="POST">
        @csrf

        <input type="hidden" name="modulo_id" value="{{ $modulo->id }}">

        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="text" name="codigo" class="form-control" value="{{ old('codigo') }}" required>
        </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>

        <div class="mb-3">
            <label for="horas" class="form-label">Horas</label>
            <input type="number" name="horas" class="form-control" value="{{ old('horas') }}" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('modulos.unidades.index', $modulo->id) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

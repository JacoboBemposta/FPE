@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Unidad Formativa: <strong>{{ $unidad->nombre }}</strong></h2>

    <form action="{{ route('modulos.unidades.update', [$modulo->id, $unidad->id']) --}} }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $unidad->nombre) }}" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control">{{ old('descripcion', $unidad->descripcion) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('modulos.unidades.index', $modulo->id) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

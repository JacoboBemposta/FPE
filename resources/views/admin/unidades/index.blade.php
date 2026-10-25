@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Unidades Formativas del Módulo: <strong>{{ $modulo->nombre }}</strong></h1>

    <div class="mb-3">
        <a href="{{ route('modulos.unidades.create', $modulo->id) }}" class="btn btn-primary">Añadir Unidad Formativa</a>
    </div>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Horas</th>
                <th>Curso</th>
                <th>Familia Profesional</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($modulo->unidades as $unidad)
                <tr>
                    <td>{{ $unidad->codigo }}</td>
                    <td>{{ $unidad->nombre }}</td>
                    <td>{{ $unidad->horas }}</td>
                    <td>{{ $modulo->curso->nombre ?? '—' }}</td>
                    <td>{{ $modulo->curso->familiaProfesional->nombre ?? '—' }}</td>
                    <td class="text-end">
                        <a href="{{ route('modulos.unidades.edit', [$modulo->id, $unidad->id']) --}} }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('admin.unidad.destroy', $unidad->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta unidad?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay unidades formativas para este módulo.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

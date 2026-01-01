@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Añadir Curso</h2>

    <a href="{{ route('admin.cursos.create', ['familia_id' => $familia->id']) --}} }}" class="btn btn-secondary">Añadir Curso</a>
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Curso</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>

        <div class="mb-3">
            <label for="horas" class="form-label">Número de horas</label>
            <input type="number" class="form-control" id="horas" name="horas" required>
        </div>

        <div class="mb-3">
            <label for="familia_id" class="form-label">Familia Profesional</label>
            <select class="form-control" id="familia_id" name="familia_id" required>
                <option value="">Selecciona una Familia Profesional</option>
                @foreach($familias_profesionales as $familia)
                    <option value="{{ $familia->id }}"
                        @if(isset($familia_seleccionada) && $familia_seleccionada->id == $familia->id) selected @endif>
                        {{ $familia->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Curso</button>
    </form>
</div>
@endsection


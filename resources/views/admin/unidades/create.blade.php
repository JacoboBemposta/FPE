@extends('layouts.app')

@section('content')
<form action="{{ route('unidades.store') }}" method="POST">
    @csrf
    
    <div class="modal-body">
        <!-- Campo para selección múltiple de módulos -->
        <div class="form-group">
            <label>Módulos asociados</label>
            <select name="modulo_ids[]" class="form-control select2" multiple required>
                @foreach($modulos as $modulo)
                    <option value="{{ $modulo->id }}">{{ $modulo->codigo }} - {{ $modulo->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Código</label>
            <input type="text" name="codigo" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Horas</label>
            <input type="number" name="horas" class="form-control" required>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>

<!-- Incluir Select2 para selección múltiple -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Selecciona módulos",
            allowClear: true
        });
    });
</script>
@endsection

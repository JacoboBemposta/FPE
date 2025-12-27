<div class="form-group">
    <label>Familia Profesional</label>
    <select name="familia_profesional_id" class="form-control" required>
        @foreach($familiasProfesionales as $familia)
            <option value="{{ $familia->id }}" {{ $curso->familia_profesional_id == $familia->id ? 'selected' : '' }}>
                {{ $familia->codigo }} - {{ $familia->nombre }}
            </option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Código</label>
    <input type="text" name="codigo" class="form-control" value="{{ $curso->codigo }}" required>
</div>
<div class="form-group">
    <label>Nombre</label>
    <input type="text" name="nombre" class="form-control" value="{{ $curso->nombre }}" required>
</div>
<div class="form-group">
    <label>Horas totales</label>
    <input type="number" name="horas" class="form-control" value="{{ $curso->horas }}">
</div>

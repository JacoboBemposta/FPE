@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Familias Profesionales</h2>
        <ul>
            @foreach($familias_profesionales as $familia)
                <li>{{ $familia->nombre }} - <a href="{{ route('admin.cursos.create', $familia->id) }}">Añadir Curso</a></li>
            @endforeach
        </ul>

        <h3>Añadir Nueva Familia Profesional</h3>
        <form action="{{ route('admin.familias.store') }}" method="POST">
            @csrf
            <input type="text" name="nombre" placeholder="Nombre de la Familia Profesional" required>
            <button type="submit">Crear Familia Profesional</button>
        </form>
    </div>
@endsection

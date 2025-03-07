@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Buscar Cursos</h1>
    
    <form method="GET" action="{{ route('academia.cursos') }}" class="mb-4">
        <div class="row">
            <div class="col-md-5">
                <select name="familia_id" class="form-control">
                    <option value="">Seleccione una Familia Profesional</option>
                    @foreach($familias_profesionales as $familia)
                        <option value="{{ $familia->id }}">{{ $familia->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <select name="curso_id" class="form-control">
                    <option value="">Seleccione un Curso</option>
                    @foreach($familias_profesionales as $familia)
                        @foreach($familia->cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Familia Profesional</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($familias_profesionales as $familia)
            <tr>
                <td>{{ $familia->nombre }}</td>
                <td>
                    <button class="btn btn-info btn-sm" data-bs-toggle="collapse" data-bs-target="#cursos-{{ $familia->id }}">
                        Ver Cursos
                    </button>
                </td>
            </tr>
            <tr class="collapse" id="cursos-{{ $familia->id }}">
                <td colspan="2">
                    <table class="table">
                        <tbody>
                            @foreach($familia->cursos as $curso)
                            <tr>
                                <td>{{ $curso->nombre }}</td>
                                <td>
                                    <form method="POST" action="{{ route('academia.asignar_curso', $curso->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Asignar Curso</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

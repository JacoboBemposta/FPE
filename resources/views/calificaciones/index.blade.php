{{-- @extends('layouts.app')

@section('content')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: center;
            vertical-align: middle;
        }

        table th {
            background-color: #007bff;
            color: white;
            font-size: 1.1rem;
        }

        table td {
            background-color: #f8f9fa;
        }

        table input {
            width: 80px;
            text-align: center;
            font-size: 1rem;
        }

        table input:disabled {
            background-color: #e9ecef;
        }

        table td:first-child {
            font-weight: bold;
        }

    </style>

    <div class="container mt-4">
        <h1 class="mb-4">Calificaciones del Curso: {{ $cursoAcademico->curso->nombre }}</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Alumnos</th>
                    @foreach($cursoAcademico->curso->modulos as $modulo)
                        @if($modulo->unidades->count() > 0)
                            <th colspan="{{ $modulo->unidades->count() }}">{{ $modulo->codigo }}</th>
                        @else
                            <th>{{ $modulo->codigo }}</th>
                        @endif
                    @endforeach
                </tr>
                <tr>
                    <th></th>
                    @foreach($cursoAcademico->curso->modulos as $modulo)
                        @if($modulo->unidades->count() > 0)
                            @foreach($modulo->unidades as $unidad)
                                <th>{{ $unidad->codigo }}</th>
                            @endforeach
                        @else
                            <th></th>
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($cursoAcademico->alumnos as $alumno)
                    <tr>
                        <td>{{ $alumno->nombre }}</td>
                        @foreach($cursoAcademico->curso->modulos as $modulo)
                            @if($modulo->unidades->count() > 0)
                                @foreach($modulo->unidades as $unidad)
                                    @php
                                        // Buscar la calificación
                                        $calificacion = $alumno->calificaciones
                                            ->where('unidad_formativa_id', $unidad->id)
                                            ->first();
                                    @endphp
                                    <td>
                                        @if($calificacion)
                                            <input type="number" class="form-control calificacion"
                                                   data-calificacion-id="{{ $calificacion->id }}"
                                                   value="{{ $calificacion->nota }}"
                                                   placeholder="Nota">
                                        @else
                                            <input type="number" class="form-control calificacion"
                                                   data-calificacion-id=""
                                                   placeholder="Nota">
                                        @endif
                                    </td>
                                @endforeach
                            @else
                                <td></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Manejo del evento para actualizar la calificación
        $('input.calificacion').on('blur', function() {
            var calificacionId = $(this).data('calificacion-id');
            var nota = $(this).val();
            if(calificacionId) {
                $.ajax({
                    url: '/calificaciones/update/' + calificacionId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nota: nota
                    },
                    success: function(response) {
                        if(response.success) {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Hubo un error al actualizar la calificación');
                    }
                });
            }
        });
    });
</script>
@endpush --}}

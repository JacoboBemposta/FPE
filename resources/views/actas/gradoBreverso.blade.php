<!DOCTYPE html>
<html>
<head>
    <style>
        .page-break {
            page-break-after: always; /* Fuerza nueva página después del anverso */
        }
    </style>

</head>
<body>
    <div class="mt-3">
        <label for="observaciones" class="form-label"><strong>OBSERVACIONES:</strong></label>
        <textarea id="observaciones" name="observaciones" class="form-control"
          rows="10" style="height: 300px;"
          placeholder="Escribe aquí tus observaciones..."></textarea>

    </div>
    <p><strong>ACCIÓN FORMATIVA:</strong> {{ $modulo->codigo }} - {{ $modulo->nombre }} ({{ $modulo->horas }} horas) </p>
    <table>
        <thead>
            <th>Bloques formativos</th>
            <th style="width:20px"></th>
            <th>Resultados de aprendijaze</th>
        </thead>
        <tbody>
            @foreach ($unidades as $unidad)
                <tr>
                    <td>{{ $unidad->codigo }} - {{ $unidad->nombre }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <thead>
            <th style="height: 20px">Profesor/Formador/Experto</th>
            <th style="height: 20px">V° 8° Director/a</th>
        </thead>
        <tbody>
            <th style="height: 100px"></th>
            <th style="height: 100px"></th>
        </tbody>
    </table>
    <!-- Notas al pie -->
    <div class="footer">

    </div>
</body>
</html>
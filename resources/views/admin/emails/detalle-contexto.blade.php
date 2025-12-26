{{-- resources/views/admin/emails/detalle-contexto.blade.php
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-envelope mr-2"></i>{{ $titulo }}
            </h1>
            <p class="text-muted">Listado de emails con contexto: {{ $contexto }}</p>
            <a href="{{ route('admin.email.stats') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left mr-2"></i>Volver a estadísticas
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Remitente</th>
                                    <th>Destinatario</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($emails as $email)
                                <tr>
                                    <td><small class="text-muted">#{{ $email->id }}</small></td>
                                    <td>
                                        @if($email->remitente_nombre)
                                            {{ $email->remitente_nombre }}
                                            <br>
                                            <small class="text-muted">{{ $email->remitente_email_db }}</small>
                                        @else
                                            <small class="text-muted">ID: {{ $email->remitente_id }}</small>
                                        @endif
                                    </td>
                                    <td><code>{{ $email->destinatario_email }}</code></td>
                                    <td>
                                        <span class="badge badge-{{ 
                                            $email->status == 'sent' ? 'success' : 
                                            ($email->status == 'failed' ? 'danger' : 
                                            ($email->status == 'delivered' ? 'info' : 'warning'))
                                        }}">
                                            <small class="text-muted">{{ ucfirst($email->status) }}</small>
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($email->created_at)->format('d/m/Y H:i') }}</small>
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-outline-info" onclick="verDetalle({{ $email->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $emails->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal para ver detalles (puedes reutilizar el mismo de la vista principal) --}}
<div class="modal fade" id="detalleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Email</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalleContent">
                Cargando...
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function verDetalle(id) {
    // Implementa la función para cargar los detalles del email

    fetch(`/admin/email-stats/detalle/${id}`)  
        .then(response => response.json())
        .then(data => {
            // Mostrar datos en el modal
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Información Básica</h6>
                        <p><strong>ID:</strong> ${data.id}</p>
                        <p><strong>Estado:</strong> <span class="badge badge-${data.status === 'sent' ? 'success' : 'danger'}">${data.status}</span></p>
                        <p><strong>Contexto:</strong> ${data.contexto}</p>
                        <p><strong>Template:</strong> ${data.template}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Remitente/Destinatario</h6>
                        <p><strong>Remitente ID:</strong> ${data.remitente_id}</p>
                        <p><strong>Destinatario:</strong> ${data.destinatario_email}</p>
                        <p><strong>IP:</strong> ${data.ip}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Variables (JSON)</h6>
                        <pre class="bg-light p-3 rounded">${JSON.stringify(data.variables, null, 2)}</pre>
                    </div>
                </div>
            `;
            document.getElementById('detalleContent').innerHTML = html;
            $('#detalleModal').modal('show');
        });
}
</script>
@endpush
@endsection --}}
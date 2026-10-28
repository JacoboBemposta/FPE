@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-envelope me-2"></i>{{ $titulo }}
            </h1>
            <p class="text-muted">Listado de emails con contexto: {{ $contexto }}</p>
            <a href="{{ route('admin.stats') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Volver a estadísticas
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
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
                                        <span class="badge bg-{{ 
                                            $email->status == 'sent' ? 'success' : 
                                            ($email->status == 'failed' ? 'danger' : 
                                            ($email->status == 'delivered' ? 'info' : 'warning'))
                                        }}">
                                            {{ ucfirst($email->status) }}
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
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $emails->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal para ver detalles --}}
<div class="modal fade" id="detalleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    fetch(`/admin/stats/email/${id}`)
        .then(response => response.json())
        .then(data => {
            const vars = data.variables || {};
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Información Básica</h6>
                        <p><strong>ID:</strong> ${data.id}</p>
                        <p><strong>Estado:</strong> <span class="badge bg-${data.status === 'sent' ? 'success' : 'danger'}">${data.status}</span></p>
                        <p><strong>Contexto:</strong> ${data.contexto}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Remitente/Destinatario</h6>
                        <p><strong>Remitente ID:</strong> ${data.remitente_id}</p>
                        <p><strong>Destinatario:</strong> ${data.destinatario_email}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Variables (JSON)</h6>
                        <pre class="bg-light p-3 rounded">${JSON.stringify(vars, null, 2)}</pre>
                    </div>
                </div>
            `;
            document.getElementById('detalleContent').innerHTML = html;
            const modalEl = document.getElementById('detalleModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        })
        .catch(() => {
            document.getElementById('detalleContent').innerHTML = '<div class="alert alert-danger">Error al cargar los detalles</div>';
        });
}
</script>
@endpush
@endsection

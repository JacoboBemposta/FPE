{{-- resources/views/admin/emails/busqueda.blade.php
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-search mr-2"></i>Resultados de Búsqueda
            </h1>
            <p class="text-muted">Resultados para: "{{ request('busqueda') }}" ({{ $resultados->total() }} encontrados)</p>
            <a href="{{ route('admin.email.stats') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left mr-2"></i>Volver a estadísticas
            </a>
        </div>
    </div>

    @if($resultados->count() > 0)
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
                                    <th>Contexto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resultados as $email)
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
                                            $email->contexto == 'academia_a_docente' ? 'primary' : 
                                            ($email->contexto == 'docente_a_academia' ? 'success' : 
                                            ($email->contexto == 'registro' ? 'info' : 'warning'))
                                        }}">
                                            {{ $email->contexto }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ 
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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $resultados->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>No se encontraron resultados</h4>
                    <p class="text-muted">Intenta con otros términos de búsqueda</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection --}}
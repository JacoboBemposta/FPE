@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Planes de Suscripción</h1>
    
    <div class="row">
        <!-- Plan Docente -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Plan Docente</h3>
                </div>
                <div class="card-body">
                    <h4>5€/mes</h4>
                    <ul>
                        <li>Acceso completo a cursos</li>
                        <li>Gestión de alumnos</li>
                        <li>Soporte prioritario</li>
                    </ul>
                    
                    @if($tipo_usuario === 'profesor')
                        <button onclick="suscribirse('docente', 5)" 
                                class="btn btn-primary btn-lg btn-block">
                            Suscribirse - 5€/mes
                        </button>
                    @else
                        <button disabled class="btn btn-secondary btn-lg btn-block">
                            No disponible para tu rol
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Plan Academia -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Plan Academia</h3>
                </div>
                <div class="card-body">
                    <h4>10€/mes</h4>
                    <ul>
                        <li>Todas las funciones del plan docente</li>
                        <li>Gestión de múltiples cursos</li>
                        <li>Panel de administración</li>
                    </ul>
                    
                    @if($tipo_usuario === 'academia')
                        <button onclick="suscribirse('academia', 10)" 
                                class="btn btn-primary btn-lg btn-block">
                            Suscribirse - 10€/mes
                        </button>
                    @else
                        <button disabled class="btn btn-secondary btn-lg btn-block">
                            No disponible para tu rol
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function suscribirse(tipo, precio) {

    
    // Mostrar loading
    const boton = event.target;
    const originalText = boton.innerHTML;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
    boton.disabled = true;
    
    // Enviar solicitud AJAX
    fetch('{{ route("suscripcion.procesar") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            tipo: tipo,
            precio: precio
        })
    })
    .then(response => {

        return response.json();
    })
    .then(data => {

        if (data.success && data.redirect_url) {
            // Redirigir a Stripe Checkout
            window.location.href = data.redirect_url;
        } else {
            alert(data.message || 'Error al procesar la suscripción');
            boton.innerHTML = originalText;
            boton.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión. Verifica la consola para más detalles.');
        boton.innerHTML = originalText;
        boton.disabled = false;
    });
}
</script>
@endsection
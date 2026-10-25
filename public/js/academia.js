// public/js/academia.js - Funcionalidades comunes para todas las vistas de academia
document.addEventListener('DOMContentLoaded', function() {

    // =============================================
    // 1. Confirmación para eliminar (botones .btn-danger)
    // =============================================
    document.querySelectorAll('.btn-danger').forEach(function(btn) {
        var form = btn.closest('form');
        if (form) {
            form.removeAttribute('onsubmit');
            form.addEventListener('submit', function(e) {
                if (!confirm('¿Estás seguro de eliminar este elemento?')) {
                    e.preventDefault();
                }
            });
        }
    });

    // =============================================
    // 2. Confirmación para archivar (botones .btn-warning)
    // =============================================
    document.querySelectorAll('.btn-warning').forEach(function(btn) {
        var form = btn.closest('form');
        if (form) {
            form.removeAttribute('onsubmit');
            form.addEventListener('submit', function(e) {
                if (!confirm('¿Estás seguro de archivar este elemento?')) {
                    e.preventDefault();
                }
            });
        }
    });

    // =============================================
    // 3. Toggle de detalles (para detalle_curso)
    // =============================================
    var btnDetalles = document.getElementById('btnDetallesCurso');
    var detallesDiv = document.getElementById('cursoDetalles');
    if (btnDetalles && detallesDiv) {
        btnDetalles.removeAttribute('onclick');
        btnDetalles.addEventListener('click', function() {
            detallesDiv.classList.toggle('hidden');
            var icon = this.querySelector('i');
            if (detallesDiv.classList.contains('hidden')) {
                icon.className = 'fas fa-chevron-down me-2';
                this.innerHTML = '<i class="fas fa-chevron-down me-2"></i>Detalles del Curso';
                this.prepend(icon);
            } else {
                icon.className = 'fas fa-chevron-up me-2';
                this.innerHTML = '<i class="fas fa-chevron-up me-2"></i>Ocultar Detalles';
                this.prepend(icon);
            }
        });
    }

    // =============================================
    // 4. Toggle de módulos (detalle_curso)
    // =============================================
    document.querySelectorAll('[onclick*="toggleModulo"]').forEach(function(btn) {
        var match = btn.getAttribute('onclick').match(/\d+/);
        if (match) {
            var moduloId = match[0];
            btn.removeAttribute('onclick');
            btn.addEventListener('click', function() {
                var moduloDiv = document.getElementById('modulo-' + moduloId);
                if (moduloDiv) {
                    moduloDiv.classList.toggle('hidden');
                }
            });
        }
    });

    // =============================================
    // 5. Botones de toggle módulo sin onclick
    // =============================================
    document.querySelectorAll('.modulo-header .btn-secondary').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var container = this.closest('.modulo-container');
            if (container) {
                var content = container.querySelector('.modulo-content');
                if (content) {
                    var id = content.id.replace('modulo-', '');
                    var moduloDiv = document.getElementById('modulo-' + id);
                    if (moduloDiv) {
                        moduloDiv.classList.toggle('hidden');
                    }
                }
            }
        });
    });

    // =============================================
    // 6. Guardar fechas (detalle_curso)
    // =============================================
    // Obtener variables desde meta tags
    var cursoAcademicoId = '';
    var csrfToken = '';
    var crearActualizarDetalleRoute = '';

    var metaCurso = document.querySelector('meta[name="curso-academico-id"]');
    if (metaCurso) {
        cursoAcademicoId = metaCurso.getAttribute('content') || '';
    }

    var metaCsrf = document.querySelector('meta[name="csrf-token"]');
    if (metaCsrf) {
        csrfToken = metaCsrf.getAttribute('content') || '';
    }

    var metaRoute = document.querySelector('meta[name="crear-actualizar-detalle-route"]');
    if (metaRoute) {
        crearActualizarDetalleRoute = metaRoute.getAttribute('content') || '';
    }

    if (!crearActualizarDetalleRoute) {
        crearActualizarDetalleRoute = '/academia/crear-actualizar-detalle';
    }

    document.querySelectorAll('.fecha-cambio').forEach(function(input) {
        input.removeAttribute('onchange');
        input.addEventListener('change', function() {
            saveFecha(this, cursoAcademicoId, csrfToken, crearActualizarDetalleRoute);
        });
    });

    window.saveFecha = function(inputElement, cursoId, token, routeUrl) {
        if (!cursoId) {
            console.error('No se encontró el ID del curso académico');
            return;
        }
        if (!token) {
            console.error('No se encontró el token CSRF');
            return;
        }

        var payload = {
            detalle_id: inputElement.getAttribute('data-detalle') || null,
            curso_academico_id: cursoId,
            campo: inputElement.getAttribute('data-campo'),
            valor: inputElement.value,
            _token: token
        };

        var unidadId = inputElement.getAttribute('data-unidad');
        var moduloId = inputElement.getAttribute('data-modulo');
        if (unidadId) {
            payload.unidad_formativa_id = unidadId;
        } else if (moduloId) {
            payload.modulo_id = moduloId;
        }

        var originalValue = inputElement.defaultValue;

        fetch(routeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify(payload)
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                inputElement.setAttribute('data-detalle', data.detalle.id);
                inputElement.classList.add('actualizado');
                setTimeout(function() {
                    inputElement.classList.remove('actualizado');
                }, 2000);
            } else {
                throw new Error(data.message || 'Error al guardar');
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            alert('Error: ' + error.message);
            inputElement.value = originalValue;
        });
    };

    // =============================================
    // 7. Editar cursos (modal en index.blade.php)
    // =============================================
    var editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(function(btn) {
        btn.removeAttribute('onclick');
        btn.addEventListener('click', function() {
            var cursoAcademicoId = this.getAttribute('data-id');
            var municipio = this.getAttribute('data-municipio');
            var provincia = this.getAttribute('data-provincia');
            var inicio = this.getAttribute('data-inicio');
            var fin = this.getAttribute('data-fin');
            var route = this.getAttribute('data-route');

            var cursoIdField = document.getElementById('curso_id');
            if (cursoIdField) cursoIdField.value = cursoAcademicoId;
            
            var municipioField = document.getElementById('municipio');
            if (municipioField) municipioField.value = municipio;
            
            var provinciaField = document.getElementById('provincia');
            if (provinciaField) provinciaField.value = provincia;
            
            var inicioField = document.getElementById('inicio');
            if (inicioField) inicioField.value = inicio;
            
            var finField = document.getElementById('fin');
            if (finField) finField.value = fin;

            var form = document.getElementById('editForm');
            if (form) form.setAttribute('action', route);

            var modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        });
    });
});
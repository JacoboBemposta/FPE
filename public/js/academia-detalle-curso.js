// public/js/academia-detalle-curso.js
document.addEventListener('DOMContentLoaded', function() {
    // =============================================
    // 0. Obtener variables desde meta tags (método tradicional)
    // =============================================
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

    // Si no hay ruta en meta, usar la URL por defecto
    if (!crearActualizarDetalleRoute) {
        crearActualizarDetalleRoute = '/academia/crear-actualizar-detalle';
    }

    // =============================================
    // 1. Toggle de detalles del curso
    // =============================================
    var btnDetalles = document.getElementById('btnDetallesCurso');
    var detallesDiv = document.getElementById('cursoDetalles');

    if (btnDetalles && detallesDiv) {
        btnDetalles.addEventListener('click', function() {
            detallesDiv.classList.toggle('hidden');
            var icon = this.querySelector('i');
            if (detallesDiv.classList.contains('hidden')) {
                icon.className = 'fas fa-chevron-down me-2';
                this.innerHTML = '<i class="fas fa-chevron-down me-2"></i>Detalles del Curso';
                // Reinsertar el icono (ya que innerHTML lo reemplaza)
                this.prepend(icon);
            } else {
                icon.className = 'fas fa-chevron-up me-2';
                this.innerHTML = '<i class="fas fa-chevron-up me-2"></i>Ocultar Detalles';
                this.prepend(icon);
            }
        });
    }

    // =============================================
    // 2. Toggle de módulos (botones con onclick heredado)
    // =============================================
    var toggleButtons = document.querySelectorAll('[onclick*="toggleModulo"]');
    for (var i = 0; i < toggleButtons.length; i++) {
        var btn = toggleButtons[i];
        var onclickAttr = btn.getAttribute('onclick');
        var match = onclickAttr.match(/\d+/);
        if (match) {
            var moduloId = match[0];
            btn.removeAttribute('onclick');
            btn.addEventListener('click', function(id) {
                return function() {
                    toggleModulo(id);
                };
            }(moduloId));
        }
    }

    // Función global para toggleModulo (también usada por los botones sin onclick)
    window.toggleModulo = function(moduloId) {
        var moduloDiv = document.getElementById('modulo-' + moduloId);
        if (moduloDiv) {
            moduloDiv.classList.toggle('hidden');
        }
    };

    // =============================================
    // 3. Guardar fechas (reemplazar onchange)
    // =============================================
    var fechaInputs = document.querySelectorAll('.fecha-cambio');
    for (var j = 0; j < fechaInputs.length; j++) {
        var input = fechaInputs[j];
        input.removeAttribute('onchange');
        input.addEventListener('change', function() {
            saveFecha(this, cursoAcademicoId, csrfToken, crearActualizarDetalleRoute);
        });
    }

    // Función global para saveFecha
    window.saveFecha = function(inputElement, cursoId, token, routeUrl) {
        // Validar que tengamos los datos necesarios
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

        // Guardar valor original para revertir si falla
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
    // 4. Eliminar alumno (confirmación)
    // =============================================
    var deleteButtons = document.querySelectorAll('form .btn-danger');
    for (var k = 0; k < deleteButtons.length; k++) {
        var btn = deleteButtons[k];
        var form = btn.closest('form');
        if (form) {
            form.removeAttribute('onsubmit');
            form.addEventListener('submit', function(e) {
                if (!confirm('¿Estás seguro de eliminar este alumno?')) {
                    e.preventDefault();
                }
            });
        }
    }

    // =============================================
    // 5. Botones de toggle módulo (los que no tienen onclick)
    // =============================================
    var moduloToggleBtns = document.querySelectorAll('.modulo-header .btn-secondary');
    for (var l = 0; l < moduloToggleBtns.length; l++) {
        var btn = moduloToggleBtns[l];
        btn.addEventListener('click', function() {
            var container = this.closest('.modulo-container');
            if (container) {
                var content = container.querySelector('.modulo-content');
                if (content) {
                    var id = content.id.replace('modulo-', '');
                    window.toggleModulo(id);
                }
            }
        });
    }
});
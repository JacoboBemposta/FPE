// public/js/academia-calificaciones.js
document.addEventListener('DOMContentLoaded', function () {
    var metaRoute = document.querySelector('meta[name="calificaciones-store-route"]');
    var calificacionesUrl = metaRoute ? metaRoute.getAttribute('content') : '/academia/calificaciones';
    if (!calificacionesUrl) {
        calificacionesUrl = '/academia/calificaciones';
    }

    var metaCsrf = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = metaCsrf ? metaCsrf.getAttribute('content') : '';

    window.guardarCalificacion = async function (input) {
        if (!input) return;

        var rawValue = (input.value || '').trim().replace(',', '.');

        // Si el campo está vacío, no disparar alerta mientras se edita
        if (rawValue === '') {
            return;
        }

        var nota = parseFloat(rawValue);

        if (isNaN(nota)) {
            alert('Por favor ingrese un número válido');
            input.value = input.getAttribute('data-last-saved') || '';
            return;
        }

        if (nota < 0 || nota > 10) {
            alert('La nota debe estar entre 0 y 10');
            input.value = input.getAttribute('data-last-saved') || '';
            return;
        }

        var lastSaved = input.getAttribute('data-last-saved');
        if (lastSaved !== null && lastSaved !== '' && parseFloat(lastSaved) === nota && !input.dataset.saving) {
            return;
        }

        var tipo = input.getAttribute('data-tipo');
        var calificacionId = input.getAttribute('data-calificacion-id') || null;

        var payload = {
            calificacion_id: calificacionId,
            alumno_curso_id: input.getAttribute('data-alumno-curso-id'),
            curso_academico_id: input.getAttribute('data-curso-academico-id'),
            unidad_formativa_id: tipo === 'unidad' ? input.getAttribute('data-unidad-id') : null,
            modulo_id: tipo === 'modulo' ? input.getAttribute('data-modulo-id') : null,
            nota: nota,
            _token: csrfToken
        };

        input.dataset.saving = 'true';

        try {
            var response = await fetch(calificacionesUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            });

            var result = await response.json();

            if (!response.ok || result.success === false) {
                var errorMsg = (result && result.message) ? result.message : 'Error al guardar la calificación';
                if (result && result.errors) {
                    errorMsg += '\n' + Object.values(result.errors).flat().join('\n');
                }
                throw new Error(errorMsg);
            }

            if (result.data && result.data.id) {
                input.setAttribute('data-calificacion-id', result.data.id);
            }

            input.setAttribute('data-last-saved', String(nota));

            // Feedback visual
            input.classList.remove('border-danger');
            input.classList.add('border-success', 'calificacion-guardada');
            setTimeout(function () {
                input.classList.remove('border-success', 'calificacion-guardada');
            }, 1500);

        } catch (error) {
            console.error('Error al guardar calificación:', error);
            input.classList.add('border-danger');
            alert('Error: ' + error.message);
            input.value = input.getAttribute('data-last-saved') || '';
        } finally {
            delete input.dataset.saving;
        }
    };

    var inputs = document.querySelectorAll('.calificacion-input');
    inputs.forEach(function (input) {
        if (input.dataset.calificacionBound === 'true') {
            return;
        }
        input.dataset.calificacionBound = 'true';
        input.removeAttribute('onchange');
        input.setAttribute('data-last-saved', (input.value || '').trim());

        // Guardar automáticamente al escribir (con debounce para permitir escribir decimales o el 10)
        input.addEventListener('input', function () {
            var el = this;
            if (el._saveTimer) {
                clearTimeout(el._saveTimer);
            }
            el._saveTimer = setTimeout(function () {
                window.guardarCalificacion(el);
            }, 500);
        });

        // Guardar inmediatamente al salir del campo o confirmar cambio
        input.addEventListener('change', function () {
            if (this._saveTimer) {
                clearTimeout(this._saveTimer);
                this._saveTimer = null;
            }
            window.guardarCalificacion(this);
        });
    });
});

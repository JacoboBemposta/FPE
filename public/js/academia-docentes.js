// public/js/academia-docentes.js
document.addEventListener('DOMContentLoaded', function () {
    // Variables del sistema
    const sistemaSuscripcionesActivo = window.sistemaSuscripcionesActivo || false;
    const userRol = window.userRol || '';

    // Función para abrir el modal de contacto
    window.abrirModalContactoDocente = function (docenteId, docenteNombre, cursoCodigo, cursoNombre, provincia) {
        // Llenar los datos en el modal
        const modalDocenteNombre = document.getElementById('modalDocenteNombre');
        const modalCursoNombre = document.getElementById('modalCursoNombre');
        const modalCursoCodigo = document.getElementById('modalCursoCodigo');
        const modalProvincia = document.getElementById('modalProvincia');

        if (modalDocenteNombre) modalDocenteNombre.textContent = docenteNombre;
        if (modalCursoNombre) modalCursoNombre.textContent = cursoNombre;
        if (modalCursoCodigo) modalCursoCodigo.textContent = cursoCodigo;
        if (modalProvincia) modalProvincia.textContent = provincia;

        const emailInput = document.getElementById('recipientEmail');
        if (emailInput) {
            emailInput.value = '';
            emailInput.placeholder = 'Cargando email del docente...';
            emailInput.disabled = true;

            // Obtener email del docente
            fetch('/academia/obtener-email-docente/' + docenteId)
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Error HTTP: ' + response.status);
                    }
                    return response.json();
                })
                .then(function (data) {
                    if (data.email) {
                        emailInput.value = data.email;
                        emailInput.placeholder = '';
                    } else {
                        emailInput.value = '';
                        emailInput.placeholder = 'Error al obtener email. Ingrese manualmente.';
                    }
                    emailInput.disabled = false;
                })
                .catch(function (error) {
                    console.error('Error:', error);
                    emailInput.value = '';
                    emailInput.placeholder = 'Error de conexión. Ingrese manualmente.';
                    emailInput.disabled = false;
                });
        }

        // Generar mensaje predeterminado
        const emailMessage = document.getElementById('emailMessage');
        if (emailMessage) {
            // Obtener datos del usuario desde meta tags o atributos
            const userName = document.querySelector('meta[name="user-name"]')?.getAttribute('content') || 'Usuario';
            const userEmail = document.querySelector('meta[name="user-email"]')?.getAttribute('content') || 'usuario@email.com';
            const academiaNombre = document.querySelector('meta[name="academia-nombre"]')?.getAttribute('content') || 'nuestra academia';

            const mensajePredeterminado =
                'Estimado/a ' + docenteNombre + ',\n\n' +
                'Nos ponemos en contacto con usted para ofrecerle la posibilidad de impartir el curso "' + cursoNombre + '" (Código: ' + cursoCodigo + ') en nuestra academia "' + academiaNombre + '".\n\n' +
                'Hemos visto su perfil y consideramos que podría adaptarse adecuadamente a este curso.\n\n' +
                'Quedamos a su disposición para cualquier información adicional.\n\n' +
                'Atentamente,\n' + userName + '\n' + userEmail;

            emailMessage.value = mensajePredeterminado;
        }

        // Abrir el modal
        const modalEl = document.getElementById('contactModal');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    };

    // Manejador de clic para los botones "Contactar"
    document.querySelectorAll('.contact-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            // Verificar sistema de suscripciones
            if (sistemaSuscripcionesActivo) {
                // Redirigir a planes
                window.location.href = '/suscripcion/planes';
                return;
            }

            // Obtener datos
            var docenteId = this.getAttribute('data-docente-id');
            var docenteNombre = this.getAttribute('data-docente-nombre');
            var cursoCodigo = this.getAttribute('data-curso-codigo');
            var cursoNombre = this.getAttribute('data-curso-nombre');
            var provincia = this.getAttribute('data-provincia');

            // Llamar a la función global
            if (typeof window.abrirModalContactoDocente === 'function') {
                window.abrirModalContactoDocente(docenteId, docenteNombre, cursoCodigo, cursoNombre, provincia);
            } else {
                console.error('Función abrirModalContactoDocente no definida');
            }
        });
    });

    // Validación del formulario de contacto
    var contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            var mensaje = document.getElementById('emailMessage');
            if (mensaje && !mensaje.value.trim()) {
                e.preventDefault();
                alert('Por favor, complete el mensaje.');
                return;
            }

            var submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Enviando...';
                submitBtn.disabled = true;
            }
        });
    }

    // Limpiar filtros
    var clearBtn = document.getElementById('clearBtn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            window.location.href = this.getAttribute('data-clear-url') || '/academia/docentes';
        });
    }

    // Selector de elementos por página (submit automático)
    var perPageSelect = document.getElementById('perPageSelect');
    if (perPageSelect) {
        perPageSelect.removeAttribute('onchange');
        perPageSelect.addEventListener('change', function () {
            this.form.submit();
        });
    }
});
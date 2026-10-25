// public/js/alumno.js - Funcionalidades para la vista de alumno
document.addEventListener('DOMContentLoaded', function() {


    // =============================================
    // 1. Limpiar filtros de búsqueda
    // =============================================
    var clearBtn = document.getElementById('clearBtn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            var form = document.getElementById('searchForm');
            var inputs = form.querySelectorAll('input[type="text"]');
            var selects = form.querySelectorAll('select');
            
            inputs.forEach(function(input) {
                input.value = '';
            });
            
            selects.forEach(function(select) {
                if (select.name === 'per_page') {
                    select.value = '10';
                } else if (select.name === 'familia') {
                    select.value = '';
                }
            });
            
            form.submit();
        });
    }

    // =============================================
    // 2. Envío automático del select per_page
    // =============================================
    var perPageSelect = document.getElementById('perPageSelect');
    if (perPageSelect) {
        perPageSelect.removeAttribute('onchange');
        perPageSelect.addEventListener('change', function() {
            this.closest('form').submit();
        });
    }

    // =============================================
    // 3. Validación del formulario de búsqueda
    // =============================================
    var searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            var inputs = this.querySelectorAll('input[type="text"]');
            inputs.forEach(function(input) {
                if (input.value) {
                    input.value = input.value.trim().toLowerCase();
                }
            });
        });
    }

    // =============================================
    // 4. Manejo del modal de contacto
    // =============================================
    var userName = document.querySelector('meta[name="user-name"]')?.getAttribute('content') || 'Usuario';
    var userEmail = document.querySelector('meta[name="user-email"]')?.getAttribute('content') || '';

    document.querySelectorAll('.contact-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            // Obtener datos del botón
            var academiaId = this.getAttribute('data-academia-id');
            var academiaNombre = this.getAttribute('data-academia-nombre');
            var cursoNombre = this.getAttribute('data-curso-nombre');
            var municipio = this.getAttribute('data-municipio');
            var inicio = this.getAttribute('data-inicio');
            var fin = this.getAttribute('data-fin');

            // Llenar el modal con la información
            document.getElementById('modalAcademiaNombre').textContent = academiaNombre || '-';
            document.getElementById('modalCursoNombre').textContent = cursoNombre || '-';
            document.getElementById('modalMunicipio').textContent = municipio || '-';
            document.getElementById('modalFechas').textContent = (inicio || 'N/A') + ' - ' + (fin || 'N/A');
            document.getElementById('academia_id').value = academiaId || '';

            // Asunto
            document.getElementById('emailSubject').value = 'Consulta sobre el curso: ' + (cursoNombre || '');

            // Mensaje predeterminado
            var mensaje = 'Estimado equipo responsable,\n\n' +
                'Me pongo en contacto con ustedes para solicitar información adicional sobre el curso «' + cursoNombre + '», que se impartirá en ' + municipio + ' entre las fechas ' + inicio + ' y ' + fin + '.\n\n' +
                'Agradecería que pudieran facilitarme detalles sobre el contenido del programa, los requisitos de inscripción y cualquier otra información que consideren relevante.\n\n' +
                'Quedo a su disposición para ampliar cualquier dato que necesiten y agradezco de antemano su atención.\n\n' +
                'Reciban un cordial saludo,\n' + userName + '\n' + userEmail;
            document.getElementById('emailMessage').value = mensaje;

            // Obtener email de la academia vía AJAX (en segundo plano)
            var emailInput = document.getElementById('recipientEmail');
            if (emailInput && academiaId) {
                emailInput.value = '';
                emailInput.placeholder = 'Cargando email...';
                emailInput.disabled = true;

                fetch('/alumno/obtener-email/' + academiaId, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(response) {
                    if (!response.ok) throw new Error('Error HTTP: ' + response.status);
                    return response.json();
                })
                .then(function(data) {
                    if (data.email) {
                        emailInput.value = data.email;
                        emailInput.placeholder = '';
                    } else {
                        emailInput.placeholder = 'Email no disponible. Ingrese manualmente.';
                    }
                    emailInput.disabled = false;
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    emailInput.placeholder = 'Error de conexión. Ingrese manualmente.';
                    emailInput.disabled = false;
                });
            } else if (emailInput) {
                emailInput.placeholder = 'No hay ID de academia. Ingrese manualmente.';
                emailInput.disabled = false;
            }

            // 🔥 MOSTRAR EL MODAL (usando Bootstrap 5)
            var modal = new bootstrap.Modal(document.getElementById('contactModal'));
            modal.show();
        });
    });

    // =============================================
    // 5. Validación del formulario de contacto
    // =============================================
    var contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            var email = document.getElementById('recipientEmail');
            var asunto = document.getElementById('emailSubject');
            var mensaje = document.getElementById('emailMessage');
            
            if (email && !email.value.trim()) {
                e.preventDefault();
                alert('No se encontró el email de la academia. Por favor, ingréselo manualmente.');
                return;
            }
            
            if (asunto && !asunto.value.trim()) {
                e.preventDefault();
                alert('Por favor, ingrese el asunto del mensaje.');
                return;
            }
            
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

    // =============================================
    // 6. Hacer el campo de email editable si está vacío
    // =============================================
    var emailInput = document.getElementById('recipientEmail');
    if (emailInput) {
        emailInput.addEventListener('focus', function() {
            if (!this.value.trim()) {
                this.readOnly = false;
                this.placeholder = 'Ingrese el email de la academia';
            }
        });
        
        emailInput.addEventListener('blur', function() {
            if (this.value.trim()) {
                this.readOnly = true;
            }
        });
    }
});
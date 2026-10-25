// public/js/profesor.js
document.addEventListener('DOMContentLoaded', function() {


    // Leer variables desde meta tags
    var sistemaActivoMeta = document.querySelector('meta[name="sistema-suscripciones-activo"]');
    window.sistemaSuscripcionesActivo = sistemaActivoMeta ? sistemaActivoMeta.getAttribute('content') === 'true' : false;

    // =============================================
    // 1. Editar curso (para vistas de profesor)
    // =============================================
    document.querySelectorAll('.edit-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var cursoAcademicoId = this.dataset.id;
            var provincia = this.dataset.provincia || '';
            var inicio = this.dataset.inicio || '';
            var fin = this.dataset.fin || '';

            var cursoIdField = document.getElementById('curso_id');
            if (cursoIdField) cursoIdField.value = cursoAcademicoId;
            
            var provinciaField = document.getElementById('provincia');
            if (provinciaField) provinciaField.value = provincia;
            
            var form = document.getElementById('editForm');
            if (form) form.action = '/profesor/curso/' + cursoAcademicoId + '/editar';

            var modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        });
    });

    // =============================================
    // 2. Confirmación de eliminación
    // =============================================
    document.querySelectorAll('form .btn-delete').forEach(function(btn) {
        var form = btn.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!confirm('¿Estás seguro de que deseas eliminar este curso?')) {
                    e.preventDefault();
                }
            });
        }
    });

    // =============================================
    // 3. Botón "Contactar" en academias (profesor)
    // =============================================
    document.querySelectorAll('.contact-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Verificar si el sistema de suscripciones está activo
            if (window.sistemaSuscripcionesActivo) {
                window.location.href = '/suscripcion/planes';
                return;
            }

            var academiaId = this.getAttribute('data-academia-id');
            var academiaNombre = this.getAttribute('data-academia-nombre') || 'Academia';
            var cursoAcadId = this.getAttribute('data-curso-acad-id');
            var cursoNombre = this.getAttribute('data-curso-nombre') || 'Curso';
            var municipio = this.getAttribute('data-municipio') || 'N/A';
            var inicio = this.getAttribute('data-inicio') || 'N/A';
            var fin = this.getAttribute('data-fin') || 'N/A';

            // Llenar modal
            var modalAcademiaNombre = document.getElementById('modalAcademiaNombre');
            if (modalAcademiaNombre) modalAcademiaNombre.textContent = academiaNombre;
            
            var modalCursoNombre = document.getElementById('modalCursoNombre');
            if (modalCursoNombre) modalCursoNombre.textContent = cursoNombre;
            
            var modalMunicipio = document.getElementById('modalMunicipio');
            if (modalMunicipio) modalMunicipio.textContent = municipio;
            
            var modalFechas = document.getElementById('modalFechas');
            if (modalFechas) modalFechas.textContent = inicio + ' - ' + fin;

            var cursoAcadIdInput = document.getElementById('cursoAcadIdInput');
            if (cursoAcadIdInput) cursoAcadIdInput.value = cursoAcadId;

            // Obtener email de la academia
            var emailInput = document.getElementById('recipientEmail');
            if (emailInput) {
                emailInput.value = '';
                emailInput.placeholder = 'Cargando email...';
                emailInput.disabled = true;

                fetch('/profesor/obtener-email/' + academiaId)
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
            }

            // Generar mensaje predeterminado
            var messageField = document.getElementById('emailMessage');
            if (messageField) {
                var userNameMeta = document.querySelector('meta[name="user-name"]');
                var userName = userNameMeta ? userNameMeta.getAttribute('content') : 'Usuario';
                var userEmailMeta = document.querySelector('meta[name="user-email"]');
                var userEmail = userEmailMeta ? userEmailMeta.getAttribute('content') : '';

                messageField.value = 'Estimado equipo de ' + academiaNombre + ',\n\n' +
                    'Me pongo en contacto con ustedes para manifestar mi interés en la plaza docente correspondiente al curso "' + cursoNombre + '", que se impartirá en ' + municipio + '.\n\n' +
                    'Tras revisar la información disponible sobre la formación, prevista entre las fechas ' + inicio + ' y ' + fin + ', considero que mi perfil profesional y experiencia docente se ajustan adecuadamente a los objetivos del curso.\n\n' +
                    'Adjunto mi currículum vitae para su valoración y quedo a su disposición para ampliar cualquier información o concertar una entrevista cuando lo estimen oportuno.\n\n' +
                    'Agradeciendo de antemano su atención, reciban un cordial saludo.\n\n' +
                    userName + '\nEmail: ' + userEmail + '\nTeléfono: [Su teléfono de contacto]';
            }

            // Abrir modal
            var modal = new bootstrap.Modal(document.getElementById('contactModal'));
            modal.show();
        });
    });

    // =============================================
    // 4. Vista previa de archivo adjunto
    // =============================================
    var cvAttachment = document.getElementById('cvAttachment');
    if (cvAttachment) {
        cvAttachment.addEventListener('change', function() {
            var file = this.files[0];
            var filePreview = document.getElementById('filePreview');
            var fileName = document.getElementById('fileName');

            if (file && filePreview && fileName) {
                var maxSize = 10 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('El archivo es demasiado grande. El tamaño máximo permitido es 10MB.');
                    this.value = '';
                    return;
                }
                fileName.textContent = file.name;
                filePreview.style.display = 'block';
            }
        });
    }

    // =============================================
    // 5. Eliminar archivo adjunto
    // =============================================
    window.removeFile = function() {
        var cvAttachment = document.getElementById('cvAttachment');
        var filePreview = document.getElementById('filePreview');
        if (cvAttachment) cvAttachment.value = '';
        if (filePreview) filePreview.style.display = 'none';
    };

    // =============================================
    // 6. Validación del formulario de contacto
    // =============================================
    var contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            var message = document.getElementById('emailMessage');
            var archivo = document.getElementById('cvAttachment');

            if (message && !message.value.trim()) {
                e.preventDefault();
                alert('Por favor, complete el mensaje de candidatura.');
                return;
            }

            if (archivo && archivo.files[0]) {
                var maxSize = 10 * 1024 * 1024;
                if (archivo.files[0].size > maxSize) {
                    e.preventDefault();
                    alert('El archivo es demasiado grande. El tamaño máximo permitido es 10MB.');
                    return;
                }
            }

            var submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Enviando...';
                submitBtn.disabled = true;
            }
        });
    }

    // =============================================
    // 7. Limpiar filtros
    // =============================================
    var clearBtn = document.getElementById('clearBtn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            var form = document.getElementById('searchForm');
            if (form) {
                form.reset();
                form.submit();
            }
        });
    }

    // =============================================
    // 8. Selector de "per_page" con envío automático
    // =============================================
    document.querySelectorAll('select[name="per_page"]').forEach(function(select) {
        select.removeAttribute('onchange');
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
});
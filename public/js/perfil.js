/**
 * perfil.js - Lógica de modales y actualización de perfil
 * (sin código inline en el HTML)
 */

document.addEventListener('DOMContentLoaded', function() {
        window.REDFPE = {
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || '',
        routes: {
            updateRole: document.querySelector('meta[name="update-role-route"]')?.content || ''
        },
        user: {
            rol: document.querySelector('meta[name="user-rol"]')?.content || ''
        }
    };    
    // ==============================
    // 1. FLUJO PARA PRIMERA SELECCIÓN DE ROL
    // ==============================
    const roleSelectionModal = document.getElementById('roleSelectionModal');
    if (roleSelectionModal) {
        const modal = new bootstrap.Modal(roleSelectionModal, {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();

        const roleOptions = document.querySelectorAll('.role-option');
        const confirmBtn = document.getElementById('confirmRoleBtn');

        roleOptions.forEach(option => {
            option.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;

                roleOptions.forEach(opt => {
                    opt.classList.remove('active');
                    opt.style.borderColor = '';
                });

                this.classList.add('active');
                this.style.borderColor = '#4361ee';
            });
        });

        confirmBtn.addEventListener('click', async function() {
            const selectedRole = document.querySelector('input[name="rol"]:checked');

            if (!selectedRole) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Selección requerida',
                    text: 'Por favor, selecciona un tipo de cuenta para continuar.',
                    confirmButtonColor: '#4361ee'
                });
                return;
            }

            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
            this.disabled = true;

            try {
                const response = await fetch(window.REDFPE.routes.updateRole, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.REDFPE.csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        rol: selectedRole.value
                    }),
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({
                        message: 'Error en el servidor'
                    }));
                    throw new Error(errorData.message || `Error ${response.status}`);
                }

                const data = await response.json();

                if (data.success && data.redirect_url) {
                    window.location.href = '/';
                } else {
                    throw new Error(data.message || 'Error al actualizar el rol');
                }
            } catch (error) {
                console.error('Error completo:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Ocurrió un error al procesar la solicitud. Por favor, intenta nuevamente.',
                    confirmButtonColor: '#4361ee'
                });
                this.innerHTML = originalText;
                this.disabled = false;
            }
        });

        // Prevenir cierre del modal sin seleccionar
        roleSelectionModal.addEventListener('hide.bs.modal', function(event) {
            const selectedRole = document.querySelector('input[name="rol"]:checked');
            if (!selectedRole) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Selección requerida',
                    text: 'Debes seleccionar un tipo de cuenta para continuar.',
                    confirmButtonColor: '#4361ee'
                });
            }
        });
    }

    // ==============================
    // 2. FLUJO PARA EDITAR PERFIL
    // ==============================
    const editProfileForm = document.getElementById('editProfileForm');
    if (editProfileForm) {
        // Inicializar estilos de los radio buttons
        function initializeRoleRadios() {
            const roleRadios = document.querySelectorAll('#editProfileForm input[name="rol"]');

            roleRadios.forEach(radio => {
                const label = radio.nextElementSibling;

                if (radio.checked) {
                    label.style.backgroundColor = 'rgba(67, 97, 238, 0.1)';
                    label.style.border = '2px solid #4361ee';
                    label.style.borderRadius = '6px';
                } else {
                    label.style.backgroundColor = '';
                    label.style.border = '2px solid transparent';
                }

                radio.addEventListener('change', function() {
                    document.querySelectorAll('#editProfileForm .form-check-label').forEach(lbl => {
                        lbl.style.backgroundColor = '';
                        lbl.style.border = '2px solid transparent';
                    });

                    if (this.checked) {
                        const selectedLabel = this.nextElementSibling;
                        selectedLabel.style.backgroundColor = 'rgba(67, 97, 238, 0.1)';
                        selectedLabel.style.border = '2px solid #4361ee';
                        selectedLabel.style.borderRadius = '6px';
                    }
                });

                label.addEventListener('click', function(e) {
                    e.preventDefault();
                    const radioBtn = this.previousElementSibling;
                    radioBtn.checked = true;
                    radioBtn.dispatchEvent(new Event('change'));
                });
            });
        }

        const editProfileModal = document.getElementById('editProfileModal');
        if (editProfileModal) {
            editProfileModal.addEventListener('shown.bs.modal', function() {
                initializeRoleRadios();
            });
        }

        editProfileForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const nameField = document.getElementById('editName');
            const selectedRole = document.querySelector('#editProfileForm input[name="rol"]:checked');
            const currentRole = window.REDFPE.user.rol || '';

            if (!nameField.value.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nombre requerido',
                    text: 'Por favor, ingresa tu nombre.',
                    confirmButtonColor: '#4361ee'
                });
                nameField.focus();
                return;
            }

            if (!selectedRole) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Rol requerido',
                    text: 'Por favor, selecciona un rol.',
                    confirmButtonColor: '#4361ee'
                });
                return;
            }

            const isChangingRole = selectedRole.value !== currentRole;
            if (isChangingRole) {
                const result = await Swal.fire({
                    icon: 'question',
                    title: '¿Cambiar rol?',
                    html: `Estás cambiando tu rol de <strong>${currentRole}</strong> a <strong>${selectedRole.value}</strong>.<br><br>Serás redirigido al panel correspondiente.`,
                    showCancelButton: true,
                    confirmButtonText: 'Sí, cambiar rol',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#4361ee',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                });

                if (!result.isConfirmed) {
                    return;
                }
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';
            submitBtn.disabled = true;

            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.REDFPE.csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData,
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
                    if (modal) modal.hide();

                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message,
                        confirmButtonColor: '#4361ee',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        if (data.role_changed && data.redirect_url) {
                            window.location.href = '/';
                        } else {
                            window.location.reload();
                        }
                    }, 2000);
                } else {
                    throw new Error(data.message || `Error ${response.status}: No se pudo actualizar el perfil`);
                }
            } catch (error) {
                console.error('Error completo:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Ocurrió un error al guardar los cambios. Por favor, intenta nuevamente.',
                    confirmButtonColor: '#4361ee'
                });
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Inicializar estilos al cargar la página
        initializeRoleRadios();
    }

    // ==============================
    // 3. FLUJO PARA ELIMINAR PERFIL
    // ==============================
    const confirmationPhrase = document.getElementById('confirmationPhrase');
    const currentPassword = document.getElementById('currentPassword');
    const finalConfirmation = document.getElementById('finalConfirmation');
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    const confirmationsCount = document.getElementById('confirmationsCount');

    const REQUIRED_PHRASE = 'ELIMINAR MI CUENTA';

    function updateConfirmations() {
        let count = 0;

        if (confirmationPhrase.value === REQUIRED_PHRASE) {
            count++;
            confirmationPhrase.classList.remove('is-invalid');
            confirmationPhrase.classList.add('is-valid');
        } else {
            confirmationPhrase.classList.remove('is-valid');
            if (confirmationPhrase.value) {
                confirmationPhrase.classList.add('is-invalid');
            }
        }

        if (currentPassword.value.length >= 8) {
            count++;
            currentPassword.classList.remove('is-invalid');
            currentPassword.classList.add('is-valid');
        } else {
            currentPassword.classList.remove('is-valid');
            if (currentPassword.value) {
                currentPassword.classList.add('is-invalid');
            }
        }

        if (finalConfirmation.checked) {
            count++;
        }

        confirmationsCount.textContent = count;
        confirmDeleteButton.disabled = count !== 3;

        if (count === 3) {
            confirmDeleteButton.classList.remove('btn-secondary');
            confirmDeleteButton.classList.add('btn-danger');
        } else {
            confirmDeleteButton.classList.remove('btn-danger');
            confirmDeleteButton.classList.add('btn-secondary');
        }
    }

    if (confirmationPhrase && currentPassword && finalConfirmation) {
        confirmationPhrase.addEventListener('input', updateConfirmations);
        currentPassword.addEventListener('input', updateConfirmations);
        finalConfirmation.addEventListener('change', updateConfirmations);
    }

    const deleteForm = document.getElementById('deleteAccountForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            if (!confirm('⚠️ ¿ESTÁS ABSOLUTAMENTE SEGURO?\n\nEsta acción eliminará permanentemente:\n• Tu cuenta y todos tus datos\n• Tu historial de cursos\n• Tus suscripciones\n\n¿Deseas continuar con la eliminación?')) {
                e.preventDefault();
                return false;
            }

            confirmDeleteButton.disabled = true;
            confirmDeleteButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> ELIMINANDO...';
        });
    }
});
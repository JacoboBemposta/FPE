// public/js/academia.js - Confirmaciones para acciones de cursos archivados y academia
document.addEventListener('DOMContentLoaded', function() {
    // 1. Confirmación para eliminar (.btn-danger y .btn-delete)
    document.querySelectorAll('.btn-danger, .btn-delete').forEach(function(btn) {
        var form = btn.closest('form');
        if (form) {
            form.removeAttribute('onsubmit');
            form.addEventListener('submit', function(e) {
                if (!confirm('¿Estás seguro de eliminar definitivamente este curso?')) {
                    e.preventDefault();
                }
            });
        }
    });

    // 2. Confirmación para restaurar (.btn-restore)
    document.querySelectorAll('.btn-restore').forEach(function(btn) {
        var form = btn.closest('form');
        if (form) {
            form.removeAttribute('onsubmit');
            form.addEventListener('submit', function(e) {
                if (!confirm('¿Deseas restaurar este curso a Mis Cursos?')) {
                    e.preventDefault();
                }
            });
        }
    });
});

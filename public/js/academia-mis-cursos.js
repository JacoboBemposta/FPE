// public/js/academia-mis-cursos.js
document.addEventListener('DOMContentLoaded', function() {
    // Evento para abrir el modal de edición de curso
    const editButtons = document.querySelectorAll('.edit-btn');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const cursoAcademicoId = this.getAttribute('data-id');
            const municipio = this.getAttribute('data-municipio');
            const provincia = this.getAttribute('data-provincia');
            const inicio = this.getAttribute('data-inicio');
            const fin = this.getAttribute('data-fin');
            const route = this.getAttribute('data-route');

            // Asignar los valores al formulario del modal de curso
            document.getElementById('curso_id').value = cursoAcademicoId;
            document.getElementById('municipio').value = municipio;
            document.getElementById('provincia').value = provincia;
            document.getElementById('inicio').value = inicio;
            document.getElementById('fin').value = fin;

            // Actualizar el action del formulario con la ruta correcta
            document.getElementById('editForm').setAttribute('action', route);

            // Mostrar el modal de edición de curso usando Bootstrap 5
            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        });
    });

    // Confirmación para archivar curso (eliminar onclick inline)
    document.querySelectorAll('form .btn-warning').forEach(function(btn) {
        btn.closest('form').addEventListener('submit', function(e) {
            if (!confirm('¿Estás seguro de que deseas archivar este curso?')) {
                e.preventDefault();
            }
        });
    });
});
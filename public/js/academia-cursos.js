// public/js/academia-cursos.js
document.addEventListener('DOMContentLoaded', function() {

    // =============================================
    // 2. Manejar el estado del acordeón (no es necesario para CSP, pero para mantener la funcionalidad)
    //    Bootstrap ya maneja el toggle, pero podemos añadir logs si son necesarios
    // =============================================
    const accordionButtons = document.querySelectorAll('.family-header-modern .btn-link');
    accordionButtons.forEach(function(button) {
        // Eliminar el onclick que pueda tener (si existe)
        button.removeAttribute('onclick');

    });

});
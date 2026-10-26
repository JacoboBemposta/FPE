// public/js/config.js
// Activar hojas de estilo no críticas cargadas de forma asíncrona (sin bloquear el renderizado ni usar inline onload por CSP)
document.querySelectorAll('link[data-async-css]').forEach(function (link) {
    link.media = 'all';
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('link[data-async-css]').forEach(function (link) {
        link.media = 'all';
    });

    window.REDFPE = {
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || '',
        routes: {
            updateRole: document.querySelector('meta[name="update-role-route"]')?.content || ''
        },
        user: {
            rol: document.querySelector('meta[name="user-rol"]')?.content || ''
        }
    };
});

// public/js/config.js
// Activar hojas de estilo cargadas de forma asíncrona una vez descargadas (sin bloquear el renderizado ni usar inline onload por CSP)
(function () {
    function activateAsyncStylesheets(forceAll) {
        document.querySelectorAll('link[data-async-css]').forEach(function (link) {
            if (link.media === 'all') {
                return;
            }
            if (forceAll || link.sheet) {
                link.media = 'all';
            } else if (!link.dataset.asyncBound) {
                link.dataset.asyncBound = '1';
                link.addEventListener('load', function () {
                    link.media = 'all';
                }, { once: true });
            }
        });
    }

    activateAsyncStylesheets(false);

    document.addEventListener('DOMContentLoaded', function () {
        activateAsyncStylesheets(false);

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

    window.addEventListener('load', function () {
        activateAsyncStylesheets(true);
    });
})();

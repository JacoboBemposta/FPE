// public/js/config.js
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
});
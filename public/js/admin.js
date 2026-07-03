// =====================================================
// admin.js - Carga asíncrona de cursos, módulos y unidades
// (sin inline event handlers para cumplir con CSP)
// =====================================================

// 1. Registrar listeners de acordeón
document.querySelectorAll('.accordion-collapse').forEach(collapse => {
    collapse.addEventListener('shown.bs.collapse', function () {
        const familiaId = this.id.replace('familiaCollapse', '');
        loadCursos(familiaId);
    });
});

// 2. Listeners para modales genéricos
const editModal = document.getElementById('editarCursoModal');
if (editModal) {
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;
        const cursoId = button.getAttribute('data-curso-id');
        document.getElementById('editCursoId').value = cursoId;
        document.getElementById('editCursoCodigo').value = button.getAttribute('data-codigo') || '';
        document.getElementById('editCursoNombre').value = button.getAttribute('data-nombre') || '';
        document.getElementById('editCursoCualificacion').value = button.getAttribute('data-cualificacion') || '';
        document.getElementById('editCursoHoras').value = button.getAttribute('data-horas') || '';
        document.getElementById('editCursoFamilia').value = button.getAttribute('data-familia-id') || '';
        document.getElementById('editarCursoForm').action = `/admin/cursos/${cursoId}`;
    });
}

const addModuloModal = document.getElementById('agregarModuloModal');
if (addModuloModal) {
    addModuloModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;
        const cursoId = button.getAttribute('data-curso-id');
        document.getElementById('addModuloCursoId').value = cursoId;
        document.getElementById('addModuloCodigo').value = '';
        document.getElementById('addModuloNombre').value = '';
        document.getElementById('addModuloHoras').value = '';
        document.getElementById('agregarModuloForm').action = `/admin/cursos/${cursoId}/modulos`;
    });
}

const addUnidadModal = document.getElementById('agregarUnidadModal');
if (addUnidadModal) {
    addUnidadModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;
        const moduloId = button.getAttribute('data-modulo-id');
        document.getElementById('addUnidadModuloId').value = moduloId;
        document.getElementById('addUnidadCodigo').value = '';
        document.getElementById('addUnidadNombre').value = '';
        document.getElementById('addUnidadHoras').value = '';
    });
}

// =============================================
// 3. Función principal: cargar cursos
// =============================================
window.loadCursos = function(familiaId) {
    const container = document.getElementById(`cursos-container-${familiaId}`);
    if (!container) {
        console.error('❌ Contenedor no encontrado');
        return;
    }
    if (container.dataset.loaded === 'true') {
        return;
    }

    const url = container.dataset.url;
    container.innerHTML = `
        <div class="spinner-wrapper">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando cursos...</span>
            </div>
        </div>
    `;

    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
            });
        }
        return response.json();
    })
    .then(cursos => {
        if (!Array.isArray(cursos)) throw new Error('Respuesta no es array');
        if (cursos.length === 0) {
            container.innerHTML = `<div class="alert alert-info text-center"><i class="fas fa-info-circle"></i> No hay cursos en esta familia.</div>`;
        } else {
            // Generar HTML SIN onclick y SIN confirm en línea
            container.innerHTML = cursos.map(curso => `
                <div class="curso-item card mb-3" data-curso-id="${curso.id}">
                    <div class="card-header bg-light curso-header" data-curso-id="${curso.id}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="curso-title">
                                <i class="fas fa-book me-2"></i>
                                <strong>${curso.codigo}</strong> - ${curso.nombre}
                                <span class="badge bg-secondary ms-2">${curso.horas || 0}h</span>
                                <span class="badge bg-info ms-2">${curso.modulos_count || 0} módulos</span>
                                <i class="fas fa-chevron-down ms-2 toggle-icon" id="toggle-icon-${curso.id}"></i>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editarCursoModal"
                                        data-curso-id="${curso.id}"
                                        data-codigo="${curso.codigo}"
                                        data-nombre="${curso.nombre}"
                                        data-cualificacion="${curso.cualificacion || ''}"
                                        data-horas="${curso.horas || ''}"
                                        data-familia-id="${curso.familia_profesional_id}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <form action="/admin/cursos/${curso.id}" method="POST" class="d-inline delete-curso-form">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body modulos-body" id="modulos-body-${curso.id}" style="display: none;">
                        <h6 class="border-bottom pb-2"><i class="fas fa-list-ul me-2"></i>Módulos <span class="badge bg-info">${curso.modulos_count || 0}</span></h6>
                        <div id="modulos-container-${curso.id}" class="modulos-container">
                            <div class="spinner-wrapper" style="padding:1rem 0;">
                                <div class="spinner-border spinner-border-sm text-secondary"></div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-primary btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#agregarModuloModal"
                                    data-curso-id="${curso.id}">
                                <i class="fas fa-plus"></i> Añadir Módulo
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');

            // --- Asignar event listeners a los elementos dinámicos ---

            // 3a. Toggle de módulos al hacer click en .curso-header
            container.querySelectorAll('.curso-header').forEach(header => {
                header.addEventListener('click', function(e) {
                    const cursoId = this.dataset.cursoId;
                    if (cursoId) {
                        toggleModulos(cursoId);
                    }
                });
            });

            // 3b. Confirmación para eliminar curso
            container.querySelectorAll('.delete-curso-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('¿Estás seguro de eliminar este curso?')) {
                        e.preventDefault();
                    }
                });
            });
        }
        container.dataset.loaded = 'true';
    })
    .catch(error => {
        console.error('❌ Error en loadCursos:', error);
        container.innerHTML = `
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle"></i> Error al cargar los cursos.<br>
                <small>${error.message}</small>
                <div class="mt-2">
                    <button class="btn btn-sm btn-warning" onclick="loadCursos(${familiaId})">
                        <i class="fas fa-redo"></i> Reintentar
                    </button>
                </div>
            </div>
        `;
    });
};

// =============================================
// 4. Toggle de módulos y carga de módulos
// =============================================
window.toggleModulos = function(cursoId) {
    const body = document.getElementById(`modulos-body-${cursoId}`);
    const icon = document.getElementById(`toggle-icon-${cursoId}`);
    if (!body) return;
    if (body.style.display === 'none' || body.style.display === '') {
        body.style.display = 'block';
        if (icon) { icon.classList.remove('fa-chevron-down'); icon.classList.add('fa-chevron-up'); }
        const container = document.getElementById(`modulos-container-${cursoId}`);
        if (container && container.dataset.loaded !== 'true') {
            loadModulos(cursoId);
        }
    } else {
        body.style.display = 'none';
        if (icon) { icon.classList.remove('fa-chevron-up'); icon.classList.add('fa-chevron-down'); }
    }
};

window.loadModulos = function(cursoId) {
    const container = document.getElementById(`modulos-container-${cursoId}`);
    if (!container || container.dataset.loaded === 'true') return;
    const url = `/admin/cursos/${cursoId}/modulos`;

    container.innerHTML = `<div class="spinner-wrapper" style="padding:1rem 0;"><div class="spinner-border spinner-border-sm text-secondary"></div></div>`;

    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        return response.json();
    })
    .then(modulos => {
        if (!Array.isArray(modulos)) throw new Error('Respuesta no es array');
        if (modulos.length === 0) {
            container.innerHTML = `<p class="text-muted">Este curso no tiene módulos.</p>`;
        } else {
            // Generar HTML SIN onclick
            container.innerHTML = modulos.map(modulo => {
                const deleteUrl = `/admin/cursos/${cursoId}/modulos/${modulo.id}`;
                return `
                    <div class="modulo-item border rounded p-3 mb-2" data-modulo-id="${modulo.id}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>${modulo.codigo} - ${modulo.nombre}</strong>
                                <span class="badge bg-light text-dark ms-2">${modulo.horas || 0}h</span>
                                <span class="badge bg-info ms-2">${modulo.unidades_count || 0} unidades</span>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#agregarUnidadModal"
                                        data-modulo-id="${modulo.id}">
                                    <i class="fas fa-plus"></i> Unidad
                                </button>
                                <form action="${deleteUrl}" method="POST" class="d-inline delete-modulo-form">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div id="unidades-container-${modulo.id}" class="unidades-container mt-2">
                            <button class="btn btn-outline-secondary btn-sm load-unidades-btn" data-modulo-id="${modulo.id}">
                                <i class="fas fa-sync"></i> Cargar Unidades
                            </button>
                        </div>
                    </div>
                `;
            }).join('');

            // --- Asignar event listeners ---

            // 4a. Eliminar módulo con confirmación
            container.querySelectorAll('.delete-modulo-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('¿Estás seguro de eliminar este módulo?')) {
                        e.preventDefault();
                    }
                });
            });

            // 4b. Botón "Cargar Unidades"
            container.querySelectorAll('.load-unidades-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const moduloId = this.dataset.moduloId;
                    if (moduloId) {
                        loadUnidades(moduloId);
                    }
                });
            });
        }
        container.dataset.loaded = 'true';
    })
    .catch(error => {
        console.error('❌ Error cargando módulos:', error);
        container.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
    });
};

// =============================================
// 5. Carga de unidades
// =============================================
window.loadUnidades = function(moduloId) {
    const container = document.getElementById(`unidades-container-${moduloId}`);
    if (!container || container.dataset.loaded === 'true') return;
    const url = `/admin/modulos/${moduloId}/unidades`;

    container.innerHTML = `<div class="spinner-wrapper" style="padding:0.5rem 0;"><div class="spinner-border spinner-border-sm text-secondary"></div></div>`;

    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        return response.json();
    })
    .then(unidades => {
        if (!Array.isArray(unidades)) throw new Error('Respuesta no es array');
        if (unidades.length === 0) {
            container.innerHTML = `<p class="text-muted"><small>No hay unidades formativas</small></p>`;
        } else {
            const sorted = unidades.sort((a,b) => (a.codigo||'').localeCompare(b.codigo||''));
            container.innerHTML = `
                <div>
                    <strong class="text-muted">Unidades formativas:</strong>
                    <ul class="list-group mt-1">
                        ${sorted.map(u => `
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <span><strong>${u.codigo}</strong> - ${u.nombre} <span class="badge bg-light text-dark ms-2">${u.horas||0}h</span></span>
                                <form action="/admin/unidades/${u.id}" method="POST" class="d-inline delete-unidad-form">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </li>
                        `).join('')}
                    </ul>
                </div>
            `;

            // Confirmación para eliminar unidad
            container.querySelectorAll('.delete-unidad-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('¿Estás seguro de eliminar esta unidad?')) {
                        e.preventDefault();
                    }
                });
            });
        }
        container.dataset.loaded = 'true';
    })
    .catch(error => {
        console.error('❌ Error cargando unidades:', error);
        container.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
    });
};

// =============================================
// 6. Función para el switch de suscripciones
// =============================================
window.toggleSistemaSuscripciones = function(checkbox) {
    const estado = document.getElementById('estadoActual');
    estado.textContent = 'CAMBIO...';
    estado.className = 'badge bg-warning ms-2';
    checkbox.disabled = true;

    fetch('/admin/toggle-suscripciones', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ activo: checkbox.checked })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            estado.textContent = checkbox.checked ? 'ACTIVO' : 'INACTIVO';
            estado.className = `badge ms-2 ${checkbox.checked ? 'bg-success' : 'bg-secondary'}`;
            mostrarNotificacion('Sistema ' + (checkbox.checked ? 'activado' : 'desactivado'), 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            throw new Error(data.message || 'Error');
        }
    })
    .catch(error => {
        checkbox.checked = !checkbox.checked;
        estado.textContent = checkbox.checked ? 'ACTIVO' : 'INACTIVO';
        estado.className = `badge ms-2 ${checkbox.checked ? 'bg-success' : 'bg-secondary'}`;
        mostrarNotificacion('Error: ' + error.message, 'error');
    })
    .finally(() => checkbox.disabled = false);
};

window.mostrarNotificacion = function(mensaje, tipo) {
    const div = document.createElement('div');
    div.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
    div.style.top = '20px';
    div.style.right = '20px';
    div.style.zIndex = '9999';
    div.innerHTML = `${mensaje} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    document.body.appendChild(div);
    setTimeout(() => div.remove(), 3000);
};

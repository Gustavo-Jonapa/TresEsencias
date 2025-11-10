<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold" style="color: #8C451C;">
            <i class="bi bi-people"></i> Gestión de Empleados
        </h1>
        <div>
            <a href="index.php?controller=administrador" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Panel
            </a>
            <button class="btn text-white" style="background-color: #F28322;" data-bs-toggle="modal" data-bs-target="#modalNuevoEmpleado">
                <i class="bi bi-person-plus"></i> Nuevo Empleado
            </button>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-black bg-light">
                <div class="card-body">
                    <h6 class="card-title">Total Empleados</h6>
                    <h2 class="mb-0"><?php echo count($empleados); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-black bg-light">
                <div class="card-body">
                    <h6 class="card-title">Meseros</h6>
                    <h2 class="mb-0">
                        <?php 
                        echo count(array_filter($empleados, function($e) { 
                            return isset($e['PUESTO']) && $e['PUESTO'] === 'Mesero'; 
                        })); 
                        ?>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-black bg-light">
                <div class="card-body">
                    <h6 class="card-title">Cocineros</h6>
                    <h2 class="mb-0">
                        <?php 
                        echo count(array_filter($empleados, function($e) { 
                            return isset($e['PUESTO']) && $e['PUESTO'] === 'Cocinero'; 
                        })); 
                        ?>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-black bg-light">
                <div class="card-body">
                    <h6 class="card-title">Otros</h6>
                    <h2 class="mb-0">
                        <?php 
                        echo count(array_filter($empleados, function($e) { 
                            return isset($e['PUESTO']) && !in_array($e['PUESTO'], ['Mesero', 'Cocinero']); 
                        })); 
                        ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" id="filtroNombre" class="form-control" placeholder="Buscar por nombre...">
                </div>
                <div class="col-md-3">
                    <select id="filtroPuesto" class="form-select">
                        <option value="">Todos los puestos</option>
                        <option value="Mesero">Mesero</option>
                        <option value="Cocinero">Cocinero</option>
                        <option value="Chef">Chef</option>
                        <option value="Recepcionista">Recepcionista</option>
                        <option value="Cajero">Cajero</option>
                        <option value="Gerente">Gerente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn w-100" style="background-color: #8C451C; color: white;" onclick="filtrarEmpleados()">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Empleados -->
    <div class="card shadow-sm">
        <div class="card-header" style="background-color: #8C451C; color: white;">
            <h5 class="mb-0"><i class="bi bi-people"></i> Lista de Empleados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tablaEmpleados">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Puesto</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Salario</th>
                            <th>Fecha Contratación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($empleados)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mt-2">No hay empleados registrados</p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($empleados as $empleado): ?>
                            <tr>
                                <td><?php echo $empleado['ID_EMPLEADO'] ?? ''; ?></td>
                                <td>
                                    <strong><?php echo ($empleado['NOMBRE'] ?? '') . ' ' . ($empleado['APELLIDO'] ?? ''); ?></strong>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: #F28322;">
                                        <?php echo $empleado['PUESTO'] ?? ''; ?>
                                    </span>
                                </td>
                                <td><?php echo $empleado['TELEFONO'] ?? ''; ?></td>
                                <td><?php echo $empleado['EMAIL'] ?? ''; ?></td>
                                <td>$<?php echo number_format($empleado['SALARIO'] ?? 0, 2); ?></td>
                                <td><?php echo isset($empleado['FECHA_CONTRATACION']) ? date('d/m/Y', strtotime($empleado['FECHA_CONTRATACION'])) : ''; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info text-white" onclick="verEmpleado(<?php echo $empleado['ID_EMPLEADO'] ?? 0; ?>)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editarEmpleado(<?php echo $empleado['ID_EMPLEADO'] ?? 0; ?>)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="eliminarEmpleado(<?php echo $empleado['ID_EMPLEADO'] ?? 0; ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Empleado -->
<div class="modal fade" id="modalNuevoEmpleado" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F28322; color: white;">
                <h5 class="modal-title"><i class="bi bi-person-plus"></i> Registrar Nuevo Empleado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoEmpleado" method="POST" action="index.php?controller=administrador&action=crearEmpleado">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre *</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Apellido *</label>
                            <input type="text" name="apellido" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Puesto *</label>
                            <select name="puesto" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="Mesero">Mesero</option>
                                <option value="Cocinero">Cocinero</option>
                                <option value="Chef">Chef</option>
                                <option value="Recepcionista">Recepcionista</option>
                                <option value="Cajero">Cajero</option>
                                <option value="Gerente">Gerente</option>
                                <option value="Limpieza">Limpieza</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Teléfono *</label>
                            <input type="tel" name="telefono" class="form-control" required placeholder="961-123-4567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="empleado@ejemplo.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Salario Mensual *</label>
                            <input type="number" name="salario" class="form-control" required step="0.01" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Contratación *</label>
                            <input type="date" name="fecha_contratacion" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Colonia/Dirección</label>
                            <input type="number" name="id_colonia" class="form-control" value="1">
                            <small class="text-muted">ID de la colonia en el sistema</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formNuevoEmpleado" class="btn text-white" style="background-color: #F28322;">
                    <i class="bi bi-save"></i> Guardar Empleado
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Empleado -->
<div class="modal fade" id="modalEditarEmpleado" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #8C451C; color: white;">
                <h5 class="modal-title"><i class="bi bi-pencil"></i> Editar Empleado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarEmpleado" method="POST" action="index.php?controller=administrador&action=editarEmpleado">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre *</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Apellido *</label>
                            <input type="text" name="apellido" id="edit_apellido" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Puesto *</label>
                            <select name="puesto" id="edit_puesto" class="form-select" required>
                                <option value="Mesero">Mesero</option>
                                <option value="Cocinero">Cocinero</option>
                                <option value="Chef">Chef</option>
                                <option value="Recepcionista">Recepcionista</option>
                                <option value="Cajero">Cajero</option>
                                <option value="Gerente">Gerente</option>
                                <option value="Limpieza">Limpieza</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Teléfono *</label>
                            <input type="tel" name="telefono" id="edit_telefono" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Salario Mensual *</label>
                            <input type="number" name="salario" id="edit_salario" class="form-control" required step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Contratación *</label>
                            <input type="date" name="fecha_contratacion" id="edit_fecha_contratacion" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Colonia/Dirección</label>
                            <input type="number" name="id_colonia" id="edit_id_colonia" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formEditarEmpleado" class="btn text-white" style="background-color: #8C451C;">
                    <i class="bi bi-save"></i> Actualizar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function filtrarEmpleados() {
    const nombre = document.getElementById('filtroNombre').value.toLowerCase();
    const puesto = document.getElementById('filtroPuesto').value;
    const filas = document.querySelectorAll('#tablaEmpleados tbody tr');
    
    filas.forEach(fila => {
        const textoFila = fila.textContent.toLowerCase();
        const puestoFila = fila.querySelector('.badge')?.textContent || '';
        
        const coincideNombre = nombre === '' || textoFila.includes(nombre);
        const coincidePuesto = puesto === '' || puestoFila === puesto;
        
        if (coincideNombre && coincidePuesto) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

function limpiarFiltros() {
    document.getElementById('filtroNombre').value = '';
    document.getElementById('filtroPuesto').value = '';
    filtrarEmpleados();
}

function verEmpleado(id) {
    alert('Ver detalles del empleado ID: ' + id);
}

function editarEmpleado(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalEditarEmpleado'));
    modal.show();
}

function eliminarEmpleado(id) {
    if (confirm('¿Estás seguro de eliminar este empleado?')) {
        fetch('index.php?controller=administrador&action=eliminarEmpleado', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.Status === 'OK') {
                alert('Empleado eliminado exitosamente');
                location.reload();
            } else {
                alert('Error: ' + data.Mensaje);
            }
        });
    }
}
</script>
<script>
function filtrarEmpleados() {
    const nombre = document.getElementById('filtroNombre').value.toLowerCase();
    const puesto = document.getElementById('filtroPuesto').value;
    const filas = document.querySelectorAll('#tablaEmpleados tbody tr');
    
    filas.forEach(fila => {
        if (fila.querySelector('td[colspan]')) {
            return;
        }
        
        const textoFila = fila.textContent.toLowerCase();
        const puestoFila = fila.querySelector('.badge')?.textContent || '';
        
        const coincideNombre = nombre === '' || textoFila.includes(nombre);
        const coincidePuesto = puesto === '' || puestoFila === puesto;
        
        if (coincideNombre && coincidePuesto) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

function limpiarFiltros() {
    document.getElementById('filtroNombre').value = '';
    document.getElementById('filtroPuesto').value = '';
    filtrarEmpleados();
}


function verEmpleado(id) {
    fetch('index.php?controller=administrador&action=obtenerDetalleEmpleado&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.Status === 'OK') {
                const emp = data.empleado;
                const antiguedad = emp.ANTIGUEDAD;
                
                let antiguedadTexto = '';
                if (antiguedad) {
                    antiguedadTexto = `${antiguedad.años} años, ${antiguedad.meses} meses y ${antiguedad.dias} días`;
                }
                
                const detalleHTML = `
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <strong>Empleado #${emp.ID_EMPLEADO}</strong>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Nombre Completo:</strong>
                            <p class="mb-0">${emp.NOMBRE} ${emp.APELLIDO}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Puesto:</strong>
                            <p class="mb-0">
                                <span class="badge" style="background-color: #F28322; font-size: 1em;">
                                    ${emp.PUESTO}
                                </span>
                            </p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Teléfono:</strong>
                            <p class="mb-0">
                                <a href="tel:${emp.TELEFONO}" class="text-decoration-none">
                                    <i class="bi bi-telephone"></i> ${emp.TELEFONO}
                                </a>
                            </p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p class="mb-0">
                                ${emp.EMAIL ? 
                                    `<a href="mailto:${emp.EMAIL}" class="text-decoration-none">
                                        <i class="bi bi-envelope"></i> ${emp.EMAIL}
                                    </a>` : 
                                    '<span class="text-muted">No registrado</span>'}
                            </p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Salario Mensual:</strong>
                            <p class="mb-0 text-success fw-bold">
                                <i class="bi bi-cash-stack"></i> $${parseFloat(emp.SALARIO).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                            </p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Salario Anual:</strong>
                            <p class="mb-0 text-primary fw-bold">
                                $${(parseFloat(emp.SALARIO) * 12).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                            </p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Fecha de Contratación:</strong>
                            <p class="mb-0">${formatearFecha(emp.FECHA_CONTRATACION)}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Antigüedad:</strong>
                            <p class="mb-0">
                                <span class="badge bg-primary">${antiguedadTexto}</span>
                            </p>
                        </div>
                    </div>
                `;
                
                mostrarModalDetalle('Detalles del Empleado', detalleHTML);
            } else {
                alert('Error: ' + data.Mensaje);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al obtener detalles del empleado');
        });
}


function editarEmpleado(id) {
    fetch('index.php?controller=administrador&action=obtenerEmpleadoParaEditar&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.Status === 'OK') {
                const emp = data.empleado;
                
                document.getElementById('edit_id').value = emp.ID_EMPLEADO;
                document.getElementById('edit_nombre').value = emp.NOMBRE;
                document.getElementById('edit_apellido').value = emp.APELLIDO;
                document.getElementById('edit_puesto').value = emp.PUESTO;
                document.getElementById('edit_telefono').value = emp.TELEFONO;
                document.getElementById('edit_email').value = emp.EMAIL || '';
                document.getElementById('edit_salario').value = emp.SALARIO;
                document.getElementById('edit_fecha_contratacion').value = emp.FECHA_CONTRATACION;
                document.getElementById('edit_id_colonia').value = emp.ID_COLONIA || 1;
                
                const modal = new bootstrap.Modal(document.getElementById('modalEditarEmpleado'));
                modal.show();
            } else {
                alert('Error: ' + data.Mensaje);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar datos del empleado');
        });
}


function eliminarEmpleado(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este empleado?\n\nEsta acción no se puede deshacer.')) {
        const formData = new FormData();
        formData.append('id', id);
        
        fetch('index.php?controller=administrador&action=eliminarEmpleado', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.Status === 'OK') {
                mostrarMensaje('Empleado eliminado exitosamente', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                alert('Error: ' + (data.Mensaje || 'No se pudo eliminar el empleado'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión. Por favor intente nuevamente.');
        });
    }
}


function formatearFecha(fecha) {
    if (!fecha) return 'No disponible';
    
    const date = new Date(fecha + 'T00:00:00');
    const opciones = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('es-MX', opciones);
}


function mostrarModalDetalle(titulo, contenidoHTML) {
    const modalAnterior = document.getElementById('modalDetalleEmpleado');
    if (modalAnterior) {
        modalAnterior.remove();
    }
    
    const modalHTML = `
        <div class="modal fade" id="modalDetalleEmpleado" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-info-circle"></i> ${titulo}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${contenidoHTML}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    const modal = new bootstrap.Modal(document.getElementById('modalDetalleEmpleado'));
    modal.show();
    
    document.getElementById('modalDetalleEmpleado').addEventListener('hidden.bs.modal', function () {
        this.remove();
    });
}


function mostrarMensaje(mensaje, tipo = 'info') {
    const iconos = {
        'success': 'check-circle',
        'danger': 'x-circle',
        'warning': 'exclamation-triangle',
        'info': 'info-circle'
    };
    
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
    alerta.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alerta.innerHTML = `
        <i class="bi bi-${iconos[tipo] || 'info-circle'}"></i>
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alerta);
    
    setTimeout(() => {
        alerta.remove();
    }, 3000);
}


document.querySelectorAll('input[type="tel"]').forEach(input => {
    input.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9-]/g, '');
    });
});

document.querySelectorAll('input[name="salario"]').forEach(input => {
    input.addEventListener('input', function() {
        if (parseFloat(this.value) < 0) {
            this.value = 0;
        }
    });
});

let timeoutEmail;
document.querySelectorAll('input[type="email"]').forEach(input => {
    input.addEventListener('blur', function() {
        const email = this.value.trim();
        const idEmpleado = document.getElementById('edit_id')?.value || null;
        
        if (email && email.includes('@')) {
            clearTimeout(timeoutEmail);
            timeoutEmail = setTimeout(() => {
                validarEmailUnico(email, idEmpleado);
            }, 500);
        }
    });
});

function validarEmailUnico(email, excluirId = null) {
    let url = 'index.php?controller=administrador&action=validarEmailEmpleado&email=' + encodeURIComponent(email);
    if (excluirId) {
        url += '&excluir=' + excluirId;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.Status === 'OK' && data.existe) {
                mostrarMensaje('Este email ya está registrado para otro empleado', 'warning');
            }
        })
        .catch(error => console.error('Error:', error));
}


document.getElementById('modalNuevoEmpleado')?.addEventListener('hidden.bs.modal', function () {
    document.getElementById('formNuevoEmpleado').reset();
    document.querySelector('#formNuevoEmpleado input[name="fecha_contratacion"]').value = '<?php echo date('Y-m-d'); ?>';
});

document.getElementById('modalEditarEmpleado')?.addEventListener('hidden.bs.modal', function () {
    document.getElementById('formEditarEmpleado').reset();
});


let timeoutBusqueda;
document.getElementById('filtroNombre')?.addEventListener('input', function() {
    clearTimeout(timeoutBusqueda);
    timeoutBusqueda = setTimeout(() => {
        filtrarEmpleados();
    }, 300);
});

document.getElementById('filtroPuesto')?.addEventListener('change', function() {
    filtrarEmpleados();
});

function exportarCSV() {
    window.location.href = 'index.php?controller=administrador&action=exportarEmpleadosCSV';
}


function mostrarResumenNomina() {
    fetch('index.php?controller=administrador&action=obtenerEstadisticasEmpleados')
        .then(response => response.json())
        .then(data => {
            if (data.Status === 'OK') {
                const stats = data.estadisticas;
                
                const resumenHTML = `
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6>Total Empleados</h6>
                                    <h3>${stats.total_empleados}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6>Puestos Diferentes</h6>
                                    <h3>${stats.puestos_diferentes}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h6>Nómina Mensual</h6>
                                    <h3>$${parseFloat(stats.nomina_total).toLocaleString('es-MX', {minimumFractionDigits: 2})}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h6>Salario Promedio</h6>
                                    <h3>$${parseFloat(stats.salario_promedio).toLocaleString('es-MX', {minimumFractionDigits: 2})}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-light">
                                <strong>Nómina Anual Proyectada:</strong>
                                $${(parseFloat(stats.nomina_total) * 12).toLocaleString('es-MX', {minimumFractionDigits: 2})}
                            </div>
                        </div>
                    </div>
                `;
                
                mostrarModalDetalle('Resumen de Nómina', resumenHTML);
            }
        })
        .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistema de Empleados cargado correctamente');
    
    <?php if (isset($_SESSION['mensaje'])): ?>
        mostrarMensaje('<?php echo $_SESSION['mensaje']; ?>', 'success');
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        mostrarMensaje('<?php echo $_SESSION['error']; ?>', 'danger');
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
});
</script>

<style>
.table tbody tr:hover {
    background-color: rgba(140, 69, 28, 0.05);
}

.badge {
    font-size: 0.85em;
    padding: 0.35em 0.65em;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.alert.position-fixed {
    animation: slideInRight 0.3s ease-out;
}

.form-control:focus,
.form-select:focus {
    border-color: #F28322;
    box-shadow: 0 0 0 0.25rem rgba(242, 131, 34, 0.25);
}
</style>
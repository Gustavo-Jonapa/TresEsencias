<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold" style="color: #8C451C;">Gestión de Clientes</h1>
        <div>
            <a href="index.php?controller=recepcion" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Panel
            </a>
            <button class="btn text-white" style="background-color: #F28322;" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">
                <i class="bi bi-person-plus"></i> Nuevo Cliente
            </button>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-10">
                    <input type="text" id="inputBusqueda" class="form-control form-control-lg" 
                           placeholder="Buscar por nombre, teléfono o email...">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-lg w-100" style="background-color: #8C451C; color: white;" onclick="buscarClientes()">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color: #8C451C; color: white;">
            <h5 class="mb-0"><i class="bi bi-people"></i> Lista de Clientes</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($clientes)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?php echo $cliente['ID_CLIENTE']; ?></td>
                            <td><?php echo htmlspecialchars($cliente['NOMBRE']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['APELLIDO'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($cliente['TELEFONO']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['EMAIL'] ?? 'N/A'); ?></td>
                            <td>
                                <?php 
                                if (isset($cliente['FECHA_REGISTRO'])) {
                                    echo date('d/m/Y', strtotime($cliente['FECHA_REGISTRO']));
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info text-white" onclick="verCliente(<?php echo $cliente['ID_CLIENTE']; ?>, '<?php echo htmlspecialchars($cliente['NOMBRE'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($cliente['APELLIDO'] ?? '', ENT_QUOTES); ?>', '<?php echo htmlspecialchars($cliente['TELEFONO'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($cliente['EMAIL'] ?? '', ENT_QUOTES); ?>')">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-success" onclick="crearReservacionCliente(<?php echo $cliente['ID_CLIENTE']; ?>, '<?php echo htmlspecialchars($cliente['NOMBRE'] . ' ' . ($cliente['APELLIDO'] ?? ''), ENT_QUOTES); ?>')">
                                    <i class="bi bi-calendar-plus"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No hay clientes registrados en el sistema.
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Nuevo Cliente -->
<div class="modal fade" id="modalNuevoCliente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F28322; color: white;">
                <h5 class="modal-title">Registrar Nuevo Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoCliente" method="POST" action="index.php?controller=recepcion&action=registrarCliente">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre *</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Apellido</label>
                            <input type="text" name="apellido" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Teléfono *</label>
                            <input type="tel" name="telefono" class="form-control" required placeholder="961-123-4567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="cliente@email.com">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formNuevoCliente" class="btn text-white" style="background-color: #F28322;">
                    <i class="bi bi-save"></i> Guardar Cliente
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Cliente -->
<div class="modal fade" id="modalVerCliente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #8C451C; color: white;">
                <h5 class="modal-title">Información del Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleCliente">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
function buscarClientes() {
    const termino = document.getElementById('inputBusqueda').value;
    
    if (!termino) {
        location.reload();
        return;
    }
    
    fetch('index.php?controller=recepcion&action=buscarCliente', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'busqueda=' + encodeURIComponent(termino)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.resultados) {
            actualizarTablaClientes(data.resultados);
        } else {
            alert('No se encontraron clientes');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al buscar clientes');
    });
}

function actualizarTablaClientes(clientes) {
    const tbody = document.querySelector('tbody');
    
    if (clientes.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center">No se encontraron resultados</td></tr>';
        return;
    }
    
    let html = '';
    clientes.forEach(cliente => {
        html += `<tr>
            <td>${cliente.ID_CLIENTE}</td>
            <td>${cliente.NOMBRE}</td>
            <td>${cliente.APELLIDO || ''}</td>
            <td>${cliente.TELEFONO}</td>
            <td>${cliente.EMAIL || 'N/A'}</td>
            <td>${cliente.FECHA_REGISTRO ? new Date(cliente.FECHA_REGISTRO).toLocaleDateString('es-MX') : 'N/A'}</td>
            <td>
                <button class="btn btn-sm btn-info text-white" onclick="verCliente(${cliente.ID_CLIENTE}, '${cliente.NOMBRE}', '${cliente.APELLIDO || ''}', '${cliente.TELEFONO}', '${cliente.EMAIL || ''}')">
                    <i class="bi bi-eye"></i>
                </button>
                <button class="btn btn-sm btn-success" onclick="crearReservacionCliente(${cliente.ID_CLIENTE}, '${cliente.NOMBRE} ${cliente.APELLIDO || ''}')">
                    <i class="bi bi-calendar-plus"></i>
                </button>
            </td>
        </tr>`;
    });
    
    tbody.innerHTML = html;
}

function verCliente(id, nombre, apellido, telefono, email) {
    const detalleDiv = document.getElementById('detalleCliente');
    
    detalleDiv.innerHTML = `
        <div class="row g-3">
            <div class="col-12">
                <strong>ID:</strong> ${id}
            </div>
            <div class="col-12">
                <strong>Nombre Completo:</strong> ${nombre} ${apellido}
            </div>
            <div class="col-12">
                <strong>Teléfono:</strong> ${telefono}
            </div>
            <div class="col-12">
                <strong>Email:</strong> ${email || 'No registrado'}
            </div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('modalVerCliente'));
    modal.show();
}

function crearReservacionCliente(idCliente, nombreCliente) {
    if (confirm(`¿Crear nueva reservación para ${nombreCliente}?`)) {
        window.location.href = `index.php?controller=recepcion&action=reservaciones&cliente_id=${idCliente}`;
    }
}

document.getElementById('inputBusqueda').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        buscarClientes();
    }
});
</script>
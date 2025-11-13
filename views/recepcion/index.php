<div class="container-fluid my-4">
    <div class="row">
        <nav class="col-md-2 d-md-block sidebar" style="background-color: #8C451C; min-height: 90vh;">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white active" href="index.php?controller=recepcion&action=index">
                            <i class="bi bi-speedometer2"></i> Panel Principal
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="index.php?controller=recepcion&action=clientes">
                            <i class="bi bi-people"></i> Clientes
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="index.php?controller=recepcion&action=reservaciones">
                            <i class="bi bi-calendar-check"></i> Reservaciones
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="index.php?controller=recepcion&action=mesas">
                            <i class="bi bi-table"></i> Mesas
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 fw-bold" style="color: #8C451C;">Panel de Recepción</h1>
                <div class="text-muted">
                    <i class="bi bi-calendar3"></i> <?php echo date('d/m/Y'); ?>
                </div>
            </div>

            <!-- Estadísticas del día -->
            <?php if (isset($estadisticas) && !empty($estadisticas)): ?>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card text-black bg-light shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar-check fs-1 mb-2" style="color: #F28322;"></i>
                            <h5 class="card-title">Reservaciones Hoy</h5>
                            <h2 class="mb-0"><?php echo $estadisticas['RESERVACIONES_HOY'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-black bg-light shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-table fs-1 mb-2" style="color: #8C451C;"></i>
                            <h5 class="card-title">Mesas Ocupadas</h5>
                            <h2 class="mb-0"><?php echo ($estadisticas['MESAS_OCUPADAS'] ?? 0) . '/' . ($estadisticas['TOTAL_MESAS'] ?? 0); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-black bg-light shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-people fs-1 mb-2" style="color: #25D366;"></i>
                            <h5 class="card-title">Clientes Registrados</h5>
                            <h2 class="mb-0"><?php echo $estadisticas['CLIENTES_REGISTRADOS'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-black bg-light shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-clock-history fs-1 mb-2" style="color: #FFC107;"></i>
                            <h5 class="card-title">Pendientes</h5>
                            <h2 class="mb-0"><?php echo $estadisticas['RESERVACIONES_PENDIENTES'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Acciones rápidas -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-person-plus fs-1 mb-3" style="color: #F28322;"></i>
                            <h5 class="card-title">Registrar Cliente</h5>
                            <p class="card-text text-muted">Agregar nuevo cliente al sistema</p>
                            <button class="btn text-white" style="background-color: #F28322;" data-bs-toggle="modal" data-bs-target="#modalRegistrarCliente">
                                <i class="bi bi-plus-circle"></i> Registrar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar-plus fs-1 mb-3" style="color: #8C451C;"></i>
                            <h5 class="card-title">Nueva Reservación</h5>
                            <p class="card-text text-muted">Crear reservación telefónica</p>
                            <button class="btn text-white" style="background-color: #8C451C;" data-bs-toggle="modal" data-bs-target="#modalNuevaReservacion">
                                <i class="bi bi-plus-circle"></i> Crear
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-search fs-1 mb-3" style="color: #25D366;"></i>
                            <h5 class="card-title">Buscar Cliente</h5>
                            <p class="card-text text-muted">Buscar cliente existente</p>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalBuscarCliente">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reservaciones de hoy -->
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #8C451C; color: white;">
                    <h5 class="mb-0"><i class="bi bi-calendar-day"></i> Reservaciones de Hoy</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($reservacionesHoy)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Cliente</th>
                                    <th>Teléfono</th>
                                    <th>Personas</th>
                                    <th>Mesa</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservacionesHoy as $reserva): ?>
                                <tr>
                                    <td><strong><?php echo date('H:i', strtotime($reserva['HORA'])); ?></strong></td>
                                    <td><?php echo htmlspecialchars($reserva['NOMBRE'] . ' ' . $reserva['APELLIDO']); ?></td>
                                    <td><?php echo htmlspecialchars($reserva['TELEFONO'] ?? 'N/A'); ?></td>
                                    <td><?php echo $reserva['PERSONAS']; ?> persona<?php echo $reserva['PERSONAS'] > 1 ? 's' : ''; ?></td>
                                    <td>Mesa <?php echo $reserva['NUMERO_MESA'] ?? $reserva['ID_MESA']; ?></td>
                                    <td>
                                        <?php
                                        $estadoClass = '';
                                        switch($reserva['ESTADO']) {
                                            case 'PENDIENTE':
                                                $estadoClass = 'bg-warning';
                                                break;
                                            case 'CONFIRMADA':
                                                $estadoClass = 'bg-success';
                                                break;
                                            case 'CANCELADA':
                                                $estadoClass = 'bg-danger';
                                                break;
                                            case 'COMPLETADA':
                                                $estadoClass = 'bg-info';
                                                break;
                                            default:
                                                $estadoClass = 'bg-secondary';
                                        }
                                        ?>
                                        <span class="badge <?php echo $estadoClass; ?>"><?php echo $reserva['ESTADO']; ?></span>
                                    </td>
                                    <td>
                                        <?php if ($reserva['ESTADO'] == 'PENDIENTE'): ?>
                                        <button class="btn btn-sm btn-success" onclick="confirmarReservacion(<?php echo $reserva['ID_RESERVACION']; ?>)">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <?php elseif ($reserva['ESTADO'] == 'CONFIRMADA'): ?>
                                        <button class="btn btn-sm btn-info text-white" onclick="completarReservacion(<?php echo $reserva['ID_RESERVACION']; ?>)">
                                            <i class="bi bi-check2-all"></i>
                                        </button>
                                        <?php else: ?>
                                        <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> No hay reservaciones programadas para hoy.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal Registrar Cliente -->
<div class="modal fade" id="modalRegistrarCliente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F28322; color: white;">
                <h5 class="modal-title">Registrar Nuevo Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistrarCliente" method="POST" action="index.php?controller=recepcion&action=registrarCliente">
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
                <button type="submit" form="formRegistrarCliente" class="btn text-white" style="background-color: #F28322;">
                    <i class="bi bi-save"></i> Guardar Cliente
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Reservación -->
<div class="modal fade" id="modalNuevaReservacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #8C451C; color: white;">
                <h5 class="modal-title">Nueva Reservación Telefónica</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaReservacion" method="POST" action="index.php?controller=recepcion&action=crearReservacion">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Cliente *</label>
                            <select name="cliente_id" id="selectCliente" class="form-select" required>
                                <option value="">Cargando clientes...</option>
                            </select>
                            <small class="text-muted">O <a href="#" data-bs-toggle="modal" data-bs-target="#modalRegistrarCliente">registrar nuevo cliente</a></small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Fecha *</label>
                            <input type="date" name="fecha" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Hora *</label>
                            <input type="time" name="hora" class="form-control" required min="13:00" max="23:00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Personas *</label>
                            <select name="personas" class="form-select" required>
                                <option value="">Seleccionar</option>
                                <?php for($i = 1; $i <= 8; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> persona<?php echo $i > 1 ? 's' : ''; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Mesa *</label>
                            <select name="mesa_id" id="selectMesa" class="form-select" required>
                                <option value="">Seleccionar mesa...</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formNuevaReservacion" class="btn text-white" style="background-color: #8C451C;">
                    <i class="bi bi-save"></i> Crear Reservación
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Buscar Cliente -->
<div class="modal fade" id="modalBuscarCliente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Buscar Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" id="inputBusquedaCliente" class="form-control" placeholder="Buscar por nombre, teléfono o email">
                    <button class="btn btn-success" type="button" onclick="buscarClienteModal()">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
                <div id="resultadosBusqueda"></div>
            </div>
        </div>
    </div>
</div>

<style>
    .sidebar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1);
        border-left: 3px solid #F28322;
    }
    
    .sidebar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }
</style>

<script>
// Cargar clientes 
document.getElementById('modalNuevaReservacion').addEventListener('show.bs.modal', function() {
    cargarClientes();
});

function cargarClientes() {
    fetch('index.php?controller=recepcion&action=obtenerClientes')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.clientes) {
                const select = document.getElementById('selectCliente');
                select.innerHTML = '<option value="">Seleccionar cliente...</option>';
                
                data.clientes.forEach(cliente => {
                    const option = document.createElement('option');
                    option.value = cliente.ID_CLIENTE;
                    option.textContent = `${cliente.NOMBRE} ${cliente.APELLIDO || ''} - ${cliente.TELEFONO}`;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error:', error));
}

function buscarClienteModal() {
    const termino = document.getElementById('inputBusquedaCliente').value;
    
    if (!termino) {
        alert('Ingresa un término de búsqueda');
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
        const resultadosDiv = document.getElementById('resultadosBusqueda');
        
        if (data.success && data.resultados && data.resultados.length > 0) {
            let html = '<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Nombre</th><th>Teléfono</th><th>Email</th></tr></thead><tbody>';
            
            data.resultados.forEach(cliente => {
                html += `<tr>
                    <td>${cliente.NOMBRE} ${cliente.APELLIDO || ''}</td>
                    <td>${cliente.TELEFONO}</td>
                    <td>${cliente.EMAIL || 'N/A'}</td>
                </tr>`;
            });
            
            html += '</tbody></table></div>';
            resultadosDiv.innerHTML = html;
        } else {
            resultadosDiv.innerHTML = '<div class="alert alert-warning">No se encontraron clientes</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al buscar clientes');
    });
}

function confirmarReservacion(id) {
    if (confirm('¿Confirmar esta reservación?')) {
        fetch('index.php?controller=recepcion&action=confirmarReservacion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'reservacion_id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Reservación confirmada exitosamente');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'No se pudo confirmar'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al confirmar la reservación');
        });
    }
}

function completarReservacion(id) {
    if (confirm('¿Marcar esta reservación como completada?')) {
        fetch('index.php?controller=recepcion&action=completarReservacion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'reservacion_id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Reservación completada exitosamente');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'No se pudo completar'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al completar la reservación');
        });
    }
}
</script>
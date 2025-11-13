<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold" style="color: #8C451C;">Gestión de Reservaciones</h1>
        <div>
            <a href="index.php?controller=recepcion" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Panel
            </a>
            <button class="btn text-white" style="background-color: #F28322;" data-bs-toggle="modal" data-bs-target="#modalNuevaReservacion">
                <i class="bi bi-calendar-plus"></i> Nueva Reservación
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="index.php">
                <input type="hidden" name="controller" value="recepcion">
                <input type="hidden" name="action" value="reservaciones">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Fecha</label>
                        <input type="date" name="fecha" id="filtroFecha" class="form-control" value="<?php echo $_GET['fecha'] ?? date('Y-m-d'); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Estado</label>
                        <select name="estado" id="filtroEstado" class="form-select">
                            <option value="">Todos</option>
                            <option value="PENDIENTE" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'PENDIENTE') ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="CONFIRMADA" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'CONFIRMADA') ? 'selected' : ''; ?>>Confirmada</option>
                            <option value="CANCELADA" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'CANCELADA') ? 'selected' : ''; ?>>Cancelada</option>
                            <option value="COMPLETADA" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'COMPLETADA') ? 'selected' : ''; ?>>Completada</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Buscar Cliente</label>
                        <input type="text" name="cliente" id="filtroCliente" class="form-control" placeholder="Nombre del cliente..." value="<?php echo $_GET['cliente'] ?? ''; ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-lg w-100" style="background-color: #8C451C; color: white;">
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Estadísticas -->
    <?php if (isset($estadisticas) && !empty($estadisticas)): ?>
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-black bg-light shadow-sm">
                <div class="card-body text-center">
                    <h6 class="card-title">Confirmadas</h6>
                    <h2 class="mb-0 text-success"><?php echo $estadisticas['confirmadas'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-black bg-light shadow-sm">
                <div class="card-body text-center">
                    <h6 class="card-title">Pendientes</h6>
                    <h2 class="mb-0 text-warning"><?php echo $estadisticas['pendientes'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-black bg-light shadow-sm">
                <div class="card-body text-center">
                    <h6 class="card-title">Canceladas</h6>
                    <h2 class="mb-0 text-danger"><?php echo $estadisticas['canceladas'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-black bg-light shadow-sm">
                <div class="card-body text-center">
                    <h6 class="card-title">Completadas</h6>
                    <h2 class="mb-0 text-info"><?php echo $estadisticas['completadas'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Tabla de Reservaciones -->
    <div class="card shadow-sm">
        <div class="card-header" style="background-color: #8C451C; color: white;">
            <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Reservaciones</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($reservaciones)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Mesa</th>
                            <th>Personas</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservaciones as $reservacion): ?>
                        <tr>
                            <td><?php echo $reservacion['ID_RESERVACION']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($reservacion['NOMBRE'] . ' ' . $reservacion['APELLIDO']); ?></strong><br>
                                <small class="text-muted"><?php echo htmlspecialchars($reservacion['TELEFONO'] ?? ''); ?></small>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($reservacion['FECHA'])); ?></td>
                            <td><strong><?php echo date('H:i', strtotime($reservacion['HORA'])); ?></strong></td>
                            <td>Mesa <?php echo $reservacion['NUMERO_MESA'] ?? $reservacion['ID_MESA']; ?></td>
                            <td><?php echo $reservacion['PERSONAS']; ?> persona<?php echo $reservacion['PERSONAS'] > 1 ? 's' : ''; ?></td>
                            <td>
                                <?php
                                $estadoClass = '';
                                switch($reservacion['ESTADO']) {
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
                                <span class="badge <?php echo $estadoClass; ?>"><?php echo $reservacion['ESTADO']; ?></span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <?php if ($reservacion['ESTADO'] == 'PENDIENTE'): ?>
                                    <button class="btn btn-success" onclick="confirmarReservacion(<?php echo $reservacion['ID_RESERVACION']; ?>)" title="Confirmar">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                    <button class="btn btn-danger" onclick="cancelarReservacion(<?php echo $reservacion['ID_RESERVACION']; ?>)" title="Cancelar">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                    <?php elseif ($reservacion['ESTADO'] == 'CONFIRMADA'): ?>
                                    <button class="btn btn-info text-white" onclick="completarReservacion(<?php echo $reservacion['ID_RESERVACION']; ?>)" title="Completar">
                                        <i class="bi bi-check2-all"></i>
                                    </button>
                                    <button class="btn btn-danger" onclick="cancelarReservacion(<?php echo $reservacion['ID_RESERVACION']; ?>)" title="Cancelar">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                    <?php else: ?>
                                    <span class="text-muted">Sin acciones</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No hay reservaciones que coincidan con los filtros seleccionados.
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Nueva Reservación -->
<div class="modal fade" id="modalNuevaReservacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #8C451C; color: white;">
                <h5 class="modal-title">Nueva Reservación</h5>
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
                            <small class="text-muted">O <a href="index.php?controller=recepcion&action=clientes">registrar nuevo cliente</a></small>
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
                                <option value="">Primero selecciona fecha, hora y personas</option>
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

function cancelarReservacion(id) {
    if (confirm('¿Estás seguro de cancelar esta reservación?')) {
        fetch('index.php?controller=reservacion&action=cancelar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'reservacion_id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Reservación cancelada exitosamente');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'No se pudo cancelar'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cancelar la reservación');
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
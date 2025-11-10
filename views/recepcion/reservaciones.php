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

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Fecha</label>
                    <input type="date" id="filtroFecha" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Estado</label>
                    <select id="filtroEstado" class="form-select">
                        <option value="">Todos</option>
                        <option value="PENDIENTE">Pendiente</option>
                        <option value="CONFIRMADA">Confirmada</option>
                        <option value="CANCELADA">Cancelada</option>
                        <option value="COMPLETADA">Completada</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Buscar Cliente</label>
                    <input type="text" id="filtroCliente" class="form-control" placeholder="Nombre del cliente...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-lg w-100" style="background-color: #8C451C; color: white;" onclick="filtrarReservaciones()">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                </div>
            </div>
        </div>
    </div>
<!--
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-black bg-light">
                <div class="card-body">
                    <h6 class="card-title">Confirmadas</h6>
                    <h2 class="mb-0">8</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-black bg-light">
                <div class="card-body">
                    <h6 class="card-title">Pendientes</h6>
                    <h2 class="mb-0">3</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-black bg-light">
                <div class="card-body">
                    <h6 class="card-title">Canceladas</h6>
                    <h2 class="mb-0">2</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-black bg-light">
                <div class="card-body">
                    <h6 class="card-title">Completadas</h6>
                    <h2 class="mb-0">15</h2>
                </div>
            </div>
        </div>
    </div>
-->
    <div class="card shadow-sm">
        <div class="card-header" style="background-color: #8C451C; color: white;">
            <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Reservaciones</h5>
        </div>
        <div class="card-body">
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
                    <tbody id="tablaReservaciones">
                        <?php foreach($reservaciones as $res): ?>
                        <tr>
                            <td><?php echo $res['id']; ?></td>
                            <td><?php echo htmlspecialchars($res['cliente_nombre']); ?></td>
                            <td><?php echo $res['fecha']; ?></td>
                            <td><?php echo substr($res['hora'], 0, 5); ?></td>
                            <td><?php echo htmlspecialchars($res['mesa_nombre']); ?></td>
                            <td><?php echo $res['personas']; ?></td>
                            <td>
                                <span class="badge 
                                    <?php 
                                        switch($res['estado']) {
                                            case 'CONFIRMADA': echo 'bg-success'; break;
                                            case 'PENDIENTE': echo 'bg-warning text-dark'; break;
                                            case 'CANCELADA': echo 'bg-danger'; break;
                                            case 'COMPLETADA': echo 'bg-primary'; break;
                                            default: echo 'bg-secondary';
                                        }
                                    ?>">
                                    <?php echo $res['estado']; ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info text-white" onclick="verReservacion(<?php echo $res['id']; ?>)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning text-white" onclick="editarReservacion(<?php echo $res['id']; ?>)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <?php if($res['estado'] == 'PENDIENTE'): ?>
                                <button class="btn btn-sm btn-success text-white" onclick="confirmarReservacion(<?php echo $res['id']; ?>)">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                                <button class="btn btn-sm btn-danger text-white" onclick="cancelarReservacion(<?php echo $res['id']; ?>)">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNuevaReservacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #8C451C; color: white;">
                <h5 class="modal-title">Nueva Reservación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaReservacion" method="POST">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Cliente *</label>
                            <select name="id_cliente" class="form-select" required>
                                <option value="">Seleccionar cliente...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Fecha *</label>
                            <input type="date" name="fecha" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Hora *</label>
                            <input type="time" name="hora" class="form-control" required min="13:00" max="22:00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Personas *</label>
                            <select name="personas" class="form-select" required>
                                <?php for($i = 1; $i <= 8; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Mesa *</label>
                            <select name="mesa_id" class="form-select" required>
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

<script>
function filtrarReservaciones() {
    const fecha = document.getElementById('filtroFecha').value;
    const estado = document.getElementById('filtroEstado').value;
    const cliente = document.getElementById('filtroCliente').value;
    
    console.log('Filtrar:', {fecha, estado, cliente});
}

function verReservacion(id) {
    alert('Ver detalles - Reservación ID: ' + id);
}

function editarReservacion(id) {
    alert('Editar reservación - ID: ' + id);
}

function confirmarReservacion(id) {
    if (confirm('¿Confirmar esta reservación?')) {
        alert('Reservación confirmada - ID: ' + id);
    }
}

function cancelarReservacion(id) {
    if (confirm('¿Estás seguro de cancelar esta reservación?')) {
        alert('Reservación cancelada - ID: ' + id);
    }
}
</script>

<div class="container my-5">
    <h1 class="text-center mb-5 display-4 fw-bold" style="color: #8C451C;">Reservaciones</h1>
    
    <div class="card mb-4 shadow border-0" style="background: linear-gradient(135deg, #FFF5E1 0%, #FFE4B5 100%);">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-4" style="color: #F28322;">Información de Contacto</h3>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <i class="bi bi-clock-fill fs-4" style="color: #F28322;"></i>
                    <strong class="d-block mt-2">Horario de Servicio</strong>
                    <p class="text-muted mb-0">Lunes a Domingo<br>1:00 PM - 11:00 PM</p>
                </div>
                <div class="col-md-4 mb-3">
                    <i class="bi bi-telephone-fill fs-4" style="color: #F28322;"></i>
                    <strong class="d-block mt-2">Teléfono</strong>
                    <p class="text-muted mb-0">961-849-4215</p>
                </div>
                <div class="col-md-4 mb-3">
                    <i class="bi bi-whatsapp fs-4" style="color: #F28322;"></i>
                    <strong class="d-block mt-2">WhatsApp</strong>
                    <p class="text-muted mb-0">961-849-4215</p>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['usuario_id'])): ?>
    <div class="card mb-4 shadow border-0">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-4" style="color: #F28322;">Mis Reservaciones</h3>
            
            <?php if (!empty($misReservaciones)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #8C451C; color: white;">
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Mesa</th>
                            <th>Personas</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($misReservaciones as $reservacion): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($reservacion['FECHA'])); ?></td>
                            <td><?php echo date('H:i', strtotime($reservacion['HORA'])); ?></td>
                            <td>Mesa <?php echo $reservacion['NUMERO_MESA'] ?? $reservacion['ID_MESA']; ?></td>
                            <td><?php echo $reservacion['PERSONAS']; ?> persona<?php echo $reservacion['PERSONAS'] > 1 ? 's' : ''; ?></td>
                            <td>
                                <?php 
                                $estadoClass = '';
                                $estadoTexto = $reservacion['ESTADO'];
                                
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
                                <span class="badge <?php echo $estadoClass; ?>"><?php echo $estadoTexto; ?></span>
                            </td>
                            <td>
                                <?php if ($reservacion['ESTADO'] == 'PENDIENTE' || $reservacion['ESTADO'] == 'CONFIRMADA'): ?>
                                <button class="btn btn-sm btn-warning" onclick="editarReservacion(<?php echo $reservacion['ID_RESERVACION']; ?>, '<?php echo $reservacion['FECHA']; ?>', '<?php echo $reservacion['HORA']; ?>', <?php echo $reservacion['PERSONAS']; ?>, <?php echo $reservacion['ID_MESA']; ?>)">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="cancelarReservacion(<?php echo $reservacion['ID_RESERVACION']; ?>)">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </button>
                                <?php else: ?>
                                <span class="text-muted">No disponible</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No tienes reservaciones activas. ¡Crea una nueva reservación abajo!
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="card shadow border-0">
        <div class="card-body p-5">
            <h3 class="fw-bold mb-4" style="color: #F28322;">Nueva Reservación</h3>
            
            <?php if (!isset($_SESSION['usuario_id'])): ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> 
                Debes <a href="index.php?controller=auth&action=login" class="alert-link">iniciar sesión</a> 
                para hacer una reservación.
            </div>
            <?php endif; ?>
            
            <form action="index.php?controller=reservacion&action=crear" method="POST" id="formReservacion">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="form-control form-control-lg" required 
                               min="<?php echo date('Y-m-d'); ?>"
                               <?php echo !isset($_SESSION['usuario_id']) ? 'disabled' : ''; ?>>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Hora</label>
                        <input type="time" name="hora" id="hora" class="form-control form-control-lg" required
                               min="13:00" max="23:00"
                               <?php echo !isset($_SESSION['usuario_id']) ? 'disabled' : ''; ?>>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Personas</label>
                        <select name="personas" id="personas" class="form-select form-select-lg" required onchange="filtrarMesas()"
                                <?php echo !isset($_SESSION['usuario_id']) ? 'disabled' : ''; ?>>
                            <option value="">Seleccionar</option>
                            <?php for($i = 1; $i <= 8; $i++): ?>
                            <option value="<?php echo $i; ?>">
                                <?php echo $i . ($i == 1 ? ' persona' : ' personas'); ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                
                <div class="mb-4" id="contenedorMesas">
                    <h4 class="fw-bold mb-3">Selecciona una mesa</h4>
                    <p class="text-muted">Primero selecciona el número de personas para ver las mesas disponibles</p>
                    <div class="row g-3" id="listaMesas">
                        <?php if (!empty($mesas)): ?>
                            <?php foreach ($mesas as $mesa): ?>
                            <div class="col-md-2 col-6 mesa-item" data-capacidad="<?php echo $mesa['capacidad']; ?>" style="display: none;">
                                <input type="radio" class="btn-check" name="mesa_id" id="mesa<?php echo $mesa['id']; ?>" 
                                       value="<?php echo $mesa['id']; ?>" required
                                       <?php echo !$mesa['disponible'] ? 'disabled' : ''; ?>
                                       <?php echo !isset($_SESSION['usuario_id']) ? 'disabled' : ''; ?>>
                                <label class="btn w-100 <?php echo $mesa['disponible'] ? 'btn-outline-success' : 'btn-outline-danger'; ?>" 
                                       for="mesa<?php echo $mesa['id']; ?>"
                                       style="<?php echo !$mesa['disponible'] ? 'opacity: 0.5; cursor: not-allowed;' : ''; ?>">
                                    <strong>Mesa <?php echo $mesa['numero']; ?></strong><br>
                                    <small><?php echo $mesa['capacidad']; ?> personas</small><br>
                                    <small class="<?php echo $mesa['disponible'] ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo $mesa['disponible'] ? 'Disponible' : 'Ocupada'; ?>
                                    </small>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-warning">No hay mesas disponibles en este momento.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <button type="submit" name="envio" class="btn btn-lg w-100 fw-bold text-white" 
                                style="background-color: #F28322; border: none;"
                                <?php echo !isset($_SESSION['usuario_id']) ? 'disabled' : ''; ?>>
                            <i class="bi bi-check-circle"></i> Reservar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Editar Reservación -->
<div class="modal fade" id="modalEditarReservacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #8C451C; color: white;">
                <h5 class="modal-title">Editar Reservación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarReservacion">
                    <input type="hidden" name="reservacion_id" id="edit_reservacion_id">
                    <input type="hidden" name="mesa_id" id="edit_mesa_id">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Fecha</label>
                            <input type="date" name="fecha" id="edit_fecha" class="form-control" required
                                   min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Hora</label>
                            <input type="time" name="hora" id="edit_hora" class="form-control" required
                                   min="13:00" max="23:00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Personas</label>
                            <select name="personas" id="edit_personas" class="form-select" required>
                                <?php for($i = 1; $i <= 8; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> persona<?php echo $i > 1 ? 's' : ''; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn text-white" style="background-color: #F28322;" onclick="guardarEdicion()">
                    Guardar cambios
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-check:checked + .btn-outline-success {
        background-color: #F28322 !important;
        border-color: #F28322 !important;
        color: white !important;
    }
</style>

<script>
function filtrarMesas() {
    const personas = parseInt(document.getElementById('personas').value);
    const mesas = document.querySelectorAll('.mesa-item');
    
    if (!personas) {
        mesas.forEach(mesa => mesa.style.display = 'none');
        return;
    }
    
    let hayMesasDisponibles = false;
    mesas.forEach(mesa => {
        const capacidad = parseInt(mesa.getAttribute('data-capacidad'));
        if (capacidad >= personas) {
            mesa.style.display = 'block';
            hayMesasDisponibles = true;
        } else {
            mesa.style.display = 'none';
        }
    });
    
    if (!hayMesasDisponibles) {
        alert('No hay mesas disponibles para ' + personas + ' personas');
    }
}

function editarReservacion(id, fecha, hora, personas, mesaId) {
    document.getElementById('edit_reservacion_id').value = id;
    document.getElementById('edit_fecha').value = fecha;
    document.getElementById('edit_hora').value = hora;
    document.getElementById('edit_personas').value = personas;
    document.getElementById('edit_mesa_id').value = mesaId;
    
    const modal = new bootstrap.Modal(document.getElementById('modalEditarReservacion'));
    modal.show();
}

function guardarEdicion() {
    const form = document.getElementById('formEditarReservacion');
    const formData = new FormData(form);
    
    fetch('index.php?controller=reservacion&action=editar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Reservación actualizada exitosamente');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'No se pudo actualizar la reservación'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar la reservación');
    });
}

function cancelarReservacion(id) {
    if (confirm('¿Estás seguro de que deseas cancelar esta reservación?')) {
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
                alert('Error: ' + (data.message || 'No se pudo cancelar la reservación'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cancelar la reservación');
        });
    }
}

function verificarDisponibilidadMesa(idMesa) {
    const fecha = document.getElementById('fecha').value;
    const hora = document.getElementById('hora').value;
    
    if (!fecha || !hora) {
        return;
    }
    
    fetch(`index.php?controller=reservacion&action=verificarDisponibilidad&mesa=${idMesa}&fecha=${fecha}&hora=${hora}`)
        .then(response => response.json())
        .then(data => {
            if (!data.disponible) {
                const radioButton = document.getElementById('mesa' + idMesa);
                if (radioButton) {
                    radioButton.disabled = true;
                    radioButton.parentElement.classList.remove('btn-outline-success');
                    radioButton.parentElement.classList.add('btn-outline-danger');
                }
            }
        })
        .catch(error => console.error('Error:', error));
}

// Actualizar disponibilidad
document.getElementById('fecha')?.addEventListener('change', actualizarDisponibilidad);
document.getElementById('hora')?.addEventListener('change', actualizarDisponibilidad);

function actualizarDisponibilidad() {
    const mesas = document.querySelectorAll('.mesa-item');
    mesas.forEach(mesa => {
        const input = mesa.querySelector('input[type="radio"]');
        if (input) {
            const idMesa = input.value;
            verificarDisponibilidadMesa(idMesa);
        }
    });
}
</script>
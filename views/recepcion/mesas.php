<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold" style="color: #8C451C;">Gestión de Mesas</h1>
        <a href="index.php?controller=recepcion" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-black bg-light">
                <div class="card-body text-center">
                    <h2 class="mb-0">4</h2>
                    <p class="mb-0">Mesas Disponibles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-black bg-light">
                <div class="card-body text-center">
                    <h2 class="mb-0">5</h2>
                    <p class="mb-0">Mesas Ocupadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-black bg-light">
                <div class="card-body text-center">
                    <h2 class="mb-0">3</h2>
                    <p class="mb-0">Mesas Reservadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-black bg-light">
                <div class="card-body text-center">
                    <h2 class="mb-0">12</h2>
                    <p class="mb-0">Total de Mesas</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header" style="background-color: #8C451C; color: white;">
            <h5 class="mb-0"><i class="bi bi-table"></i> Estado de Mesas en Tiempo Real</h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-3 col-sm-6">
                    <div class="card border-success" style="cursor: pointer;" onclick="gestionarMesa(1)">
                        <div class="card-body text-center" style="background-color: #d4edda;">
                            <i class="bi bi-table fs-1 text-success"></i>
                            <h4 class="fw-bold mt-2">Mesa 1</h4>
                            <p class="mb-1">Capacidad: 2 personas</p>
                            <span class="badge bg-success">DISPONIBLE</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="card border-danger" style="cursor: pointer;" onclick="gestionarMesa(2)">
                        <div class="card-body text-center" style="background-color: #f8d7da;">
                            <i class="bi bi-table fs-1 text-danger"></i>
                            <h4 class="fw-bold mt-2">Mesa 2</h4>
                            <p class="mb-1">Capacidad: 4 personas</p>
                            <span class="badge bg-danger">OCUPADA</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="card border-success" style="cursor: pointer;" onclick="gestionarMesa(3)">
                        <div class="card-body text-center" style="background-color: #d4edda;">
                            <i class="bi bi-table fs-1 text-success"></i>
                            <h4 class="fw-bold mt-2">Mesa 3</h4>
                            <p class="mb-1">Capacidad: 4 personas</p>
                            <span class="badge bg-success">DISPONIBLE</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="card border-warning" style="cursor: pointer;" onclick="gestionarMesa(4)">
                        <div class="card-body text-center" style="background-color: #fff3cd;">
                            <i class="bi bi-table fs-1 text-warning"></i>
                            <h4 class="fw-bold mt-2">Mesa 4</h4>
                            <p class="mb-1">Capacidad: 6 personas</p>
                            <span class="badge bg-warning">RESERVADA</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="card border-danger" style="cursor: pointer;" onclick="gestionarMesa(5)">
                        <div class="card-body text-center" style="background-color: #f8d7da;">
                            <i class="bi bi-table fs-1 text-danger"></i>
                            <h4 class="fw-bold mt-2">Mesa 5</h4>
                            <p class="mb-1">Capacidad: 8 personas</p>
                            <span class="badge bg-danger">OCUPADA</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="card border-warning" style="cursor: pointer;" onclick="gestionarMesa(6)">
                        <div class="card-body text-center" style="background-color: #fff3cd;">
                            <i class="bi bi-table fs-1 text-warning"></i>
                            <h4 class="fw-bold mt-2">Mesa 6</h4>
                            <p class="mb-1">Capacidad: 2 personas</p>
                            <span class="badge bg-warning">RESERVADA</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="card border-danger" style="cursor: pointer;" onclick="gestionarMesa(7)">
                        <div class="card-body text-center" style="background-color: #f8d7da;">
                            <i class="bi bi-table fs-1 text-danger"></i>
                            <h4 class="fw-bold mt-2">Mesa 7</h4>
                            <p class="mb-1">Capacidad: 2 personas</p>
                            <span class="badge bg-danger">OCUPADA</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="card border-success" style="cursor: pointer;" onclick="gestionarMesa(8)">
                        <div class="card-body text-center" style="background-color: #d4edda;">
                            <i class="bi bi-table fs-1 text-success"></i>
                            <h4 class="fw-bold mt-2">Mesa 8</h4>
                            <p class="mb-1">Capacidad: 4 personas</p>
                            <span class="badge bg-success">DISPONIBLE</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="card border-danger" style="cursor: pointer;" onclick="gestionarMesa(9)">
                        <div class="card-body text-center" style="background-color: #f8d7da;">
                            <i class="bi bi-table fs-1 text-danger"></i>
                            <h4 class="fw-bold mt-2">Mesa 9</h4>
                            <p class="mb-1">Capacidad: 4 personas</p>
                            <span class="badge bg-danger">OCUPADA</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="card border-warning" style="cursor: pointer;" onclick="gestionarMesa(10)">
                        <div class="card-body text-center" style="background-color: #fff3cd;">
                            <i class="bi bi-table fs-1 text-warning"></i>
                            <h4 class="fw-bold mt-2">Mesa 10</h4>
                            <p class="mb-1">Capacidad: 6 personas</p>
                            <span class="badge bg-warning">RESERVADA</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="card border-danger" style="cursor: pointer;" onclick="gestionarMesa(11)">
                        <div class="card-body text-center" style="background-color: #f8d7da;">
                            <i class="bi bi-table fs-1 text-danger"></i>
                            <h4 class="fw-bold mt-2">Mesa 11</h4>
                            <p class="mb-1">Capacidad: 8 personas</p>
                            <span class="badge bg-danger">OCUPADA</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="card border-success" style="cursor: pointer;" onclick="gestionarMesa(12)">
                        <div class="card-body text-center" style="background-color: #d4edda;">
                            <i class="bi bi-table fs-1 text-success"></i>
                            <h4 class="fw-bold mt-2">Mesa 12</h4>
                            <p class="mb-1">Capacidad: 2 personas</p>
                            <span class="badge bg-success">DISPONIBLE</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalGestionarMesa" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #8C451C; color: white;">
                <h5 class="modal-title" id="tituloModal">Gestionar Mesa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formGestionarMesa">
                    <input type="hidden" id="mesa_id" name="mesa_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Estado de la Mesa</label>
                        <select class="form-select" id="estado_mesa" name="estado">
                            <option value="DISPONIBLE">Disponible</option>
                            <option value="OCUPADA">Ocupada</option>
                            <option value="RESERVADA">Reservada</option>
                        </select>
                    </div>

                    <div class="alert alert-info">
                        <strong>Información:</strong>
                        <ul class="mb-0">
                            <li>Disponible: Mesa libre para asignar</li>
                            <li>Ocupada: Clientes actualmente sentados</li>
                            <li>Reservada: Tiene reservación confirmada</li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn text-white" style="background-color: #F28322;" onclick="actualizarEstadoMesa()">
                    <i class="bi bi-save"></i> Actualizar Estado
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function gestionarMesa(idMesa) {
    document.getElementById('mesa_id').value = idMesa;
    document.getElementById('tituloModal').textContent = 'Gestionar Mesa ' + idMesa;
    
    const modal = new bootstrap.Modal(document.getElementById('modalGestionarMesa'));
    modal.show();
}

function actualizarEstadoMesa() {
    const mesaId = document.getElementById('mesa_id').value;
    const estado = document.getElementById('estado_mesa').value;
    
    fetch('index.php?controller=recepcion&action=actualizarEstadoMesa', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'mesa_id=' + mesaId + '&estado=' + estado
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Estado actualizado correctamente');
            location.reload();
        } else {
            alert('Error al actualizar el estado');
        }
    });
}
</script>

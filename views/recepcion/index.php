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
                <div>
                    <span class="me-3">
                        <i class="bi bi-person-circle"></i> 
                        <?php echo $_SESSION['recepcion_nombre'] ?? 'Recepcionista'; ?>
                    </span>
                    <a href="index.php?controller=auth&action=logout" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                    </a>
                </div>
            </div>
<!--
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card text-black bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Reservaciones Hoy</h5>
                            <h2 class="mb-0">3</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-black bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Mesas Ocupadas</h5>
                            <h2 class="mb-0">8/12</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-black bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Clientes Registrados</h5>
                            <h2 class="mb-0">245</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-black bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Pendientes</h5>
                            <h2 class="mb-0">3</h2>
                        </div>
                    </div>
                </div>
            </div>
-->
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

            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #8C451C; color: white;">
                    <h5 class="mb-0"><i class="bi bi-calendar-day"></i> Reservaciones de Hoy</h5>
                </div>
            </div>
        </main>
    </div>
</div>

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
                            <label class="form-label fw-bold">Nombre Completo *</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Teléfono *</label>
                            <input type="tel" name="telefono" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Notas</label>
                            <textarea name="notas" class="form-control" rows="3" placeholder="Preferencias, alergias, etc."></textarea>
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
                            <label class="form-label fw-bold">Buscar Cliente *</label>
                            <input type="text" id="buscarCliente" class="form-control" placeholder="Nombre o teléfono del cliente">
                            <small class="text-muted">O <a href="#" data-bs-toggle="modal" data-bs-target="#modalRegistrarCliente">registrar nuevo cliente</a></small>
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
                                <option value="">Seleccionar</option>
                                <?php for($i = 1; $i <= 8; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Mesa *</label>
                            <select name="mesa_id" class="form-select" required>
                                <option value="">Seleccionar mesa</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Notas especiales</label>
                            <textarea name="notas" class="form-control" rows="2"></textarea>
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

<div class="modal fade" id="modalBuscarCliente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Buscar Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Buscar por nombre, teléfono o email">
                    <button class="btn btn-success" type="button">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
                <div id="resultadosBusqueda">
                </div>
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

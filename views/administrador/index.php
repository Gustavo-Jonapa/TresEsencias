<div class="container-fluid my-4">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-md-block sidebar" style="background-color: #8C451C; min-height: 90vh;">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white active" href="index.php?controller=administrador&action=index">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="index.php?controller=administrador&action=empleados">
                            <i class="bi bi-people"></i> Empleados
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="index.php?controller=administrador&action=inventario">
                            <i class="bi bi-box-seam"></i> Inventario
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="index.php?controller=administrador&action=productos">
                            <i class="bi bi-bag"></i> Productos
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="index.php?controller=administrador&action=proveedores">
                            <i class="bi bi-truck"></i> Proveedores
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="index.php?controller=menuAdmin">
                            <i class="bi bi-journal-text"></i> Menú
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Contenido Principal -->
        <main class="col-md-10 ms-sm-auto px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 fw-bold" style="color: #8C451C;">
                    <i class="bi bi-speedometer2"></i> Panel de Administración
                </h1>
                <div>
                    <span class="me-3">
                        <i class="bi bi-person-circle"></i> 
                        <?php echo $_SESSION['admin_nombre'] ?? 'Administrador'; ?>
                    </span>
                    <a href="index.php?controller=auth&action=logout" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                    </a>
                </div>
            </div>

            <!-- Tarjetas de Estadísticas -->
             <!--
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #F28322 0%, #8C451C 100%);">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-uppercase mb-2">Empleados</h6>
                                    <h2 class="mb-0 fw-bold">25</h2>
                                </div>
                                <i class="bi bi-people fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white h-100 bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-uppercase mb-2">Inventario</h6>
                                    <h2 class="mb-0 fw-bold">150</h2>
                                    <small>Items</small>
                                </div>
                                <i class="bi bi-box-seam fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white h-100 bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-uppercase mb-2">Productos</h6>
                                    <h2 class="mb-0 fw-bold">85</h2>
                                </div>
                                <i class="bi bi-bag fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white h-100 bg-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-uppercase mb-2">Proveedores</h6>
                                    <h2 class="mb-0 fw-bold">12</h2>
                                </div>
                                <i class="bi bi-truck fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
-->
            <!-- Accesos Rápidos -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <h3 class="fw-bold mb-3" style="color: #8C451C;">Accesos Rápidos</h3>
                </div>

                <div class="col-md-3">
                    <a href="index.php?controller=administrador&action=empleados" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-card">
                            <div class="card-body text-center p-4">
                                <div class="mb-3" style="width: 80px; height: 80px; background-color: #F28322; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                    <i class="bi bi-person-plus fs-1 text-white"></i>
                                </div>
                                <h5 class="card-title fw-bold">Nuevo Empleado</h5>
                                <p class="text-muted small mb-0">Registrar personal</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="index.php?controller=administrador&action=inventario" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-card">
                            <div class="card-body text-center p-4">
                                <div class="mb-3" style="width: 80px; height: 80px; background-color: #28a745; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                    <i class="bi bi-box-seam-fill fs-1 text-white"></i>
                                </div>
                                <h5 class="card-title fw-bold">Gestionar Inventario</h5>
                                <p class="text-muted small mb-0">Control de stock</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="index.php?controller=administrador&action=productos" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-card">
                            <div class="card-body text-center p-4">
                                <div class="mb-3" style="width: 80px; height: 80px; background-color: #17a2b8; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                    <i class="bi bi-bag-plus-fill fs-1 text-white"></i>
                                </div>
                                <h5 class="card-title fw-bold">Productos</h5>
                                <p class="text-muted small mb-0">Catálogo de productos</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="index.php?controller=administrador&action=proveedores" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-card">
                            <div class="card-body text-center p-4">
                                <div class="mb-3" style="width: 80px; height: 80px; background-color: #ffc107; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                    <i class="bi bi-truck fs-1 text-white"></i>
                                </div>
                                <h5 class="card-title fw-bold">Proveedores</h5>
                                <p class="text-muted small mb-0">Gestionar proveedores</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Alertas -->
             <!--
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card shadow-sm border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Productos Bajo Stock</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Tomate</strong>
                                            <br><small class="text-muted">Stock actual: 5 kg</small>
                                        </div>
                                        <span class="badge bg-danger">Crítico</span>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Aceite de Oliva</strong>
                                            <br><small class="text-muted">Stock actual: 2 L</small>
                                        </div>
                                        <span class="badge bg-warning">Bajo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-info">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Actividad Reciente</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <div class="list-group-item">
                                    <i class="bi bi-person-plus text-success"></i>
                                    <strong>Nuevo empleado registrado</strong>
                                    <br><small class="text-muted">Hace 2 horas</small>
                                </div>
                                <div class="list-group-item">
                                    <i class="bi bi-box-seam text-primary"></i>
                                    <strong>Actualización de inventario</strong>
                                    <br><small class="text-muted">Hace 4 horas</small>
                                </div>
                                <div class="list-group-item">
                                    <i class="bi bi-truck text-warning"></i>
                                    <strong>Nuevo proveedor agregado</strong>
                                    <br><small class="text-muted">Ayer</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
-->
        </main>
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

    .hover-card {
        transition: all 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
    }
</style>
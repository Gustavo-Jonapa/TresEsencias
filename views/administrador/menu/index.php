<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold" style="color: #8C451C;">
            <i class="bi bi-menu-button"></i> Gestión de Menú
        </h1>
        <div>
            <a href="index.php?controller=administrador" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <button class="btn text-white" style="background-color: #F28322;" data-bs-toggle="modal" data-bs-target="#modalNuevoPlatillo">
                <i class="bi bi-plus-circle"></i> Nuevo Platillo
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <select id="filtroTipo" class="form-select">
                        <option value="">Todos los tipos</option>
                        <option value="Platos Fuertes">Platos Fuertes</option>
                        <option value="Postres">Postres</option>
                        <option value="Bebidas">Bebidas</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="filtroDisponibilidad" class="form-select">
                        <option value="">Todas</option>
                        <option value="1">Disponibles</option>
                        <option value="0">No disponibles</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button class="btn w-100" style="background-color: #8C451C; color: white;" onclick="filtrar()">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de platillos -->
    <div class="row g-4" id="listaPlatillos">
        <?php foreach ($platillos as $platillo): ?>
        <div class="col-md-4">
            <div class="card h-100">
                <?php if (!empty($platillo['IMAGEN_URL'])): ?>
                <img src="<?php echo $platillo['IMAGEN_URL']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                <?php else: ?>
                <div class="bg-secondary" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                </div>
                <?php endif; ?>
                <div class="card-body">
                    <span class="badge" style="background-color: #F28322;"><?php echo $platillo['TIPO']; ?></span>
                    <h5 class="card-title mt-2"><?php echo $platillo['NOMBRE']; ?></h5>
                    <p class="card-text text-muted"><?php echo $platillo['DESCRIPCION']; ?></p>
                    <h4 class="text-primary">$<?php echo number_format($platillo['PRECIO'], 2); ?></h4>
                    <div class="btn-group w-100 mt-2">
                        <button class="btn btn-sm btn-warning" onclick="editar(<?php echo $platillo['ID_MENU']; ?>)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminar(<?php echo $platillo['ID_MENU']; ?>)">
                            <i class="bi bi-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-<?php echo $platillo['DISPONIBLE'] ? 'success' : 'secondary'; ?>" 
                                onclick="cambiarDisponibilidad(<?php echo $platillo['ID_MENU']; ?>, <?php echo $platillo['DISPONIBLE'] ? 0 : 1; ?>)">
                            <i class="bi bi-<?php echo $platillo['DISPONIBLE'] ? 'check' : 'x'; ?>-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal Nuevo Platillo -->
<div class="modal fade" id="modalNuevoPlatillo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F28322; color: white;">
                <h5 class="modal-title">Nuevo Platillo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?controller=menuAdmin&action=crear" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre *</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Precio *</label>
                            <input type="number" name="precio" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo *</label>
                            <select name="tipo" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="Platos Fuertes">Platos Fuertes</option>
                                <option value="Postres">Postres</option>
                                <option value="Bebidas">Bebidas</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Imagen</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                            <small class="text-muted">JPG, PNG, GIF (Max 5MB)</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="disponible" class="form-check-input" id="disponible" checked>
                                <label class="form-check-label" for="disponible">Disponible</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn text-white" style="background-color: #F28322;">
                        <i class="bi bi-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function filtrar() {
    const tipo = document.getElementById('filtroTipo').value;
    const disponible = document.getElementById('filtroDisponibilidad').value;
    
    let url = 'index.php?controller=menuAdmin&action=filtrar';
    if (tipo) url += '&tipo=' + tipo;
    if (disponible) url += '&disponible=' + disponible;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            location.reload();
        });
}

function editar(id) {
    alert('Editar platillo ID: ' + id);
}

function eliminar(id) {
    if (confirm('¿Eliminar este platillo?')) {
        fetch('index.php?controller=menuAdmin&action=eliminar', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.Status === 'OK') {
                location.reload();
            } else {
                alert('Error: ' + data.Mensaje);
            }
        });
    }
}

function cambiarDisponibilidad(id, disponible) {
    fetch('index.php?controller=menuAdmin&action=cambiarDisponibilidad', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + id + '&disponible=' + disponible
    })
    .then(response => response.json())
    .then(data => {
        if (data.Status === 'OK') {
            location.reload();
        }
    });
}
</script>
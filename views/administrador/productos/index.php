<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold" style="color: #8C451C;">
            <i class="bi bi-bag"></i> Gestión de Productos
        </h1>
        <div>
            <a href="index.php?controller=administrador" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Panel
            </a>
            <button class="btn text-white" style="background-color: #F28322;" data-bs-toggle="modal" data-bs-target="#modalNuevoProducto">
                <i class="bi bi-plus-circle"></i> Nuevo Producto
            </button>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-black h-100 bg-light" >
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-2">Total Productos</h6>
                            <h2 class="mb-0 fw-bold"><?php echo count($productos); ?></h2>
                        </div>
                        <i class="bi bi-bag fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <!--
        <div class="col-md-3">
            <div class="card text-black h-100 bg-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-2">Valor Inventario</h6>
                            <h2 class="mb-0 fw-bold">
                                $<?php 
                                $valorTotal = array_sum(array_map(function($p) {
                                    return ($p['STOCK_ACTUAL'] ?? 0) * ($p['PRECIO_COMPRA'] ?? 0);
                                }, $productos));
                                echo number_format($valorTotal, 2);
                                ?>
                            </h2>
                        </div>
                        <i class="bi bi-cash-stack fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
                            -->
        <div class="col-md-3">
            <div class="card text-black h-100 bg-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-2">Stock Bajo</h6>
                            <h2 class="mb-0 fw-bold">
                                <?php 
                                echo count(array_filter($productos, function($p) {
                                    return isset($p['STOCK_ACTUAL']) && isset($p['STOCK_MINIMO']) 
                                           && $p['STOCK_ACTUAL'] <= $p['STOCK_MINIMO'];
                                }));
                                ?>
                            </h2>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
                            
        <!--
        <div class="col-md-3">
            <div class="card text-black h-100 bg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-2">Margen Prom.</h6>
                            <h2 class="mb-0 fw-bold">
                                <?php 
                                $promedioMargen = 0;
                                $productosConMargen = 0;
                                foreach ($productos as $p) {
                                    if (isset($p['PRECIO_COMPRA']) && $p['PRECIO_COMPRA'] > 0) {
                                        $margen = (($p['PRECIO_VENTA'] ?? 0) - $p['PRECIO_COMPRA']) / $p['PRECIO_COMPRA'] * 100;
                                        $promedioMargen += $margen;
                                        $productosConMargen++;
                                    }
                                }
                                echo $productosConMargen > 0 ? number_format($promedioMargen / $productosConMargen, 1) . '%' : '0%';
                                ?>
                            </h2>
                        </div>
                        <i class="bi bi-graph-up fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
                            -->
    <!-- Filtros -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" id="filtroNombre" class="form-control" placeholder="Buscar por nombre del producto...">
                </div>
                <div class="col-md-3">
                    <select id="filtroEstado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="BAJO">Stock Bajo</option>
                        <option value="OPTIMO">Stock Óptimo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn w-100" style="background-color: #8C451C; color: white;" onclick="filtrarProductos()">
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

    <!-- Tabla de Productos -->
    <div class="card shadow-sm">
        <div class="card-header" style="background-color: #8C451C; color: white;">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Catálogo de Productos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tablaProductos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Stock Actual</th>
                            <th>Stock Mínimo</th>
                            <th>P. Compra</th>
                            <th>P. Venta</th>
                            <th>Margen</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mt-2">No hay productos registrados</p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($productos as $producto): ?>
                            <?php 
                            $stockActual = $producto['STOCK_ACTUAL'] ?? 0;
                            $stockMinimo = $producto['STOCK_MINIMO'] ?? 0;
                            $esBajo = $stockActual <= $stockMinimo;
                            
                            $precioCompra = $producto['PRECIO_COMPRA'] ?? 0;
                            $precioVenta = $producto['PRECIO_VENTA'] ?? 0;
                            $margen = $precioCompra > 0 ? (($precioVenta - $precioCompra) / $precioCompra * 100) : 0;
                            ?>
                            <tr class="<?php echo $esBajo ? 'table-danger' : ''; ?>">
                                <td><?php echo $producto['ID_PRODUCTO'] ?? ''; ?></td>
                                <td><strong><?php echo $producto['NOMBRE'] ?? ''; ?></strong></td>
                                <td><?php echo $producto['DESCRIPCION'] ?? ''; ?></td>
                                <td>
                                    <strong class="<?php echo $esBajo ? 'text-danger' : 'text-success'; ?>">
                                        <?php echo $stockActual; ?>
                                    </strong>
                                </td>
                                <td><?php echo $stockMinimo; ?></td>
                                <td>$<?php echo number_format($precioCompra, 2); ?></td>
                                <td>$<?php echo number_format($precioVenta, 2); ?></td>
                                <td>
                                    <span class="badge <?php echo $margen >= 30 ? 'bg-success' : ($margen >= 15 ? 'bg-warning' : 'bg-danger'); ?>">
                                        <?php echo number_format($margen, 1); ?>%
                                    </span>
                                </td>
                                <td>
                                    <?php if ($esBajo): ?>
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-triangle"></i> BAJO
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> OK
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info text-white" onclick="verProducto(<?php echo $producto['ID_PRODUCTO'] ?? 0; ?>)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editarProducto(<?php echo $producto['ID_PRODUCTO'] ?? 0; ?>)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="eliminarProducto(<?php echo $producto['ID_PRODUCTO'] ?? 0; ?>)">
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

<!-- Modal Nuevo Producto -->
<div class="modal fade" id="modalNuevoProducto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F28322; color: white;">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Registrar Nuevo Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoProducto" method="POST" action="index.php?controller=administrador&action=crearProducto">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre del Producto *</label>
                            <input type="text" name="nombre" class="form-control" required placeholder="Ej: Refresco Coca-Cola">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Proveedor (opcional)</label>
                            <select name="id_proveedor" class="form-select">
                                <option value="">Sin proveedor asignado</option>
                                <?php foreach ($proveedores as $proveedor): ?>
                                    <option value="<?php echo $proveedor['ID_PROVEEDOR']; ?>">
                                        <?php echo htmlspecialchars($proveedor['NOMBRE']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2" placeholder="Descripción del producto..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Precio de Compra *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="precio_compra" class="form-control" required step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Precio de Venta *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="precio_venta" id="precio_venta" class="form-control" required step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Stock Actual *</label>
                            <input type="number" name="stock_actual" class="form-control" required step="1" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Stock Mínimo *</label>
                            <input type="number" name="stock_minimo" class="form-control" required step="1" min="0">
                            <small class="text-muted">Nivel de alerta para reabastecimiento</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formNuevoProducto" class="btn text-white" style="background-color: #F28322;">
                    <i class="bi bi-save"></i> Guardar Producto
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function filtrarProductos() {
    const nombre = document.getElementById('filtroNombre').value.toLowerCase();
    const estado = document.getElementById('filtroEstado').value;
    const filas = document.querySelectorAll('#tablaProductos tbody tr');
    
    filas.forEach(fila => {
        const textoFila = fila.textContent.toLowerCase();
        const estadoBadge = fila.querySelectorAll('.badge')[1]?.textContent || '';
        
        const coincideNombre = nombre === '' || textoFila.includes(nombre);
        const coincideEstado = estado === '' || estadoBadge.includes(estado);
        
        if (coincideNombre && coincideEstado) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

function limpiarFiltros() {
    document.getElementById('filtroNombre').value = '';
    document.getElementById('filtroEstado').value = '';
    filtrarProductos();
}

function verProducto(id) {
    alert('Ver detalles del producto ID: ' + id);
}

function editarProducto(id) {
    alert('Editar producto ID: ' + id);
}

function eliminarProducto(id) {
    if (confirm('¿Estás seguro de eliminar este producto?')) {
        alert('Producto eliminado - ID: ' + id);
    }
}
</script>
<script>

function filtrarProductos() {
    const nombre = document.getElementById('filtroNombre').value.toLowerCase();
    const estado = document.getElementById('filtroEstado').value;
    const filas = document.querySelectorAll('#tablaProductos tbody tr');
    
    filas.forEach(fila => {
        if (fila.querySelector('td[colspan]')) {
            return;
        }
        
        const textoFila = fila.textContent.toLowerCase();
        const estadoBadge = fila.querySelectorAll('.badge')[1]?.textContent || '';
        
        const coincideNombre = nombre === '' || textoFila.includes(nombre);
        const coincideEstado = estado === '' || estadoBadge.includes(estado);
        
        if (coincideNombre && coincideEstado) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

function limpiarFiltros() {
    document.getElementById('filtroNombre').value = '';
    document.getElementById('filtroEstado').value = '';
    filtrarProductos();
}

function verProducto(id) {
    fetch('index.php?controller=administrador&action=obtenerDetalleProducto&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.Status === 'OK') {
                const prod = data.producto;
                const margen = prod.ANALISIS_MARGEN;
                
                const detalleHTML = `
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <strong>Producto #${prod.ID_PRODUCTO}</strong>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <h5 class="fw-bold">${prod.NOMBRE}</h5>
                            <p class="text-muted">${prod.DESCRIPCION || 'Sin descripción'}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Precio de Compra</h6>
                                    <h4 class="text-primary mb-0">
                                        $${parseFloat(prod.PRECIO_COMPRA).toLocaleString('es-MX', {minimumFractionDigits: 2})}
                                    </h4>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Precio de Venta</h6>
                                    <h4 class="text-success mb-0">
                                        $${parseFloat(prod.PRECIO_VENTA).toLocaleString('es-MX', {minimumFractionDigits: 2})}
                                    </h4>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-warning text-dark">
                                <div class="card-body">
                                    <h6 class="mb-2">Margen Unitario</h6>
                                    <h4 class="mb-0">
                                        $${margen.margen_unitario.toFixed(2)}
                                    </h4>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h6 class="mb-2">% Margen</h6>
                                    <h4 class="mb-0">
                                        ${margen.porcentaje_margen.toFixed(1)}%
                                    </h4>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6 class="mb-2">Utilidad en Stock</h6>
                                    <h4 class="mb-0">
                                        $${margen.utilidad_por_stock.toFixed(2)}
                                    </h4>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Stock Actual:</strong>
                            <p class="mb-0 ${prod.STOCK_ACTUAL <= prod.STOCK_MINIMO ? 'text-danger' : 'text-success'} fw-bold">
                                ${prod.STOCK_ACTUAL} unidades
                                ${prod.STOCK_ACTUAL <= prod.STOCK_MINIMO ? '<i class="bi bi-exclamation-triangle"></i>' : ''}
                            </p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Stock Mínimo:</strong>
                            <p class="mb-0">${prod.STOCK_MINIMO} unidades</p>
                        </div>
                        
                        <div class="col-12">
                            <div class="alert alert-light">
                                <strong>Valor del Stock:</strong><br>
                                Costo: $${(prod.STOCK_ACTUAL * prod.PRECIO_COMPRA).toLocaleString('es-MX', {minimumFractionDigits: 2})}<br>
                                Venta: $${(prod.STOCK_ACTUAL * prod.PRECIO_VENTA).toLocaleString('es-MX', {minimumFractionDigits: 2})}
                            </div>
                        </div>
                    </div>
                `;
                
                mostrarModalDetalle('Detalles del Producto', detalleHTML);
            } else {
                alert('Error: ' + data.Mensaje);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al obtener detalles del producto');
        });
}

function editarProducto(id) {
    fetch('index.php?controller=administrador&action=obtenerProductoParaEditar&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.Status === 'OK') {
                const prod = data.producto;
                
                if (!document.getElementById('modalEditarProducto')) {
                    crearModalEditar();
                }
                document.getElementById('edit_id').value = prod.ID_PRODUCTO;
                document.getElementById('edit_nombre').value = prod.NOMBRE;
                document.getElementById('edit_descripcion').value = prod.DESCRIPCION || '';
                document.getElementById('edit_precio_compra').value = prod.PRECIO_COMPRA;
                document.getElementById('edit_precio_venta').value = prod.PRECIO_VENTA;
                document.getElementById('edit_stock_actual').value = prod.STOCK_ACTUAL;
                document.getElementById('edit_stock_minimo').value = prod.STOCK_MINIMO;
                document.getElementById('edit_id_proveedor').value = prod.ID_PROVEEDOR || '';
                
                calcularMargenEditar();
                
                const modal = new bootstrap.Modal(document.getElementById('modalEditarProducto'));
                modal.show();
            } else {
                alert('Error: ' + data.Mensaje);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar datos del producto');
        });
}

function crearModalEditar() {
    const modalHTML = `
        <div class="modal fade" id="modalEditarProducto" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #8C451C; color: white;">
                        <h5 class="modal-title"><i class="bi bi-pencil"></i> Editar Producto</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEditarProducto" method="POST" action="index.php?controller=administrador&action=editarProducto">
                            <input type="hidden" name="id" id="edit_id">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nombre del Producto *</label>
                                    <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Proveedor</label>
                                    <select name="id_proveedor" id="edit_id_proveedor" class="form-select">
                                        <option value="">Sin proveedor</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Descripción</label>
                                    <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Precio de Compra *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="precio_compra" id="edit_precio_compra" 
                                               class="form-control" required step="0.01" min="0" 
                                               onchange="calcularMargenEditar()">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Precio de Venta *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="precio_venta" id="edit_precio_venta" 
                                               class="form-control" required step="0.01" min="0"
                                               onchange="calcularMargenEditar()">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div id="margenEditar" class="alert alert-info" style="display:none;">
                                        <strong>Margen:</strong> <span id="margenTextoEditar"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Stock Actual *</label>
                                    <input type="number" name="stock_actual" id="edit_stock_actual" 
                                           class="form-control" required step="1" min="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Stock Mínimo *</label>
                                    <input type="number" name="stock_minimo" id="edit_stock_minimo" 
                                           class="form-control" required step="1" min="0">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" form="formEditarProducto" class="btn text-white" 
                                style="background-color: #8C451C;">
                            <i class="bi bi-save"></i> Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function calcularMargenEditar() {
    const precioCompra = parseFloat(document.getElementById('edit_precio_compra').value) || 0;
    const precioVenta = parseFloat(document.getElementById('edit_precio_venta').value) || 0;
    
    if (precioCompra > 0) {
        const margen = precioVenta - precioCompra;
        const porcentaje = (margen / precioCompra) * 100;
        
        const divMargen = document.getElementById('margenEditar');
        const textoMargen = document.getElementById('margenTextoEditar');
        
        let colorClass = 'alert-info';
        if (porcentaje < 15) colorClass = 'alert-danger';
        else if (porcentaje < 30) colorClass = 'alert-warning';
        else colorClass = 'alert-success';
        
        divMargen.className = `alert ${colorClass}`;
        divMargen.style.display = 'block';
        textoMargen.textContent = `$${margen.toFixed(2)} (${porcentaje.toFixed(1)}%)`;
    }
}

function eliminarProducto(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este producto?\n\nEsta acción no se puede deshacer.')) {
        const formData = new FormData();
        formData.append('id', id);
        
        fetch('index.php?controller=administrador&action=eliminarProducto', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.Status === 'OK') {
                mostrarMensaje('Producto eliminado exitosamente', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                alert('Error: ' + (data.Mensaje || 'No se pudo eliminar el producto'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión. Por favor intente nuevamente.');
        });
    }
}

function calcularMargenNuevo() {
    const precioCompra = parseFloat(document.querySelector('#formNuevoProducto input[name="precio_compra"]').value) || 0;
    const precioVenta = parseFloat(document.querySelector('#formNuevoProducto input[name="precio_venta"]').value) || 0;
    
    let divMargen = document.getElementById('margenNuevo');
    if (!divMargen) {
        divMargen = document.createElement('div');
        divMargen.id = 'margenNuevo';
        divMargen.className = 'alert alert-info col-12';
        
        const container = document.querySelector('#formNuevoProducto .row');
        container.appendChild(divMargen);
    }
    
    if (precioCompra > 0 && precioVenta > 0) {
        const margen = precioVenta - precioCompra;
        const porcentaje = (margen / precioCompra) * 100;
        
        let colorClass = 'alert-info';
        let mensaje = '';
        
        if (margen < 0) {
            colorClass = 'alert-danger';
            mensaje = '¡Pérdida! ';
        } else if (porcentaje < 15) {
            colorClass = 'alert-danger';
            mensaje = 'Margen bajo - ';
        } else if (porcentaje < 30) {
            colorClass = 'alert-warning';
            mensaje = 'Margen medio - ';
        } else {
            colorClass = 'alert-success';
            mensaje = 'Buen margen - ';
        }
        
        divMargen.className = `alert ${colorClass} col-12`;
        divMargen.innerHTML = `<strong>${mensaje}Margen:</strong> $${margen.toFixed(2)} (${porcentaje.toFixed(1)}%)`;
        divMargen.style.display = 'block';
    } else {
        divMargen.style.display = 'none';
    }
}

function mostrarModalDetalle(titulo, contenidoHTML) {
    const modalAnterior = document.getElementById('modalDetalleProducto');
    if (modalAnterior) {
        modalAnterior.remove();
    }
    
    const modalHTML = `
        <div class="modal fade" id="modalDetalleProducto" tabindex="-1">
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
    
    const modal = new bootstrap.Modal(document.getElementById('modalDetalleProducto'));
    modal.show();
    
    document.getElementById('modalDetalleProducto').addEventListener('hidden.bs.modal', function () {
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

function cargarProveedoresEnSelect() {
    fetch('index.php?controller=administrador&action=obtenerProveedoresSelect')
        .then(response => response.json())
        .then(data => {
            if (data.Status === 'OK') {
                const selectNuevo = document.querySelector('#modalNuevoProducto select[name="id_proveedor"]');
                if (selectNuevo) {
                    llenarSelectProveedores(selectNuevo, data.proveedores);
                }
                
                const selectEditar = document.getElementById('edit_id_proveedor');
                if (selectEditar) {
                    llenarSelectProveedores(selectEditar, data.proveedores);
                }
            }
        })
        .catch(error => console.error('Error:', error));
}

function llenarSelectProveedores(selectElement, proveedores) {
    const valorActual = selectElement.value;
    
    while (selectElement.options.length > 1) {
        selectElement.remove(1);
    }
    
    proveedores.forEach(proveedor => {
        const option = document.createElement('option');
        option.value = proveedor.ID_PROVEEDOR;
        option.textContent = proveedor.NOMBRE;
        selectElement.appendChild(option);
    });
    
    if (valorActual) {
        selectElement.value = valorActual;
    }
}

let timeoutBusqueda;
document.getElementById('filtroNombre')?.addEventListener('input', function() {
    clearTimeout(timeoutBusqueda);
    timeoutBusqueda = setTimeout(() => {
        filtrarProductos();
    }, 300);
});

document.getElementById('filtroEstado')?.addEventListener('change', function() {
    filtrarProductos();
});

document.addEventListener('DOMContentLoaded', function() {
    const inputsNuevo = document.querySelectorAll('#formNuevoProducto input[name="precio_compra"], #formNuevoProducto input[name="precio_venta"]');
    inputsNuevo.forEach(input => {
        input.addEventListener('input', calcularMargenNuevo);
    });
    
    document.getElementById('modalNuevoProducto')?.addEventListener('show.bs.modal', function() {
        cargarProveedoresEnSelect();
    });
});

function exportarCSV() {
    window.location.href = 'index.php?controller=administrador&action=exportarProductosCSV';
}
document.getElementById('modalNuevoProducto')?.addEventListener('hidden.bs.modal', function () {
    document.getElementById('formNuevoProducto').reset();
    const divMargen = document.getElementById('margenNuevo');
    if (divMargen) {
        divMargen.style.display = 'none';
    }
});

document.getElementById('modalEditarProducto')?.addEventListener('hidden.bs.modal', function () {
    document.getElementById('formEditarProducto')?.reset();
});

document.addEventListener('DOMContentLoaded', function() {
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

.table tbody tr.table-danger {
    background-color: rgba(220, 53, 69, 0.1);
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
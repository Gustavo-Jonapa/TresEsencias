<div class="container my-5">
    <div class="mb-4">
        <a href="index.php?controller=menu" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Menú
        </a>
    </div>
    
    <h1 class="text-center mb-5 display-4 fw-bold" style="color: #8C451C;">
        <?php 
        $titulos = [
            'platos' => 'Platos Fuertes',
            'postres' => 'Postres',
            'bebidas' => 'Bebidas'
        ];
        echo $titulos[$categoria] ?? 'Menú'; 
        ?>
    </h1>
    
    <div class="row g-4">
        <?php if (count($platillos) > 0): ?>
            <?php foreach ($platillos as $platillo): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="<?php echo $platillo['']; ?>" class="card-img-top" alt="<?php echo $platillo['nombre']; ?>" style="height: 250px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?php echo $platillo['NOMBRE']; ?></h5>
                        <?php if (!empty($platillo['DESCRIPCION'])): ?>
                        <p class="card-text text-muted small"><?php echo $platillo['DESCRIPCION']; ?></p>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="fs-3 fw-bold mb-0" style="color: #F28322;">$<?php echo number_format($platillo['PRECIO'], 2); ?></p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle fs-3"></i>
                    <p class="mb-0 mt-2">No hay platillos disponibles en esta categoría.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2) !important;
        transition: all 0.3s ease;
    }
</style>

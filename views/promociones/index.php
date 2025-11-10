<div class="container my-5">
    <h1 class="text-center mb-5 display-4 fw-bold" style="color: #8C451C;">Promociones Especiales</h1>
    
    <div class="row g-4">
        <?php foreach ($promociones as $promo): ?>
        <div class="col-md-6">
            <div class="card h-100 shadow-lg border-0">
                <!-- imagen por defecto -->
                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600" 
                    class="card-img-top" alt="<?php echo $promo['DESCRIPCION']; ?>" 
                    style="height: 300px; object-fit: cover;">
                <div class="card-body">
                    <h3 class="card-title fw-bold"><?php echo $promo['DESCRIPCION']; ?></h3>
                    <p class="text-muted">
                        <i class="bi bi-calendar"></i> 
                        Válido del <?php echo date('d/m/Y', strtotime($promo['FECHA_INICIO'])); ?> 
                        al <?php echo date('d/m/Y', strtotime($promo['FECHA_FIN'])); ?>
                    </p>
                    <?php if ($promo['DESCUENTO_PORCENTAJE'] > 0): ?>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="display-6 fw-bold" style="color: #F28322;">
                            <?php echo $promo['DESCUENTO_PORCENTAJE']; ?>% OFF
                        </span>
                        <a href="index.php?controller=reservacion" class="btn btn-lg text-white" style="background-color: #F28322;">
                            <i class="bi bi-calendar-check"></i> Reservar
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

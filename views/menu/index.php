<div class="container my-5">
    <h1 class="text-center mb-5 display-4 fw-bold" style="color: #8C451C;">Nuestro Menú</h1>
    
    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <a href="index.php?controller=menu&action=categoria&cat=platos" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 text-center" style="transition: transform 0.3s;">
                    <div class="card-body p-4">
                        <div class="icon-circle mb-3" style="width: 100px; height: 100px; background-color: #F28322; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="bi bi-egg-fried fs-1 text-white"></i>
                        </div>
                        <h3 class="fw-bold" style="color: #8C451C;">Platos Fuertes</h3>
                        <p class="text-muted">Descubre nuestros platillos principales</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-4">
            <a href="index.php?controller=menu&action=categoria&cat=postres" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 text-center" style="transition: transform 0.3s;">
                    <div class="card-body p-4">
                        <div class="icon-circle mb-3" style="width: 100px; height: 100px; background-color: #F28322; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="bi bi- fs-1 text-white"></i>
                        </div>
                        <h3 class="fw-bold" style="color: #8C451C;">Postres</h3>
                        <p class="text-muted">Endulza tu experiencia</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-4">
            <a href="index.php?controller=menu&action=categoria&cat=bebidas" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 text-center" style="transition: transform 0.3s;">
                    <div class="card-body p-4">
                        <div class="icon-circle mb-3" style="width: 100px; height: 100px; background-color: #F28322; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="bi bi-cup-straw fs-1 text-white"></i>
                        </div>
                        <h3 class="fw-bold" style="color: #8C451C;">Bebidas</h3>
                        <p class="text-muted">Refrescantes opciones</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    
    <div class="text-center mb-4">
        <h2 class="fw-bold" style="color: #8C451C;">Platillos Destacados</h2>
        <p class="text-muted">Los favoritos de nuestros clientes</p>
    </div>
    
    <div class="row g-4">
        <?php 
        $destacados = array_slice($platillos, 0, 3);
        foreach ($destacados as $platillo): 
        ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="<?php echo $platillo['']; ?>" class="card-img-top" alt="<?php echo $platillo['nombre']; ?>" style="height: 250px; object-fit: cover;">
                <div class="card-body">
                    <span class="badge mb-2" style="background-color: #F28322;"><?php echo $platillo['TIPO']; ?></span>
                    <h5 class="card-title fw-bold"><?php echo $platillo['NOMBRE']; ?></h5>
                    <p class="card-text text-muted small"><?php echo $platillo['DESCRIPCION']; ?></p>
                    <p class="fs-3 fw-bold mb-0" style="color: #F28322;">$<?php echo number_format($platillo['PRECIO'], 2); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    
</style>

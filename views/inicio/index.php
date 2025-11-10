<style>
    .carousel-img {
        height: 500px;
        object-fit: cover;
        filter: brightness(0.7);
    }
    
    .carousel-caption {
        background: rgba(0, 0, 0, 0.6);
        padding: 2rem;
        border-radius: 15px;
        backdrop-filter: blur(5px);
    }
    
    .button-menu {
        background-color: #F28322;
        color: #F3F2F1;
        padding: 15px 40px;
        border-radius: 40px;
        border: 0;
        font-size: large;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
        /*transition: all 0.3s ease;*/
    }
    /*
    .button-menu:hover {
        color: #F3F2F1;
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(242, 131, 34, 0.4);
    }
    */
    .card-custom {
        /*transition: all 0.3s ease;*/
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    /*
    .card-custom:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
    }
    */
    .icon-circle {
        width: 80px;
        height: 80px;
        background-color: #F28322;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .icon-circle img {
        width: 50px;
        height: 50px;
    }
    
    @media (max-width: 768px) {
        .carousel-img {
            height: 300px;
        }
    }
        
</style>

<article>
    <!-- Carrusel -->
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200&h=500&fit=crop" class="d-block w-100 carousel-img" alt="Restaurante Interior">
                <div class="carousel-caption d-none d-md-block">
                    <h1 class="display-3 fw-bold">Bienvenidos a Tres Esencias</h1>
                    <p class="fs-4">Experiencia culinaria única en cada platillo</p>
                    <a href="index.php?controller=menu" class="button-menu">Ver Menú</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1200&h=500&fit=crop" class="d-block w-100 carousel-img" alt="Platillos Gourmet">
                <div class="carousel-caption d-none d-md-block">
                    <h1 class="display-3 fw-bold">Platillos Gourmet</h1>
                    <p class="fs-4">Preparados con ingredientes de la más alta calidad</p>
                    <a href="index.php?controller=menu" class="button-menu">Descubrir</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&h=500&fit=crop" class="d-block w-100 carousel-img" alt="Eventos Especiales">
                <div class="carousel-caption d-none d-md-block">
                    <h1 class="display-3 fw-bold">Eventos Especiales</h1>
                    <p class="fs-4">El lugar perfecto para tus celebraciones</p>
                    <a href="index.php?controller=promocion" class="button-menu">Ver Promociones</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
    
    <div class="container my-5 py-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-custom h-100">
                    <div class="card-body text-center p-4">
                        <div class="icon-circle">
                            <i class="bi bi-people-fill fs-1 text-white"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Conócenos</h3>
                        <p class="text-muted">
                            Somos un restaurante familiar. 
                            Nuestros chefs preparan cada platillo con ingredientes frescos y de la más alta calidad. 
                            Nuestra pasión es brindar momentos inolvidables a cada uno de nuestros comensales.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom h-100">
                    <div class="card-body text-center p-4">
                        <div class="icon-circle">
                            <i class="bi bi-telephone-fill fs-1 text-white"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Contáctanos</h3>
                        <div class="contact-info text-start">
                            <p class="mb-2">
                                <i class="bi bi-telephone text-black"></i> 
                                <strong>Teléfono:</strong> 961-849-4215
                            </p>
                            <p class="mb-2">
                                <i class="bi bi-whatsapp text-black"></i> 
                                <strong>WhatsApp:</strong> 961-849-4215
                            </p>
                            <p class="mb-2">
                                <i class="bi bi-envelope text-black"></i> 
                                <strong>Email:</strong> contacto@tresesencias.com
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom h-100">
                    <div class="card-body text-center p-4">
                        <div class="icon-circle">
                            <i class="bi bi-geo-alt-fill fs-1 text-white"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Ubícanos</h3>
                        <p class="text-muted">
                            <br>
                            <br>
                            Tuxtla Gutiérrez, Chiapas<br>
                            C.P. 29000
                        </p>
                        <p class="mt-3">
                            <i class="bi bi-clock text-black"></i> 
                            <strong>Horario:</strong><br>
                            Lun-Dom: 1:00 PM - 11:00 PM
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
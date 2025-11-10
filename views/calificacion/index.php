<div class="container my-5">
    <h1 class="text-center mb-5 display-4 fw-bold" style="color: #8C451C;">Califícanos</h1>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 mb-5">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 fw-bold" style="color: #F28322;">Tu opinión es importante para nosotros</h3>
                    
                    <form action="index.php?controller=calificacion&action=enviar" method="POST" id="formCalificacion">
                        <div class="text-center mb-4">
                            <label class="form-label fw-bold d-block mb-3">Calificación General</label>
                            <div class="rating" id="rating">
                                <input type="radio" name="calificacion" value="5" id="star5" required>
                                <label for="star5" title="5 estrellas">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                                
                                <input type="radio" name="calificacion" value="4" id="star4">
                                <label for="star4" title="4 estrellas">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                                
                                <input type="radio" name="calificacion" value="3" id="star3">
                                <label for="star3" title="3 estrellas">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                                
                                <input type="radio" name="calificacion" value="2" id="star2">
                                <label for="star2" title="2 estrellas">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                                
                                <input type="radio" name="calificacion" value="1" id="star1">
                                <label for="star1" title="1 estrella">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">Haz clic en las estrellas para calificar</small>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Tipo de comentario</label>
                            <select name="tipo" class="form-select form-select-lg" required>
                                <option value="">Seleccionar...</option>
                                <option value="sugerencia">Sugerencia</option>
                                <option value="queja">Queja</option>
                                <option value="felicitacion">Felicitación</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Cuéntanos tu experiencia</label>
                            <textarea name="comentario" class="form-control form-control-lg" rows="5" 
                                      placeholder="Escribe aquí tu comentario, sugerencia o queja..." required></textarea>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nombre (opcional)</label>
                                <input type="text" name="nombre" class="form-control form-control-lg" 
                                       placeholder="Tu nombre">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email (opcional)</label>
                                <input type="email" name="email" class="form-control form-control-lg" 
                                       placeholder="tu@email.com">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-lg w-100 fw-bold text-white" style="background-color: #F28322;">
                            <i class="bi bi-send"></i> Enviar Calificación
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rating {
        display: inline-flex;
        flex-direction: row-reverse;
        justify-content: center;
        gap: 5px;
    }
    
    .rating input {
        display: none;
    }
    
    .rating label {
        cursor: pointer;
        font-size: 3rem;
        color: #ddd;
        transition: color 0.2s;
    }
    
    .rating label:hover,
    .rating label:hover ~ label,
    .rating input:checked ~ label {
        color: #FFD700;
    }
    
    .rating label i {
        transition: transform 0.2s;
    }
    
    .rating label:hover i {
        transform: scale(1.1);
    }
</style>

<script>
    document.getElementById('formCalificacion').addEventListener('submit', function(e) {
        const calificacion = document.querySelector('input[name="calificacion"]:checked');
        
        if (!calificacion) {
            e.preventDefault();
            alert('Por favor selecciona una calificación con estrellas');
            return false;
        }
    });
</script>

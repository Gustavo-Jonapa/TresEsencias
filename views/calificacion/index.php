<div class="container my-5">
    <h1 class="text-center mb-5 display-4 fw-bold" style="color: #8C451C;">Califícanos</h1>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Formulario de calificación -->
            <div class="card shadow-lg border-0 mb-5">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 fw-bold" style="color: #F28322;">Tu opinión es importante para nosotros</h3>
                    
                    <form action="index.php?controller=calificacion&action=enviar" method="POST" id="formCalificacion">
                        <div class="text-center mb-4">
                            <label class="form-label fw-bold d-block mb-3">Calificación General</label>
                            <div class="rating" id="rating">
                                <input type="radio" name="CALIFICACION" value="5" id="star5" required>
                                <label for="star5" title="5 estrellas">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                                
                                <input type="radio" name="CALIFICACION" value="4" id="star4">
                                <label for="star4" title="4 estrellas">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                                
                                <input type="radio" name="CALIFICACION" value="3" id="star3">
                                <label for="star3" title="3 estrellas">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                                
                                <input type="radio" name="CALIFICACION" value="2" id="star2">
                                <label for="star2" title="2 estrellas">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                                
                                <input type="radio" name="CALIFICACION" value="1" id="star1">
                                <label for="star1" title="1 estrella">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">Haz clic en las estrellas para calificar</small>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Tipo de comentario</label>
                            <select name="TIPO" class="form-select form-select-lg" required>
                                <option value="">Seleccionar...</option>
                                <option value="SUGERENCIA">Sugerencia</option>
                                <option value="QUEJA">Queja</option>
                                <option value="FELICITACION">Felicitación</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Cuéntanos tu experiencia</label>
                            <textarea name="COMENTARIO" class="form-control form-control-lg" rows="5" 
                                      placeholder="Escribe aquí tu comentario, sugerencia o queja..." required></textarea>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nombre (opcional)</label>
                                <input type="text" name="NOMBRE" class="form-control form-control-lg" 
                                       placeholder="Tu nombre">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email (opcional)</label>
                                <input type="email" name="EMAIL" class="form-control form-control-lg" 
                                       placeholder="tu@email.com">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-lg w-100 fw-bold text-white" style="background-color: #F28322;">
                            <i class="bi bi-send"></i> Enviar Calificación
                        </button>
                    </form>
                </div>
            </div>

            <!-- Estadísticas de calificaciones -->
            <?php if (isset($estadisticas) && $estadisticas['TOTAL_CALIFICACIONES'] > 0): ?>
            <div class="card shadow-lg border-0 mb-5">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4" style="color: #8C451C;">Calificación Promedio</h4>
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            <h1 class="display-1 fw-bold" style="color: #F28322;">
                                <?php echo number_format($estadisticas['PROMEDIO_CALIFICACION'], 1); ?>
                            </h1>
                            <div class="mb-2">
                                <?php
                                $promedio = $estadisticas['PROMEDIO_CALIFICACION'];
                                $estrellas_llenas = floor($promedio);
                                $media_estrella = ($promedio - $estrellas_llenas) >= 0.5;
                                
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $estrellas_llenas) {
                                        echo '<i class="bi bi-star-fill fs-4" style="color: #FFD700;"></i>';
                                    } elseif ($i == $estrellas_llenas + 1 && $media_estrella) {
                                        echo '<i class="bi bi-star-half fs-4" style="color: #FFD700;"></i>';
                                    } else {
                                        echo '<i class="bi bi-star fs-4" style="color: #FFD700;"></i>';
                                    }
                                }
                                ?>
                            </div>
                            <p class="text-muted">
                                Basado en <?php echo $estadisticas['TOTAL_CALIFICACIONES']; ?> 
                                <?php echo $estadisticas['TOTAL_CALIFICACIONES'] == 1 ? 'opinión' : 'opiniones'; ?>
                            </p>
                        </div>
                        <div class="col-md-8">
                            <?php
                            $total = $estadisticas['TOTAL_CALIFICACIONES'];
                            
                            // Calcular porcentajes
                            $porcentajes = [
                                5 => ($total > 0) ? round(($estadisticas['CINCO_ESTRELLAS'] / $total) * 100) : 0,
                                4 => ($total > 0) ? round(($estadisticas['CUATRO_ESTRELLAS'] / $total) * 100) : 0,
                                3 => ($total > 0) ? round(($estadisticas['TRES_ESTRELLAS'] / $total) * 100) : 0,
                                2 => ($total > 0) ? round(($estadisticas['DOS_ESTRELLAS'] / $total) * 100) : 0,
                                1 => ($total > 0) ? round(($estadisticas['UNA_ESTRELLA'] / $total) * 100) : 0,
                            ];
                            
                            for ($i = 5; $i >= 1; $i--):
                            ?>
                            <div class="mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="me-2"><?php echo $i; ?> <i class="bi bi-star-fill" style="color: #FFD700;"></i></span>
                                    <div class="progress flex-grow-1" style="height: 20px;">
                                        <div class="progress-bar" style="width: <?php echo $porcentajes[$i]; ?>%; background-color: #F28322;"></div>
                                    </div>
                                    <span class="ms-2"><?php echo $porcentajes[$i]; ?>%</span>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Comentarios recientes -->
            <?php if (!empty($calificacionesRecientes)): ?>
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4" style="color: #8C451C;">Comentarios Recientes</h4>
                    
                    <?php foreach ($calificacionesRecientes as $index => $calificacion): ?>
                    <div class="<?php echo ($index < count($calificacionesRecientes) - 1) ? 'mb-4 pb-3 border-bottom' : 'mb-0'; ?>">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold mb-1">
                                    <?php echo htmlspecialchars($calificacion['NOMBRE'] ?? 'Anónimo'); ?>
                                </h6>
                                <div class="mb-1">
                                    <?php
                                    $estrellas = intval($calificacion['CALIFICACION']);
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $estrellas) {
                                            echo '<i class="bi bi-star-fill" style="color: #FFD700;"></i>';
                                        } else {
                                            echo '<i class="bi bi-star" style="color: #FFD700;"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <?php if (!empty($calificacion['TIPO'])): ?>
                                <span class="badge 
                                    <?php 
                                    switch($calificacion['TIPO']) {
                                        case 'FELICITACION':
                                            echo 'bg-success';
                                            break;
                                        case 'QUEJA':
                                            echo 'bg-danger';
                                            break;
                                        case 'SUGERENCIA':
                                            echo 'bg-info';
                                            break;
                                        default:
                                            echo 'bg-secondary';
                                    }
                                    ?>">
                                    <?php echo ucfirst(strtolower($calificacion['TIPO'])); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted">
                                <?php 
                                if (isset($calificacion['FECHA_REGISTRO'])) {
                                    $fecha = new DateTime($calificacion['FECHA_REGISTRO']);
                                    $ahora = new DateTime();
                                    $diferencia = $ahora->diff($fecha);
                                    
                                    if ($diferencia->days == 0) {
                                        echo 'Hoy';
                                    } elseif ($diferencia->days == 1) {
                                        echo 'Ayer';
                                    } elseif ($diferencia->days < 7) {
                                        echo 'Hace ' . $diferencia->days . ' días';
                                    } elseif ($diferencia->days < 30) {
                                        $semanas = floor($diferencia->days / 7);
                                        echo 'Hace ' . $semanas . ($semanas == 1 ? ' semana' : ' semanas');
                                    } else {
                                        echo $fecha->format('d/m/Y');
                                    }
                                } else {
                                    echo 'Fecha no disponible';
                                }
                                ?>
                            </small>
                        </div>
                        <p class="text-muted mb-0">
                            <?php echo htmlspecialchars($calificacion['COMENTARIO']); ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="card shadow-lg border-0">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-chat-left-text fs-1 mb-3" style="color: #8C451C;"></i>
                    <h4 class="fw-bold mb-3" style="color: #8C451C;">Sé el primero en calificarnos</h4>
                    <p class="text-muted">Aún no tenemos calificaciones. ¡Comparte tu experiencia con nosotros!</p>
                </div>
            </div>
            <?php endif; ?>
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
        const calificacion = document.querySelector('input[name="CALIFICACION"]:checked');
        
        if (!calificacion) {
            e.preventDefault();
            alert('Por favor selecciona una calificación con estrellas');
            return false;
        }
    });
</script>
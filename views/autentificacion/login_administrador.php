<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-lock fs-1" style="color: #8C451C;"></i>
                        <h2 class="mt-3 fw-bold" style="color: #8C451C;">Acceso Administrador</h2>
                        <p class="text-muted">Panel de administración del sistema</p>
                    </div>
                    
                    <form action="index.php?controller=auth&action=loginAdministrador" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Usuario</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="usuario" class="form-control" 
                                       placeholder="Usuario administrador" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Contraseña</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" 
                                       placeholder="••••••••" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-lg w-100 fw-bold text-white mb-3" style="background-color: #8C451C;">
                            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                        </button>
                        
                        <hr>
                        
                        <p class="text-center mb-0">
                            <a href="index.php?controller=auth&action=mostrarLoginRecepcion" class="text-decoration-none" style="color: #F28322;">
                                <i class="bi bi-arrow-left"></i> Ir a login de recepción
                            </a>
                        </p>
                        <p class="text-center mb-0 mt-2">
                            <a href="index.php?controller=auth&action=login" class="text-decoration-none" style="color: #F28322;">
                                <i class="bi bi-arrow-left"></i> Volver al login de clientes
                            </a>
                        </p>
                    </form>
                </div>
            </div>
            
            <div class="card mt-3 border-0" style="background-color: #FFF5E1;">
                <div class="card-body text-center">
                    <small class="text-muted">
                        <i class="bi bi-shield-check"></i> 
                        Acceso exclusivo para administradores del sistema
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
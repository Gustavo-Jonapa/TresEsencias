<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4 fw-bold" style="color: #8C451C;">Iniciar Sesión</h2>
                    
                    <form action="index.php?controller=auth&action=login" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Usuario</label>
                            <input type="text" name="usuario" class="form-control form-control-lg" 
                                placeholder="tu_usuario" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Contraseña</label>
                            <input type="password" name="password" class="form-control form-control-lg" 
                                placeholder="••••••••" required>
                        </div>
                        
                        <button type="submit" class="btn btn-lg w-100 fw-bold text-white mb-3" style="background-color: #F28322;">
                            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                        </button>
                        
                        <hr>
                        
                        <p class="text-center mb-0">
                            ¿No tienes cuenta? 
                            <a href="index.php?controller=auth&action=register" class="text-decoration-none fw-bold" style="color: #F28322;">
                                Regístrate aquí
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
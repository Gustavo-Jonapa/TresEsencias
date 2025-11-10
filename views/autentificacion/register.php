<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4 fw-bold" style="color: #8C451C;">Crear Cuenta</h2>
                    
                    <form action="index.php?controller=auth&action=register" method="POST" id="formRegister">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control form-control-lg" 
                                   placeholder="Juan Pérez" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control form-control-lg" 
                                   placeholder="tu@email.com" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Teléfono</label>
                            <input type="tel" name="telefono" class="form-control form-control-lg" 
                                   placeholder="961-123-4567" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Contraseña</label>
                            <input type="password" name="password" id="password" class="form-control form-control-lg" 
                                   placeholder="••••••••" required minlength="6">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Confirmar Contraseña</label>
                            <input type="password" name="confirm_password" id="confirm_password" 
                                   class="form-control form-control-lg" placeholder="••••••••" required>
                            <div class="invalid-feedback">Las contraseñas no coinciden</div>
                        </div>
                        
                        <button type="submit" class="btn btn-lg w-100 fw-bold text-white mb-3" style="background-color: #F28322;">
                            <i class="bi bi-person-plus"></i> Crear Cuenta
                        </button>
                        
                        <hr>
                        
                        <p class="text-center mb-0">
                            ¿Ya tienes cuenta? 
                            <a href="index.php?controller=auth&action=login" class="text-decoration-none fw-bold" style="color: #F28322;">
                                Inicia sesión aquí
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('formRegister').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const confirmInput = document.getElementById('confirm_password');
        
        if (password !== confirmPassword) {
            e.preventDefault();
            confirmInput.classList.add('is-invalid');
            return false;
        } else {
            confirmInput.classList.remove('is-invalid');
        }
    });
    
    document.getElementById('confirm_password').addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });
</script>
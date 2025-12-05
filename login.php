<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CECYTE - Sistema de Mantenimiento</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/simple-style.css" rel="stylesheet">
</head>

<body class="login-page">
    <div class="bg-animation">
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-tools"></i>
            </div>
            <h1 class="login-title">CECYTE</h1>
            <p class="login-subtitle">Colegio de Estudios Científicos y Tecnológicos<br>Sistema de Mantenimiento</p>
        </div>

        <?php
        session_start();
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-error">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        ?>

        <form action="Lconexion.php" method="post" class="login-form">
            <div class="form-group">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" name="usuario" id="usuario" class="form-input" placeholder="Ingresa tu usuario" required>
                <i class="fas fa-user input-icon"></i>
            </div>

            <div class="form-group">
                <label for="contraseña" class="form-label">Contraseña</label>
                <input type="password" name="contraseña" id="contraseña" class="form-input" placeholder="Ingresa tu contraseña" required>
                <i class="fas fa-lock input-icon"></i>
            </div>

            <button type="submit" name="ejecutar" class="btn-login">
                <span class="btn-text">Iniciar Sesión</span>
            </button>
        </form>

        <div class="login-links">
            <a href="usuario.php" class="login-link">
                <i class="fas fa-user-plus"></i> Registrarse
            </a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript simplificado para login
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.querySelector('.btn-login');
            
            form.addEventListener('submit', function() {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Iniciando...';
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html>

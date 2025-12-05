<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CECYTE Mantenimiento</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/simple-style.css" rel="stylesheet">
</head>

<body class="dashboard-container">
    <?php
    session_start();
    if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
        header("Location: login.php");
        exit();
    }
    ?>

    <div class="dashboard-header">
        <div class="container">
            <h1 class="dashboard-title">
                <i class="fas fa-tools me-3"></i>
                Sistema de Mantenimiento CECYTE
            </h1>
            <p class="dashboard-subtitle">
                Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>
            </p>
            <?php
            if (isset($_SESSION['success_message'])) {
                echo '<div class="alert alert-success mt-3 fade-in">' . $_SESSION['success_message'] . '</div>';
                unset($_SESSION['success_message']);
            }
            ?>
        </div>
    </div>

    <div class="container">
        <div class="dashboard-nav">
            <a href="insertar.php" class="nav-card fade-in">
                <div class="nav-card-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <h3 class="nav-card-title">Insertar Registros</h3>
                <p class="nav-card-desc">Agregar nuevos registros de mantenimiento y personal al sistema</p>
            </a>

            <a href="select.php" class="nav-card fade-in">
                <div class="nav-card-icon">
                    <i class="fas fa-list"></i>
                </div>
                <h3 class="nav-card-title">Ver Registros</h3>
                <p class="nav-card-desc">Consultar y visualizar todos los registros guardados</p>
            </a>

            <a href="modificar.php" class="nav-card fade-in">
                <div class="nav-card-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3 class="nav-card-title">Editar Registros</h3>
                <p class="nav-card-desc">Modificar información existente de mantenimiento y personal</p>
            </a>

            <a href="eliminar.php" class="nav-card fade-in">
                <div class="nav-card-icon">
                    <i class="fas fa-trash"></i>
                </div>
                <h3 class="nav-card-title">Eliminar Registros</h3>
                <p class="nav-card-desc">Remover registros del sistema de manera segura</p>
            </a>

            <a href="reportes.php" class="nav-card fade-in">
                <div class="nav-card-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <h3 class="nav-card-title">Reportes</h3>
                <p class="nav-card-desc">Generar reportes en PDF de mantenimiento y personal</p>
            </a>

            <a href="cerrar.php" class="nav-card fade-in" style="background: #fee; border-left: 4px solid #e53e3e;">
                <div class="nav-card-icon" style="background: linear-gradient(135deg, #e53e3e, #c53030);">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <h3 class="nav-card-title" style="color: #e53e3e;">Cerrar Sesión</h3>
                <p class="nav-card-desc">Salir del sistema de manera segura</p>
            </a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript simplificado para dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Animación simple para las tarjetas
            const cards = document.querySelectorAll('.nav-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });

        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - CECYTE</title>
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

    require_once 'config/conexion.php';

    try {
        $db = new Database();
        $conexion = $db->getConnection();
    } catch (Exception $e) {
        die("Error de conexión: " . $e->getMessage());
    }

    $mensaje = '';
    $tipo_mensaje = '';

    if (isset($_POST['insert'])) {
        $usuario = trim($_POST['usuario']);
        $contraseña = trim($_POST['contraseña']);

        if (!empty($usuario) && !empty($contraseña)) {
            try {
                $stmt = $conexion->prepare("INSERT INTO login (usuario, contraseña) VALUES (?, ?)");
                $stmt->bind_param("ss", $usuario, $contraseña);
                
                if ($stmt->execute()) {
                    $mensaje = 'Usuario creado correctamente.';
                    $tipo_mensaje = 'success';
                } else {
                    $mensaje = 'Error al crear usuario.';
                    $tipo_mensaje = 'error';
                }
                $stmt->close();
            } catch (Exception $e) {
                $mensaje = 'Error: ' . $e->getMessage();
                $tipo_mensaje = 'error';
            }
        } else {
            $mensaje = 'Por favor complete todos los campos.';
            $tipo_mensaje = 'error';
        }
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #667eea, #764ba2);">
        <div class="container">
            <a class="navbar-brand" href="inicio.php">
                <i class="fas fa-tools me-2"></i>CECYTE Mantenimiento
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="inicio.php"><i class="fas fa-home me-1"></i>Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="usuario.php"><i class="fas fa-user-plus me-1"></i>Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cerrar.php"><i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="form-container">
            <h2 class="form-title">
                <i class="fas fa-user-plus me-3"></i>Crear Nuevo Usuario
            </h2>

            <?php if ($mensaje): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <form action="usuario.php" method="post">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" name="usuario" id="usuario" class="form-input" placeholder="Nombre de usuario" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contraseña" class="form-label">Contraseña</label>
                        <input type="password" name="contraseña" id="contraseña" class="form-input" placeholder="Contraseña segura" required>
                    </div>
                </div>
                
                <div class="text-center">
                    <button type="submit" name="insert" class="btn-primary me-3">
                        <i class="fas fa-user-plus me-2"></i>Crear Usuario
                    </button>
                    <button type="reset" class="btn-secondary">
                        <i class="fas fa-redo me-2"></i>Limpiar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de usuarios existentes -->
        <div class="table-container">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-users me-2"></i>Usuarios del Sistema
                </h3>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $resultado = $conexion->query("SELECT * FROM login ORDER BY usuario");
                            if ($resultado->num_rows > 0) {
                                $id = 1;
                                while ($row = $resultado->fetch_assoc()) {
                                    echo "<tr>
                                            <td><span class='badge bg-primary'>{$id}</span></td>
                                            <td><strong>{$row['usuario']}</strong></td>
                                            <td><span class='badge' style='background: linear-gradient(135deg, #48bb78, #38a169); color: white;'>Activo</span></td>
                                          </tr>";
                                    $id++;
                                }
                            } else {
                                echo "<tr><td colspan='3' class='text-center text-muted py-4'>
                                        <i class='fas fa-users-slash fa-2x mb-2'></i><br>
                                        No hay usuarios registrados
                                      </td></tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='3' class='text-center text-danger'>Error al cargar datos: " . $e->getMessage() . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php
    if (isset($db)) {
        $db->closeConnection();
    }
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript para usuario.php
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            form.addEventListener('submit', function() {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creando...';
                submitBtn.disabled = true;
            });

            // Auto-hide alerts
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 4000);
        });
    </script>
</body>
</html>
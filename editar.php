<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mantenimiento - CECYTE</title>
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

if (!isset($_GET['id'])) {
    die("ID no proporcionado.");
}

$id = $_GET['id'];
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'mantenimiento';

if ($tipo == 'mantenimiento') {
    $consulta = "SELECT * FROM mantenimiento WHERE id = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $row = $resultado->fetch_assoc();
    
    if (!$row) {
        die("No se encontró el registro de mantenimiento.");
    }
} else {
    $consulta = "SELECT * FROM personal WHERE id = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $row = $resultado->fetch_assoc();
    
    if (!$row) {
        die("No se encontró el registro de personal.");
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
                        <a class="nav-link active" href="#"><i class="fas fa-edit me-1"></i>Editar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cerrar.php"><i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-container fade-in">
                    <?php if ($tipo == 'mantenimiento'): ?>
                    <h2 class="form-title">
                        <i class="fas fa-wrench me-3"></i>Editar Mantenimiento
                    </h2>
                    
                    <form action="procesar.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="tipo" value="mantenimiento">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="areaa" class="form-label">Área</label>
                                <input type="text" name="areaa" id="areaa" class="form-input" value="<?php echo htmlspecialchars($row['areaa']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="actividad" class="form-label">Actividad</label>
                                <input type="text" name="actividad" id="actividad" class="form-input" value="<?php echo htmlspecialchars($row['actividad']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="frecuencia" class="form-label">Frecuencia</label>
                                <input type="text" name="frecuencia" id="frecuencia" class="form-input" value="<?php echo htmlspecialchars($row['frecuencia']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="folio" class="form-label">Folio</label>
                                <input type="text" name="folio" id="folio" class="form-input" value="<?php echo htmlspecialchars($row['folio']); ?>">
                            </div>
                            
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <input type="text" name="observaciones" id="observaciones" class="form-input" value="<?php echo htmlspecialchars($row['observaciones']); ?>">
                            </div>
                            
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label for="material" class="form-label">Material</label>
                                <input type="text" name="material" id="material" class="form-input" value="<?php echo htmlspecialchars($row['material']); ?>">
                            </div>
                        </div>
                    <?php else: ?>
                    <h2 class="form-title">
                        <i class="fas fa-user-edit me-3"></i>Editar Personal
                    </h2>
                    
                    <form action="procesar.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="tipo" value="personal">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-input" value="<?php echo htmlspecialchars($row['nombre']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="cargo" class="form-label">Cargo</label>
                                <input type="text" name="cargo" id="cargo" class="form-input" value="<?php echo htmlspecialchars($row['cargo']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" name="telefono" id="telefono" class="form-input" value="<?php echo htmlspecialchars($row['telefono']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-input" value="<?php echo htmlspecialchars($row['email']); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    
                        <div class="text-center mt-4">
                            <button type="submit" class="btn-primary me-3">
                                <i class="fas fa-save me-2"></i>Actualizar Registro
                            </button>
                            <a href="inicio.php" class="btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Animación de entrada
            const container = document.querySelector('.fade-in');
            if (container) {
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }
            
            // Efectos en inputs
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('input-focused');
                    const label = this.previousElementSibling;
                    if (label && label.classList.contains('form-label')) {
                        label.style.transform = 'translateY(-2px) scale(0.95)';
                        label.style.color = '#667eea';
                    }
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('input-focused');
                    const label = this.previousElementSibling;
                    if (label && label.classList.contains('form-label')) {
                        label.style.transform = '';
                        label.style.color = '';
                    }
                });
            });
        });
    </script>

</body>
</html>

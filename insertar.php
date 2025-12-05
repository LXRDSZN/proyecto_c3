<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Registros - CECYTE</title>
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

    $mensaje_mantto = '';
    $mensaje_personal = '';
    $tipo_mensaje_mantto = '';
    $tipo_mensaje_personal = '';

    // Obtener tipo de operación
    $tipo_actual = isset($_GET['tipo']) ? $_GET['tipo'] : 'mantenimiento';

    try {
        $db = new Database();
        $conexion = $db->getConnection();

        // Si no hay tipo específico, mostrar selector
        if (!isset($_GET['tipo'])) {
            $mostrar_selector = true;
        } else {
            $mostrar_selector = false;
        }

        // Procesar inserción de mantenimiento
        if (isset($_POST['insert_mantto'])) {
            $ID = intval(trim($_POST['ID']));
            $AREA = trim($_POST['AREA']);
            $ACTIVIDAD = trim($_POST['ACTIVIDAD']);
            $FRECUENCIA = trim($_POST['FRECUENCIA']);
            $FOLIO = trim($_POST['FOLIO']);
            $OBSERVACIONES = trim($_POST['OBSERVACIONES']);
            $MATERIAL = trim($_POST['MATERIAL']);

            // Verificar si el ID ya existe
            $stmt = $conexion->prepare("SELECT id FROM mantenimiento WHERE id = ?");
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $mensaje_mantto = "El ID $ID ya existe en mantenimiento. Ingresa uno diferente.";
                $tipo_mensaje_mantto = 'danger';
            } else {
                // Insertar nuevo registro
                $stmt_insert = $conexion->prepare("INSERT INTO mantenimiento (id, areaa, actividad, frecuencia, folio, observaciones, material) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt_insert->bind_param("issssss", $ID, $AREA, $ACTIVIDAD, $FRECUENCIA, $FOLIO, $OBSERVACIONES, $MATERIAL);
                
                if ($stmt_insert->execute()) {
                    $mensaje_mantto = "Registro de mantenimiento insertado correctamente.";
                    $tipo_mensaje_mantto = 'success';
                    // No redirect, mostrar mensaje en la misma página
                } else {
                    $mensaje_mantto = "Error al insertar el registro: " . $stmt_insert->error;
                    $tipo_mensaje_mantto = 'danger';
                }
                $stmt_insert->close();
            }
            $stmt->close();
        }

        // Procesar inserción de personal
        if (isset($_POST['insert_personal'])) {
            $ID = intval(trim($_POST['ID']));
            $NOMBRE = trim($_POST['NOMBRE']);
            $CARGO = trim($_POST['CARGO']);
            $TELEFONO = trim($_POST['TELEFONO']);
            $EMAIL = trim($_POST['EMAIL']);

            // Verificar si el ID ya existe
            $stmt = $conexion->prepare("SELECT id FROM personal WHERE id = ?");
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $mensaje_personal = "El ID $ID ya existe en personal. Ingresa uno diferente.";
                $tipo_mensaje_personal = 'danger';
            } else {
                // Insertar nuevo registro
                $stmt_insert = $conexion->prepare("INSERT INTO personal (id, nombre, cargo, telefono, email) VALUES (?, ?, ?, ?, ?)");
                $stmt_insert->bind_param("issss", $ID, $NOMBRE, $CARGO, $TELEFONO, $EMAIL);
                
                if ($stmt_insert->execute()) {
                    $mensaje_personal = "Registro de personal insertado correctamente.";
                    $tipo_mensaje_personal = 'success';
                    // No redirect, mostrar mensaje en la misma página
                } else {
                    $mensaje_personal = "Error al insertar el registro: " . $stmt_insert->error;
                    $tipo_mensaje_personal = 'danger';
                }
                $stmt_insert->close();
            }
            $stmt->close();
        }

    } catch (Exception $e) {
        $mensaje_mantto = "Error del sistema: " . $e->getMessage();
        $tipo_mensaje_mantto = 'danger';
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
                        <a class="nav-link active" href="insertar.php"><i class="fas fa-plus me-1"></i>Insertar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="select.php"><i class="fas fa-list me-1"></i>Ver Registros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cerrar.php"><i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        
        <?php if ($mostrar_selector): ?>
        <!-- Selector de tipo -->
        <div class="type-selector mb-4">
            <h2 class="text-center mb-3">
                <i class="fas fa-plus me-2"></i>Agregar Nuevo Registro
            </h2>
            <div class="btn-group w-100" role="group">
                <a href="insertar.php?tipo=mantenimiento" class="btn btn-inactive">
                    <i class="fas fa-wrench me-1"></i>Agregar Mantenimiento
                </a>
                <a href="insertar.php?tipo=personal" class="btn btn-inactive">
                    <i class="fas fa-users me-1"></i>Agregar Personal
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($tipo_actual == 'mantenimiento' && !$mostrar_selector): ?>
        <!-- Sección Mantenimiento -->
        <div class="form-container fade-in">
            <h2 class="form-title">
                <i class="fas fa-wrench me-3"></i>Registro de Mantenimiento
            </h2>

            <?php if ($mensaje_mantto): ?>
                <div class="alert alert-<?php echo $tipo_mensaje_mantto === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                    <i class="fas fa-<?php echo $tipo_mensaje_mantto === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                    <?php echo $mensaje_mantto; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="insertar.php?tipo=mantenimiento" method="post">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="id_mantto" class="form-label">ID</label>
                        <input type="number" name="ID" id="id_mantto" class="form-input" placeholder="ID único" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="area" class="form-label">Área</label>
                        <input type="text" name="AREA" id="area" class="form-input" placeholder="Área de mantenimiento" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="actividad" class="form-label">Actividad</label>
                        <input type="text" name="ACTIVIDAD" id="actividad" class="form-input" placeholder="Actividad a realizar" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="frecuencia" class="form-label">Frecuencia</label>
                        <input type="text" name="FRECUENCIA" id="frecuencia" class="form-input" placeholder="Frecuencia de mantenimiento" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="folio" class="form-label">Folio</label>
                        <input type="text" name="FOLIO" id="folio" class="form-input" placeholder="Número de folio">
                    </div>
                    
                    <div class="form-group">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <input type="text" name="OBSERVACIONES" id="observaciones" class="form-input" placeholder="Observaciones adicionales">
                    </div>
                    
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="material" class="form-label">Materiales</label>
                        <input type="text" name="MATERIAL" id="material" class="form-input" placeholder="Materiales necesarios">
                    </div>
                </div>
                
                <div class="text-center">
                    <button type="submit" name="insert_mantto" class="btn-primary me-3">
                        <i class="fas fa-save me-2"></i>Guardar Registro
                    </button>
                    <button type="reset" class="btn-secondary">
                        <i class="fas fa-redo me-2"></i>Limpiar
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <?php if ($tipo_actual == 'personal' && !$mostrar_selector): ?>
        <!-- Sección Personal -->
        <div class="form-container fade-in">
            <h2 class="form-title">
                <i class="fas fa-users me-3"></i>Registro de Personal
            </h2>

            <?php if ($mensaje_personal): ?>
                <div class="alert alert-<?php echo $tipo_mensaje_personal === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                    <i class="fas fa-<?php echo $tipo_mensaje_personal === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                    <?php echo $mensaje_personal; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="insertar.php?tipo=personal" method="post">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="id_personal" class="form-label">ID</label>
                        <input type="number" name="ID" id="id_personal" class="form-input" placeholder="ID único" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="NOMBRE" id="nombre" class="form-input" placeholder="Nombre completo" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="cargo" class="form-label">Cargo</label>
                        <input type="text" name="CARGO" id="cargo" class="form-input" placeholder="Cargo o puesto" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" name="TELEFONO" id="telefono" class="form-input" placeholder="Número de teléfono">
                    </div>
                    
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="EMAIL" id="email" class="form-input" placeholder="correo@ejemplo.com">
                    </div>
                </div>
                
                <div class="text-center">
                    <button type="submit" name="insert_personal" class="btn-primary me-3">
                        <i class="fas fa-user-plus me-2"></i>Guardar Personal
                    </button>
                    <button type="reset" class="btn-secondary">
                        <i class="fas fa-redo me-2"></i>Limpiar
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <?php
    if (isset($db)) {
        $db->closeConnection();
    }
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript simplificado - sin efectos complejos que interfieren con el formulario
        document.addEventListener('DOMContentLoaded', function() {
            // Solo animación básica de entrada
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((el) => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            });

            // Auto-hide alerts después de 4 segundos
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
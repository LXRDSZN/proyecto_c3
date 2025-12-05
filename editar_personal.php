<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Personal - CECYTE</title>
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
    $registro = null;

    // Obtener registro para editar
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        try {
            $stmt = $conexion->prepare("SELECT * FROM personal WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            if ($resultado->num_rows > 0) {
                $registro = $resultado->fetch_assoc();
            } else {
                $mensaje = 'No se encontró el registro especificado.';
                $tipo_mensaje = 'error';
            }
            $stmt->close();
        } catch (Exception $e) {
            $mensaje = 'Error al obtener el registro: ' . $e->getMessage();
            $tipo_mensaje = 'error';
        }
    }

    // Procesar actualización
    if (isset($_POST['actualizar'])) {
        $id = intval($_POST['id']);
        $nombre = trim($_POST['nombre']);
        $cargo = trim($_POST['cargo']);
        $telefono = trim($_POST['telefono']);
        $email = trim($_POST['email']);

        if (!empty($nombre) && !empty($cargo)) {
            try {
                $stmt = $conexion->prepare("UPDATE personal SET nombre=?, cargo=?, telefono=?, email=? WHERE id=?");
                $stmt->bind_param("ssssi", $nombre, $cargo, $telefono, $email, $id);
                
                if ($stmt->execute()) {
                    $mensaje = 'Registro actualizado correctamente.';
                    $tipo_mensaje = 'success';
                    
                    // Recargar datos actualizados
                    $stmt2 = $conexion->prepare("SELECT * FROM personal WHERE id = ?");
                    $stmt2->bind_param("i", $id);
                    $stmt2->execute();
                    $resultado = $stmt2->get_result();
                    $registro = $resultado->fetch_assoc();
                    $stmt2->close();
                } else {
                    $mensaje = 'Error al actualizar el registro.';
                    $tipo_mensaje = 'error';
                }
                $stmt->close();
            } catch (Exception $e) {
                $mensaje = 'Error: ' . $e->getMessage();
                $tipo_mensaje = 'error';
            }
        } else {
            $mensaje = 'Por favor complete los campos obligatorios (Nombre y Cargo).';
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
                        <a class="nav-link" href="select.php?tipo=personal"><i class="fas fa-list me-1"></i>Ver Personal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="modificar.php?tipo=personal"><i class="fas fa-edit me-1"></i>Editar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cerrar.php"><i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                <i class="fas fa-<?php echo $tipo_mensaje == 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <?php if ($registro): ?>
        <div class="form-container">
            <h2 class="form-title">
                <i class="fas fa-user-edit me-3"></i>Editar Información de Personal
            </h2>
            
            <div class="info-banner">
                <div class="d-flex align-items-center">
                    <div class="avatar me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">
                        <?php echo strtoupper(substr($registro['nombre'], 0, 1)); ?>
                    </div>
                    <div>
                        <strong>Editando: <?php echo htmlspecialchars($registro['nombre']); ?></strong><br>
                        <small>ID: <?php echo $registro['id']; ?> | Cargo: <?php echo htmlspecialchars($registro['cargo']); ?></small>
                    </div>
                </div>
            </div>

            <form method="post" class="needs-validation" novalidate>
                <input type="hidden" name="id" value="<?php echo $registro['id']; ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre" class="form-label">
                            <i class="fas fa-user me-1"></i>Nombre Completo <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               class="form-input" 
                               value="<?php echo htmlspecialchars($registro['nombre']); ?>"
                               placeholder="Ej: Juan Carlos Pérez García"
                               required>
                        <div class="invalid-feedback">Por favor ingrese el nombre completo.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="cargo" class="form-label">
                            <i class="fas fa-briefcase me-1"></i>Cargo <span class="text-danger">*</span>
                        </label>
                        <select name="cargo" id="cargo" class="form-input" required>
                            <option value="">Seleccionar cargo...</option>
                            <option value="Técnico en Mantenimiento" <?php echo $registro['cargo'] == 'Técnico en Mantenimiento' ? 'selected' : ''; ?>>Técnico en Mantenimiento</option>
                            <option value="Supervisor de Mantenimiento" <?php echo $registro['cargo'] == 'Supervisor de Mantenimiento' ? 'selected' : ''; ?>>Supervisor de Mantenimiento</option>
                            <option value="Jefe de Mantenimiento" <?php echo $registro['cargo'] == 'Jefe de Mantenimiento' ? 'selected' : ''; ?>>Jefe de Mantenimiento</option>
                            <option value="Electricista" <?php echo $registro['cargo'] == 'Electricista' ? 'selected' : ''; ?>>Electricista</option>
                            <option value="Plomero" <?php echo $registro['cargo'] == 'Plomero' ? 'selected' : ''; ?>>Plomero</option>
                            <option value="Jardinero" <?php echo $registro['cargo'] == 'Jardinero' ? 'selected' : ''; ?>>Jardinero</option>
                            <option value="Conserje" <?php echo $registro['cargo'] == 'Conserje' ? 'selected' : ''; ?>>Conserje</option>
                            <option value="Personal de Limpieza" <?php echo $registro['cargo'] == 'Personal de Limpieza' ? 'selected' : ''; ?>>Personal de Limpieza</option>
                            <option value="Auxiliar de Mantenimiento" <?php echo $registro['cargo'] == 'Auxiliar de Mantenimiento' ? 'selected' : ''; ?>>Auxiliar de Mantenimiento</option>
                            <option value="Otro" <?php echo $registro['cargo'] == 'Otro' ? 'selected' : ''; ?>>Otro</option>
                        </select>
                        <div class="invalid-feedback">Por favor seleccione un cargo.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono" class="form-label">
                            <i class="fas fa-phone me-1"></i>Teléfono
                        </label>
                        <input type="tel" 
                               name="telefono" 
                               id="telefono" 
                               class="form-input" 
                               value="<?php echo htmlspecialchars($registro['telefono']); ?>"
                               placeholder="Ej: +52 (618) 123-4567"
                               pattern="[0-9+\-\s\(\)]+">
                        <div class="invalid-feedback">Por favor ingrese un teléfono válido.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>Correo Electrónico
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-input" 
                               value="<?php echo htmlspecialchars($registro['email']); ?>"
                               placeholder="Ej: juan.perez@cecyte.edu.mx">
                        <div class="invalid-feedback">Por favor ingrese un correo válido.</div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" name="actualizar" class="btn-primary me-3">
                        <i class="fas fa-save me-2"></i>Actualizar Información
                    </button>
                    <a href="select.php?tipo=personal" class="btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
        <?php else: ?>
        <div class="alert alert-warning text-center">
            <h4><i class="fas fa-exclamation-triangle me-2"></i>Registro no encontrado</h4>
            <p>El registro de personal que está intentando editar no existe o ha sido eliminado.</p>
            <a href="select.php?tipo=personal" class="btn-primary">
                <i class="fas fa-arrow-left me-1"></i>Volver a la lista
            </a>
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
        // Validación del formulario
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert:not(.alert-warning)');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);

            // Loading state en botón de submit
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            form.addEventListener('submit', function() {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...';
                submitBtn.disabled = true;
            });

            // Formateo automático de teléfono
            const telefonoInput = document.getElementById('telefono');
            telefonoInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 10) {
                    value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
                }
                e.target.value = value;
            });
        });
    </script>

    <style>
        .info-banner {
            background: linear-gradient(135deg, #f3e5f5, #e1bee7);
            border: 1px solid #9c27b0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            color: #4a148c;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .was-validated .form-input:valid {
            border-color: #28a745;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='m2.3 6.73.98-.97-.97-.98a.5.5 0 01.708-.708L3 5.02l1.972-1.97a.5.5 0 11.708.708l-2.32 2.32a.5.5 0 01-.708 0z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(.375em + .1875rem) center;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
        }

        .was-validated .form-input:invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 5.8 4.4 4.4M10.2 5.8l-4.4 4.4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(.375em + .1875rem) center;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: .25rem;
            font-size: .875rem;
            color: #dc3545;
        }

        .was-validated .form-input:invalid ~ .invalid-feedback {
            display: block;
        }
    </style>
</body>
</html>
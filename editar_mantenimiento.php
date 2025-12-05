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

    $mensaje = '';
    $tipo_mensaje = '';
    $registro = null;

    // Obtener registro para editar
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        try {
            $stmt = $conexion->prepare("SELECT * FROM mantenimiento WHERE id = ?");
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
        $areaa = trim($_POST['areaa']);
        $actividad = trim($_POST['actividad']);
        $frecuencia = trim($_POST['frecuencia']);
        $folio = trim($_POST['folio']);
        $observaciones = trim($_POST['observaciones']);
        $material = trim($_POST['material']);

        if (!empty($areaa) && !empty($actividad) && !empty($frecuencia)) {
            try {
                $stmt = $conexion->prepare("UPDATE mantenimiento SET areaa=?, actividad=?, frecuencia=?, folio=?, observaciones=?, material=? WHERE id=?");
                $stmt->bind_param("ssssssi", $areaa, $actividad, $frecuencia, $folio, $observaciones, $material, $id);
                
                if ($stmt->execute()) {
                    $mensaje = 'Registro actualizado correctamente.';
                    $tipo_mensaje = 'success';
                    
                    // Recargar datos actualizados
                    $stmt2 = $conexion->prepare("SELECT * FROM mantenimiento WHERE id = ?");
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
            $mensaje = 'Por favor complete los campos obligatorios (Área, Actividad, Frecuencia).';
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
                        <a class="nav-link" href="select.php?tipo=mantenimiento"><i class="fas fa-list me-1"></i>Ver Registros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="modificar.php?tipo=mantenimiento"><i class="fas fa-edit me-1"></i>Editar</a>
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
                <i class="fas fa-edit me-3"></i>Editar Registro de Mantenimiento
            </h2>
            
            <div class="info-banner">
                <i class="fas fa-info-circle me-2"></i>
                Modificando registro ID: <strong><?php echo $registro['id']; ?></strong>
            </div>

            <form method="post" class="needs-validation" novalidate>
                <input type="hidden" name="id" value="<?php echo $registro['id']; ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="areaa" class="form-label">
                            <i class="fas fa-building me-1"></i>Área <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="areaa" 
                               id="areaa" 
                               class="form-input" 
                               value="<?php echo htmlspecialchars($registro['areaa']); ?>"
                               placeholder="Ej: Laboratorio de Cómputo"
                               required>
                        <div class="invalid-feedback">Por favor ingrese el área.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="actividad" class="form-label">
                            <i class="fas fa-tasks me-1"></i>Actividad <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="actividad" 
                               id="actividad" 
                               class="form-input" 
                               value="<?php echo htmlspecialchars($registro['actividad']); ?>"
                               placeholder="Ej: Limpieza de equipos"
                               required>
                        <div class="invalid-feedback">Por favor ingrese la actividad.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="frecuencia" class="form-label">
                            <i class="fas fa-calendar-alt me-1"></i>Frecuencia <span class="text-danger">*</span>
                        </label>
                        <select name="frecuencia" id="frecuencia" class="form-input" required>
                            <option value="">Seleccionar frecuencia...</option>
                            <option value="Diario" <?php echo $registro['frecuencia'] == 'Diario' ? 'selected' : ''; ?>>Diario</option>
                            <option value="Semanal" <?php echo $registro['frecuencia'] == 'Semanal' ? 'selected' : ''; ?>>Semanal</option>
                            <option value="Quincenal" <?php echo $registro['frecuencia'] == 'Quincenal' ? 'selected' : ''; ?>>Quincenal</option>
                            <option value="Mensual" <?php echo $registro['frecuencia'] == 'Mensual' ? 'selected' : ''; ?>>Mensual</option>
                            <option value="Bimestral" <?php echo $registro['frecuencia'] == 'Bimestral' ? 'selected' : ''; ?>>Bimestral</option>
                            <option value="Trimestral" <?php echo $registro['frecuencia'] == 'Trimestral' ? 'selected' : ''; ?>>Trimestral</option>
                            <option value="Semestral" <?php echo $registro['frecuencia'] == 'Semestral' ? 'selected' : ''; ?>>Semestral</option>
                            <option value="Anual" <?php echo $registro['frecuencia'] == 'Anual' ? 'selected' : ''; ?>>Anual</option>
                        </select>
                        <div class="invalid-feedback">Por favor seleccione la frecuencia.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="folio" class="form-label">
                            <i class="fas fa-file-alt me-1"></i>Folio
                        </label>
                        <input type="text" 
                               name="folio" 
                               id="folio" 
                               class="form-input" 
                               value="<?php echo htmlspecialchars($registro['folio']); ?>"
                               placeholder="Ej: MNT-2024-001">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="observaciones" class="form-label">
                            <i class="fas fa-sticky-note me-1"></i>Observaciones
                        </label>
                        <textarea name="observaciones" 
                                  id="observaciones" 
                                  class="form-input" 
                                  rows="3"
                                  placeholder="Notas adicionales sobre el mantenimiento..."><?php echo htmlspecialchars($registro['observaciones']); ?></textarea>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="material" class="form-label">
                            <i class="fas fa-tools me-1"></i>Material y Herramientas
                        </label>
                        <textarea name="material" 
                                  id="material" 
                                  class="form-input" 
                                  rows="3"
                                  placeholder="Lista de materiales y herramientas necesarias..."><?php echo htmlspecialchars($registro['material']); ?></textarea>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" name="actualizar" class="btn-primary me-3">
                        <i class="fas fa-save me-2"></i>Actualizar Registro
                    </button>
                    <a href="select.php?tipo=mantenimiento" class="btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
        <?php else: ?>
        <div class="alert alert-warning text-center">
            <h4><i class="fas fa-exclamation-triangle me-2"></i>Registro no encontrado</h4>
            <p>El registro que está intentando editar no existe o ha sido eliminado.</p>
            <a href="select.php?tipo=mantenimiento" class="btn-primary">
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
        });
    </script>

    <style>
        .info-banner {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border: 1px solid #2196f3;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 2rem;
            color: #1565c0;
            font-weight: 500;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .full-width {
            grid-column: 1 / -1;
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
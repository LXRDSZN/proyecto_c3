<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Registros - CECYTE</title>
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

    // Procesar eliminación mediante AJAX o POST directo
    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
        $id = intval($_POST['id']);
        $tipo = $_POST['tipo'];
        
        try {
            if ($tipo === 'mantenimiento') {
                $stmt = $conexion->prepare("DELETE FROM mantenimiento WHERE id = ?");
            } else {
                $stmt = $conexion->prepare("DELETE FROM personal WHERE id = ?");
            }
            
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $_SESSION['success_message'] = 'Registro eliminado correctamente.';
                    header("Location: inicio.php");
                    exit();
                } else {
                    $mensaje = 'No se encontró el registro especificado.';
                    $tipo_mensaje = 'error';
                }
            } else {
                $mensaje = 'Error al eliminar el registro.';
                $tipo_mensaje = 'error';
            }
            $stmt->close();
        } catch (Exception $e) {
            $mensaje = 'Error: ' . $e->getMessage();
            $tipo_mensaje = 'error';
        }
    }

    // Procesar eliminación de mantenimiento
    if (isset($_POST['eliminar_mantto'])) {
        $id = intval($_POST['id_mantto']);
        
        try {
            $stmt = $conexion->prepare("DELETE FROM mantenimiento WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $mensaje = 'Registro de mantenimiento eliminado correctamente.';
                    $tipo_mensaje = 'success';
                } else {
                    $mensaje = 'No se encontró el registro especificado.';
                    $tipo_mensaje = 'error';
                }
            } else {
                $mensaje = 'Error al eliminar el registro.';
                $tipo_mensaje = 'error';
            }
            $stmt->close();
        } catch (Exception $e) {
            $mensaje = 'Error: ' . $e->getMessage();
            $tipo_mensaje = 'error';
        }
    }

    // Procesar eliminación de personal
    if (isset($_POST['eliminar_personal'])) {
        $id = intval($_POST['id_personal']);
        
        try {
            $stmt = $conexion->prepare("DELETE FROM personal WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $mensaje = 'Registro de personal eliminado correctamente.';
                    $tipo_mensaje = 'success';
                } else {
                    $mensaje = 'No se encontró el registro especificado.';
                    $tipo_mensaje = 'error';
                }
            } else {
                $mensaje = 'Error al eliminar el registro.';
                $tipo_mensaje = 'error';
            }
            $stmt->close();
        } catch (Exception $e) {
            $mensaje = 'Error: ' . $e->getMessage();
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
                        <a class="nav-link" href="insertar.php"><i class="fas fa-plus me-1"></i>Insertar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="select.php"><i class="fas fa-edit me-1"></i>Modificar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="select.php"><i class="fas fa-list me-1"></i>Ver Registros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="eliminar.php"><i class="fas fa-trash-alt me-1"></i>Eliminar</a>
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
            <div class="alert alert-<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <!-- Eliminar Registros de Mantenimiento -->
        <div class="table-container">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-wrench me-2"></i>Eliminar Registros de Mantenimiento
                </h3>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Área</th>
                            <th>Actividad</th>
                            <th>Frecuencia</th>
                            <th>Folio</th>
                            <th>Observaciones</th>
                            <th>Materiales</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $resultado = $conexion->query("SELECT * FROM mantenimiento ORDER BY id");
                            if ($resultado->num_rows > 0) {
                                while ($row = $resultado->fetch_assoc()) {
                                    echo "<tr>
                                            <td><span class='badge bg-primary'>{$row['id']}</span></td>
                                            <td><strong>{$row['areaa']}</strong></td>
                                            <td>{$row['actividad']}</td>
                                            <td><span class='badge' style='background: linear-gradient(135deg, #48bb78, #38a169); color: white;'>{$row['frecuencia']}</span></td>
                                            <td>{$row['folio']}</td>
                                            <td>{$row['observaciones']}</td>
                                            <td>{$row['material']}</td>
                                            <td>
                                                <form method='post' style='display: inline-block;' onsubmit='return confirmarEliminacion(\"mantenimiento\", \"{$row['areaa']}\")'>
                                                    <input type='hidden' name='id_mantto' value='{$row['id']}'>
                                                    <button type='submit' name='eliminar_mantto' class='btn btn-sm' style='background: linear-gradient(135deg, #e53e3e, #c53030); color: white; border: none; border-radius: 8px; padding: 0.4rem 0.8rem;'>
                                                        <i class='fas fa-trash'></i> Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center text-muted py-4'>
                                        <i class='fas fa-inbox fa-2x mb-2'></i><br>
                                        No hay registros de mantenimiento
                                      </td></tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='8' class='text-center text-danger'>Error al cargar datos: " . $e->getMessage() . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Eliminar Registros de Personal -->
        <div class="table-container">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-users me-2"></i>Eliminar Registros de Personal
                </h3>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $resultado = $conexion->query("SELECT * FROM personal ORDER BY id");
                            if ($resultado->num_rows > 0) {
                                while ($row = $resultado->fetch_assoc()) {
                                    echo "<tr>
                                            <td><span class='badge bg-info'>{$row['id']}</span></td>
                                            <td>
                                                <div class='d-flex align-items-center'>
                                                    <div class='avatar me-2' style='width: 40px; height: 40px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;'>
                                                        " . strtoupper(substr($row['nombre'], 0, 1)) . "
                                                    </div>
                                                    <strong>{$row['nombre']}</strong>
                                                </div>
                                            </td>
                                            <td><span class='badge' style='background: linear-gradient(135deg, #ed8936, #dd6b20); color: white;'>{$row['cargo']}</span></td>
                                            <td>{$row['telefono']}</td>
                                            <td>{$row['email']}</td>
                                            <td>
                                                <form method='post' style='display: inline-block;' onsubmit='return confirmarEliminacion(\"personal\", \"{$row['nombre']}\")'>
                                                    <input type='hidden' name='id_personal' value='{$row['id']}'>
                                                    <button type='submit' name='eliminar_personal' class='btn btn-sm' style='background: linear-gradient(135deg, #e53e3e, #c53030); color: white; border: none; border-radius: 8px; padding: 0.4rem 0.8rem;'>
                                                        <i class='fas fa-user-times'></i> Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted py-4'>
                                        <i class='fas fa-user-slash fa-2x mb-2'></i><br>
                                        No hay personal registrado
                                      </td></tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='6' class='text-center text-danger'>Error al cargar datos: " . $e->getMessage() . "</td></tr>";
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
        function confirmarEliminacion(tipo, nombre) {
            const mensaje = `¿Estás seguro de que quieres eliminar este registro de ${tipo}?\n\nElemento: ${nombre}\n\nEsta acción no se puede deshacer.`;
            return confirm(mensaje);
        }

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 4000);

            // Animación para filas
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                setTimeout(() => {
                    row.style.opacity = '1';
                }, index * 50);
            });
        });
    </script>
</body>
</html>
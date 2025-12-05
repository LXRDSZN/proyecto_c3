<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Personal - CECYTE</title>
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
                        <a class="nav-link active" href="reportesp.php"><i class="fas fa-users me-1"></i>Reportes Personal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cerrar.php"><i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Encabezado del reporte -->
        <div class="report-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="report-title">
                        <i class="fas fa-users me-3"></i>Reporte de Personal
                    </h1>
                    <p class="report-subtitle">
                        Generado el <?php echo date('d/m/Y H:i:s'); ?>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="reporte_personal_pdf.php" class="btn btn-danger" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas del reporte -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h4>
                            <?php
                            $result = $conexion->query("SELECT COUNT(*) as count FROM personal");
                            $total = $result->fetch_assoc()['count'];
                            echo $total;
                            ?>
                        </h4>
                        <p>Total de Personal</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="stat-content">
                        <h4>
                            <?php
                            $result = $conexion->query("SELECT COUNT(DISTINCT cargo) as count FROM personal");
                            $cargos = $result->fetch_assoc()['count'];
                            echo $cargos;
                            ?>
                        </h4>
                        <p>Tipos de Cargo</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="stat-content">
                        <h4>
                            <?php
                            $result = $conexion->query("SELECT COUNT(*) as count FROM personal WHERE telefono != '' AND telefono IS NOT NULL");
                            $conTelefono = $result->fetch_assoc()['count'];
                            echo $conTelefono;
                            ?>
                        </h4>
                        <p>Con Teléfono</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-info">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-content">
                        <h4>
                            <?php
                            $result = $conexion->query("SELECT COUNT(*) as count FROM personal WHERE email != '' AND email IS NOT NULL");
                            $conEmail = $result->fetch_assoc()['count'];
                            echo $conEmail;
                            ?>
                        </h4>
                        <p>Con Email</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla completa de personal -->
        <div class="table-container">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-address-book me-2"></i>Listado Completo de Personal
                </h3>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $resultado = $conexion->query("SELECT * FROM personal ORDER BY id ASC");
                            if ($resultado->num_rows > 0) {
                                while ($row = $resultado->fetch_assoc()) {
                                    echo "<tr>
                                            <td><span class='badge bg-info'>{$row['id']}</span></td>
                                            <td>
                                                <div class='d-flex align-items-center'>
                                                    <div class='avatar me-2'>
                                                        " . strtoupper(substr($row['nombre'], 0, 1)) . "
                                                    </div>
                                                    <strong>{$row['nombre']}</strong>
                                                </div>
                                            </td>
                                            <td><span class='badge bg-success'>{$row['cargo']}</span></td>
                                            <td>
                                                <a href='tel:{$row['telefono']}' class='text-decoration-none'>
                                                    <i class='fas fa-phone me-1'></i>{$row['telefono']}
                                                </a>
                                            </td>
                                            <td>
                                                <a href='mailto:{$row['email']}' class='text-decoration-none'>
                                                    <i class='fas fa-envelope me-1'></i>{$row['email']}
                                                </a>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center text-muted py-4'>
                                        <i class='fas fa-user-slash fa-2x mb-2'></i><br>
                                        No hay personal registrado
                                      </td></tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='5' class='text-center text-danger'>Error al cargar datos: " . $e->getMessage() . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Resumen por cargos -->
        <div class="table-container mt-4">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-chart-pie me-2"></i>Resumen por Cargos
                </h3>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cargo</th>
                            <th>Cantidad</th>
                            <th>Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $resultado = $conexion->query("SELECT cargo, COUNT(*) as cantidad FROM personal GROUP BY cargo ORDER BY cantidad DESC");
                            $total_personal = $total; // Usar el total calculado anteriormente
                            
                            if ($resultado->num_rows > 0) {
                                while ($row = $resultado->fetch_assoc()) {
                                    $porcentaje = $total_personal > 0 ? round(($row['cantidad'] / $total_personal) * 100, 1) : 0;
                                    echo "<tr>
                                            <td><strong>{$row['cargo']}</strong></td>
                                            <td><span class='badge bg-primary'>{$row['cantidad']}</span></td>
                                            <td>
                                                <div class='progress' style='height: 20px;'>
                                                    <div class='progress-bar' style='width: {$porcentaje}%'>{$porcentaje}%</div>
                                                </div>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3' class='text-center text-muted py-4'>No hay datos para mostrar</td></tr>";
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

    <style>
        .report-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .report-title {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .report-subtitle {
            opacity: 0.9;
            margin: 0;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .stat-content h4 {
            color: #2d3748;
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.2rem;
        }

        .stat-content p {
            color: #718096;
            margin: 0;
            font-weight: 500;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .table-header {
            background: linear-gradient(135deg, #f8faff, #e8f1ff);
            padding: 1.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .table-title {
            color: #2d3748;
            font-weight: 600;
            margin: 0;
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
        }

        .badge {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .progress {
            background-color: #e2e8f0;
        }

        .progress-bar {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-weight: 600;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CECYTE</title>
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
                        <a class="nav-link active" href="inicio.php"><i class="fas fa-home me-1"></i>Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="usuario.php"><i class="fas fa-user-cog me-1"></i>Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cerrar.php"><i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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
                echo '<div class="alert alert-success mt-3">' . $_SESSION['success_message'] . '</div>';
                unset($_SESSION['success_message']);
            }
            ?>
        </div>
    </div>

    <div class="container-fluid px-4 mt-4">
        <!-- Barra de búsqueda global -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="search-container">
                    <div class="search-header">
                        <h4><i class="fas fa-search me-2"></i>Búsqueda Rápida</h4>
                    </div>
                    <div class="search-body">
                        <form id="searchForm" class="search-form">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label">Buscar en:</label>
                                    <select class="form-select" id="searchType">
                                        <option value="mantenimiento">🔧 Mantenimiento</option>
                                        <option value="personal">👥 Personal</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Término de búsqueda:</label>
                                    <input type="text" class="form-control" id="searchTerm" placeholder="Ingresa lo que deseas buscar...">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-search w-100">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="row mb-5">
            <!-- Card Mantenimiento -->
            <div class="col-lg-6 mb-4">
                <div class="module-card maintenance-card">
                    <div class="module-header">
                        <div class="module-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="module-info">
                            <h3>Mantenimiento</h3>
                            <p>Gestión completa de actividades de mantenimiento</p>
                            <div class="stats-badge">
                                <?php
                                try {
                                    $result = $conexion->query("SELECT COUNT(*) as count FROM mantenimiento");
                                    $count = $result->fetch_assoc()['count'];
                                    echo $count . " registros";
                                } catch (Exception $e) {
                                    echo "0 registros";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="module-actions">
                        <div class="action-grid">
                            <a href="insertar.php?tipo=mantenimiento" class="action-btn add-btn">
                                <i class="fas fa-plus"></i>
                                <span>Agregar</span>
                            </a>
                            <a href="select.php?tipo=mantenimiento" class="action-btn view-btn">
                                <i class="fas fa-list"></i>
                                <span>Ver Todo</span>
                            </a>
                            <a href="modificar.php?tipo=mantenimiento" class="action-btn edit-btn">
                                <i class="fas fa-edit"></i>
                                <span>Editar</span>
                            </a>
                            <a href="reportes.php" class="action-btn report-btn">
                                <i class="fas fa-file-pdf"></i>
                                <span>Reportes</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Personal -->
            <div class="col-lg-6 mb-4">
                <div class="module-card personal-card">
                    <div class="module-header">
                        <div class="module-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="module-info">
                            <h3>Personal</h3>
                            <p>Administración del personal especializado</p>
                            <div class="stats-badge">
                                <?php
                                try {
                                    $result = $conexion->query("SELECT COUNT(*) as count FROM personal");
                                    $count = $result->fetch_assoc()['count'];
                                    echo $count . " empleados";
                                } catch (Exception $e) {
                                    echo "0 empleados";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="module-actions">
                        <div class="action-grid">
                            <a href="insertar.php?tipo=personal" class="action-btn add-btn">
                                <i class="fas fa-user-plus"></i>
                                <span>Agregar</span>
                            </a>
                            <a href="select.php?tipo=personal" class="action-btn view-btn">
                                <i class="fas fa-address-book"></i>
                                <span>Ver Todo</span>
                            </a>
                            <a href="modificar.php?tipo=personal" class="action-btn edit-btn">
                                <i class="fas fa-user-edit"></i>
                                <span>Editar</span>
                            </a>
                            <a href="reportesp.php" class="action-btn report-btn">
                                <i class="fas fa-file-contract"></i>
                                <span>Reportes</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-content">
                        <h4>
                            <?php
                            try {
                                $result = $conexion->query("SELECT COUNT(*) as count FROM mantenimiento WHERE frecuencia LIKE '%diario%' OR frecuencia LIKE '%semanal%'");
                                $count = $result->fetch_assoc()['count'];
                                echo $count;
                            } catch (Exception $e) {
                                echo "0";
                            }
                            ?>
                        </h4>
                        <p>Tareas Programadas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card stat-success">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h4>100%</h4>
                        <p>Sistema Operativo</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card stat-info">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h4><?php echo date('Y'); ?></h4>
                        <p>Año Activo</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Administrativas -->
        <div class="row">
            <div class="col-12">
                <div class="admin-section">
                    <h5><i class="fas fa-cogs me-2"></i>Herramientas Administrativas</h5>
                    <div class="admin-actions">
                        <a href="eliminar.php" class="admin-btn delete-btn">
                            <i class="fas fa-trash-alt"></i>
                            <span>Eliminar Registros</span>
                        </a>
                        <a href="usuario.php" class="admin-btn user-btn">
                            <i class="fas fa-user-cog"></i>
                            <span>Gestión de Usuarios</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .search-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.1);
            overflow: hidden;
            border: 2px solid #f1f5f9;
        }

        .search-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 1rem 1.5rem;
        }

        .search-header h4 {
            margin: 0;
            font-weight: 600;
        }

        .search-body {
            padding: 1.5rem;
        }

        .btn-search {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .module-card {
            background: white;
            border-radius: 20px;
            padding: 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .maintenance-card:hover {
            border-color: #667eea;
        }

        .personal-card:hover {
            border-color: #764ba2;
        }

        .module-header {
            background: linear-gradient(135deg, #f8faff, #e8f1ff);
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .module-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            flex-shrink: 0;
        }

        .module-info h3 {
            color: #2d3748;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .module-info p {
            color: #718096;
            margin-bottom: 1rem;
        }

        .stats-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
        }

        .module-actions {
            padding: 1.5rem 2rem 2rem;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .action-btn {
            background: #f8faff;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem;
            text-decoration: none;
            color: #4a5568;
            text-align: center;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            color: white;
            transform: translateY(-2px);
        }

        .add-btn:hover {
            background: linear-gradient(135deg, #48bb78, #38a169);
            border-color: #38a169;
        }

        .view-btn:hover {
            background: linear-gradient(135deg, #4299e1, #3182ce);
            border-color: #3182ce;
        }

        .edit-btn:hover {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
            border-color: #dd6b20;
        }

        .report-btn:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: #764ba2;
        }

        .action-btn i {
            font-size: 1.5rem;
        }

        .action-btn span {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
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

        .stat-primary .stat-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .stat-success .stat-icon {
            background: linear-gradient(135deg, #48bb78, #38a169);
        }

        .stat-info .stat-icon {
            background: linear-gradient(135deg, #4299e1, #3182ce);
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

        .admin-section {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .admin-section h5 {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .admin-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .admin-btn {
            background: #f8faff;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            text-decoration: none;
            color: #4a5568;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            border-color: #e53e3e;
            color: white;
        }

        .user-btn:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: #764ba2;
            color: white;
        }

        @media (max-width: 768px) {
            .action-grid {
                grid-template-columns: 1fr;
            }
            
            .admin-actions {
                flex-direction: column;
            }
        }
    </style>

    <?php
    if (isset($db)) {
        $db->closeConnection();
    }
    ?>

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
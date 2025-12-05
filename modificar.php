<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Registros - CECYTE</title>
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

    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'mantenimiento';
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-edit me-1"></i>Modificar
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="modificar.php?tipo=mantenimiento">
                                <i class="fas fa-wrench me-1"></i>Mantenimiento
                            </a></li>
                            <li><a class="dropdown-item" href="modificar.php?tipo=personal">
                                <i class="fas fa-users me-1"></i>Personal
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cerrar.php"><i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Selector de tipo -->
        <div class="type-selector mb-4">
            <h2 class="text-center mb-3">
                <i class="fas fa-edit me-2"></i>Modificar Registros
            </h2>
            <div class="btn-group w-100" role="group">
                <a href="modificar.php?tipo=mantenimiento" class="btn <?php echo $tipo == 'mantenimiento' ? 'btn-active' : 'btn-inactive'; ?>">
                    <i class="fas fa-wrench me-1"></i>Mantenimiento
                </a>
                <a href="modificar.php?tipo=personal" class="btn <?php echo $tipo == 'personal' ? 'btn-active' : 'btn-inactive'; ?>">
                    <i class="fas fa-users me-1"></i>Personal
                </a>
            </div>
        </div>

        <?php if ($tipo == 'mantenimiento'): ?>
        <!-- Tabla de Mantenimiento -->
        <div class="table-container">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-wrench me-2"></i>Registros de Mantenimiento
                </h3>
                <p class="table-subtitle">Selecciona un registro para editarlo</p>
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
                            <th>Material</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $resultado = $conexion->query("SELECT * FROM mantenimiento ORDER BY id DESC");
                            if ($resultado->num_rows > 0) {
                                while ($row = $resultado->fetch_assoc()) {
                                    $observaciones = strlen($row['observaciones']) > 50 ? substr($row['observaciones'], 0, 50) . '...' : $row['observaciones'];
                                    $material = strlen($row['material']) > 40 ? substr($row['material'], 0, 40) . '...' : $row['material'];
                                    
                                    echo "<tr class='table-row'>
                                            <td><span class='badge bg-primary'>{$row['id']}</span></td>
                                            <td><strong>{$row['areaa']}</strong></td>
                                            <td>{$row['actividad']}</td>
                                            <td><span class='frequency-badge'>{$row['frecuencia']}</span></td>
                                            <td>{$row['folio']}</td>
                                            <td><span class='text-muted'>{$observaciones}</span></td>
                                            <td><span class='text-muted'>{$material}</span></td>
                                            <td>
                                                <a href='editar_mantenimiento.php?id={$row['id']}' class='btn btn-sm edit-btn' title='Editar registro'>
                                                    <i class='fas fa-edit'></i> Editar
                                                </a>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center text-muted py-5'>
                                        <i class='fas fa-inbox fa-3x mb-3'></i><br>
                                        <h5>No hay registros de mantenimiento</h5>
                                        <p>Comienza agregando un nuevo registro</p>
                                        <a href='insertar.php?tipo=mantenimiento' class='btn btn-primary'>
                                            <i class='fas fa-plus me-1'></i>Agregar Registro
                                        </a>
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

        <?php else: ?>
        <!-- Tabla de Personal -->
        <div class="table-container">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-users me-2"></i>Registros de Personal
                </h3>
                <p class="table-subtitle">Selecciona un empleado para editar su información</p>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Empleado</th>
                            <th>Cargo</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $resultado = $conexion->query("SELECT * FROM personal ORDER BY nombre");
                            if ($resultado->num_rows > 0) {
                                while ($row = $resultado->fetch_assoc()) {
                                    $inicial = strtoupper(substr($row['nombre'], 0, 1));
                                    
                                    echo "<tr class='table-row'>
                                            <td><span class='badge bg-info'>{$row['id']}</span></td>
                                            <td>
                                                <div class='d-flex align-items-center'>
                                                    <div class='employee-avatar me-2'>
                                                        {$inicial}
                                                    </div>
                                                    <div>
                                                        <strong>{$row['nombre']}</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class='cargo-badge'>{$row['cargo']}</span></td>
                                            <td>{$row['telefono']}</td>
                                            <td>{$row['email']}</td>
                                            <td>
                                                <a href='editar_personal.php?id={$row['id']}' class='btn btn-sm edit-btn' title='Editar información'>
                                                    <i class='fas fa-user-edit'></i> Editar
                                                </a>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted py-5'>
                                        <i class='fas fa-user-slash fa-3x mb-3'></i><br>
                                        <h5>No hay personal registrado</h5>
                                        <p>Comienza agregando un nuevo empleado</p>
                                        <a href='insertar.php?tipo=personal' class='btn btn-primary'>
                                            <i class='fas fa-user-plus me-1'></i>Agregar Personal
                                        </a>
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
        <?php endif; ?>

        <!-- Información adicional -->
        <div class="info-card mt-4">
            <div class="info-header">
                <i class="fas fa-info-circle me-2"></i>Información de Uso
            </div>
            <div class="info-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-mouse-pointer me-1"></i>Cómo usar:</h6>
                        <ul class="info-list">
                            <li>Selecciona el tipo de registro (Mantenimiento o Personal)</li>
                            <li>Haz clic en "Editar" en el registro que desees modificar</li>
                            <li>Completa el formulario y guarda los cambios</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-shield-alt me-1"></i>Seguridad:</h6>
                        <ul class="info-list">
                            <li>Todos los cambios quedan registrados</li>
                            <li>Solo usuarios autenticados pueden editar</li>
                            <li>Los datos son validados antes de guardar</li>
                        </ul>
                    </div>
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
            // Animación para las filas
            const rows = document.querySelectorAll('.table-row');
            rows.forEach((row, index) => {
                setTimeout(() => {
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Hover effects para botones de editar
            const editBtns = document.querySelectorAll('.edit-btn');
            editBtns.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                });
                
                btn.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>

    <style>
        .type-selector {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.1);
        }

        .btn-active {
            background: linear-gradient(135deg, #667eea, #764ba2) !important;
            color: white !important;
            border: 2px solid #667eea !important;
            font-weight: 600;
        }

        .btn-inactive {
            background: white !important;
            color: #667eea !important;
            border: 2px solid #e2e8f0 !important;
            font-weight: 500;
        }

        .btn-inactive:hover {
            background: #f8faff !important;
            border-color: #667eea !important;
        }

        .table-subtitle {
            color: #718096;
            font-size: 0.95rem;
            margin: 0;
        }

        .table-row {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .frequency-badge {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .cargo-badge {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .employee-avatar {
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

        .edit-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .edit-btn:hover {
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .info-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .info-header {
            background: linear-gradient(135deg, #f8faff, #e8f1ff);
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: #2d3748;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-body {
            padding: 1.5rem;
        }

        .info-list {
            list-style: none;
            padding: 0;
        }

        .info-list li {
            padding: 0.3rem 0;
            color: #4a5568;
        }

        .info-list li:before {
            content: "✓";
            color: #48bb78;
            margin-right: 0.5rem;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .type-selector .btn-group {
                flex-direction: column;
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }
        }
    </style>
</body>
</html>
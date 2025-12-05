<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Registros - CECYTE</title>
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
                        <a class="nav-link" href="insertar.php"><i class="fas fa-plus me-1"></i>Insertar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="modificar.php"><i class="fas fa-edit me-1"></i>Modificar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="select.php"><i class="fas fa-list me-1"></i>Ver Registros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cerrar.php"><i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Estadísticas Generales -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="nav-card text-center">
                    <div class="nav-card-icon mx-auto">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3 class="nav-card-title">
                        <?php
                        $result = $conexion->query("SELECT COUNT(*) as count FROM mantenimiento");
                        $count = $result->fetch_assoc()['count'];
                        echo $count;
                        ?>
                    </h3>
                    <p class="nav-card-desc">Registros de Mantenimiento</p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="nav-card text-center">
                    <div class="nav-card-icon mx-auto">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="nav-card-title">
                        <?php
                        $result = $conexion->query("SELECT COUNT(*) as count FROM personal");
                        $count = $result->fetch_assoc()['count'];
                        echo $count;
                        ?>
                    </h3>
                    <p class="nav-card-desc">Personal Registrado</p>
                </div>
            </div>
        </div>

        <!-- Tabla de Mantenimiento -->
        <div class="table-container fade-in">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-wrench me-2"></i>Registros de Mantenimiento
                </h3>
                <div>
                    <a href="reportes.php" class="btn-primary">
                        <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                    </a>
                </div>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $resultado = $conexion->query("SELECT * FROM mantenimiento ORDER BY id DESC");
                            if ($resultado->num_rows > 0) {
                                while ($row = $resultado->fetch_assoc()) {
                                    echo "<tr class='table-row-animated'>
                                            <td><span class='badge bg-primary'>{$row['id']}</span></td>
                                            <td><strong>{$row['areaa']}</strong></td>
                                            <td>{$row['actividad']}</td>
                                            <td><span class='badge' style='background: linear-gradient(135deg, #48bb78, #38a169); color: white;'>{$row['frecuencia']}</span></td>
                                            <td>{$row['folio']}</td>
                                            <td>{$row['observaciones']}</td>
                                            <td>{$row['material']}</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center text-muted py-4'>
                                        <i class='fas fa-inbox fa-2x mb-2'></i><br>
                                        No hay registros de mantenimiento
                                      </td></tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='7' class='text-center text-danger'>Error al cargar datos: " . $e->getMessage() . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabla de Personal -->
        <div class="table-container fade-in">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-users me-2"></i>Registros de Personal
                </h3>
                <div>
                    <a href="reportesp.php" class="btn-primary">
                        <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                    </a>
                </div>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $resultado = $conexion->query("SELECT * FROM personal ORDER BY id DESC");
                            if ($resultado->num_rows > 0) {
                                while ($row = $resultado->fetch_assoc()) {
                                    echo "<tr class='table-row-animated'>
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
    </div>

    <?php
    if (isset($db)) {
        $db->closeConnection();
    }
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/enhanced-effects.js"></script>
    <script>
        // Animaciones específicas para la página de visualización
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar efectos de partículas
            createViewPageEffects();
            
            // Animación de entrada para elementos
            animateViewElements();
            
            // Efectos especiales en tablas
            enhanceTableView();
            
            // Contador animado para estadísticas
            animateStatistics();
            
            // Efectos de búsqueda y filtrado
            setupTableSearch();
        });

        function createViewPageEffects() {
            const particleCount = 8;
            const container = document.querySelector('.dashboard-container');

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.style.cssText = `
                    position: absolute;
                    width: ${Math.random() * 6 + 3}px;
                    height: ${Math.random() * 6 + 3}px;
                    background: rgba(102, 126, 234, ${Math.random() * 0.2 + 0.05});
                    border-radius: 50%;
                    left: ${Math.random() * 100}%;
                    top: ${Math.random() * 100}%;
                    animation: viewPageFloat ${Math.random() * 25 + 15}s infinite ease-in-out;
                    pointer-events: none;
                    z-index: 1;
                `;
                container.appendChild(particle);
            }

            // Añadir estilos específicos
            addViewPageStyles();
        }

        function addViewPageStyles() {
            if (!document.getElementById('view-page-styles')) {
                const style = document.createElement('style');
                style.id = 'view-page-styles';
                style.textContent = `
                    @keyframes viewPageFloat {
                        0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
                        25% { transform: translateY(-30px) rotate(90deg) scale(1.2); }
                        50% { transform: translateY(15px) rotate(180deg) scale(0.8); }
                        75% { transform: translateY(-15px) rotate(270deg) scale(1.1); }
                    }
                    
                    .table-row-animated {
                        opacity: 0;
                        transform: translateX(-30px);
                        animation: slideInRow 0.6s ease-out forwards;
                    }
                    
                    @keyframes slideInRow {
                        to {
                            opacity: 1;
                            transform: translateX(0);
                        }
                    }
                    
                    .avatar {
                        transition: all 0.3s ease;
                    }
                    
                    .avatar:hover {
                        transform: scale(1.2) rotate(360deg);
                    }
                    
                    .search-box {
                        position: relative;
                        margin-bottom: 1rem;
                    }
                    
                    .search-input {
                        width: 100%;
                        padding: 0.8rem 1rem 0.8rem 3rem;
                        border: 2px solid #e2e8f0;
                        border-radius: 12px;
                        font-size: 1rem;
                        transition: all 0.3s ease;
                    }
                    
                    .search-input:focus {
                        outline: none;
                        border-color: #667eea;
                        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
                    }
                    
                    .search-icon {
                        position: absolute;
                        left: 1rem;
                        top: 50%;
                        transform: translateY(-50%);
                        color: #a0aec0;
                    }
                `;
                document.head.appendChild(style);
            }
        }

        function animateViewElements() {
            const elements = document.querySelectorAll('.fade-in, .nav-card');
            elements.forEach((el, index) => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0) scale(1)';
                setTimeout(() => {
                    el.style.transition = 'all 0.3s ease';
                }, index * 100);
            });
        }

        function enhanceTableView() {
            const rows = document.querySelectorAll('.table-row-animated');
            rows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.1}s`;
                
                // Efecto hover mejorado
                row.addEventListener('mouseenter', function() {
                    this.style.background = 'linear-gradient(135deg, rgba(102, 126, 234, 0.08), rgba(118, 75, 162, 0.08))';
                    this.style.transform = 'translateX(10px) scale(1.01)';
                    this.style.boxShadow = '0 8px 25px rgba(102, 126, 234, 0.15)';
                    this.style.zIndex = '10';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.background = '';
                    this.style.transform = '';
                    this.style.boxShadow = '';
                    this.style.zIndex = '';
                });
                
                // Efecto de click
                row.addEventListener('click', function() {
                    window.manttoEffects?.pulse(this, 1.05, 200);
                });
            });
        }

        function animateStatistics() {
            const statNumbers = document.querySelectorAll('.nav-card-title');
            statNumbers.forEach(stat => {
                const finalNumber = parseInt(stat.textContent);
                if (!isNaN(finalNumber)) {
                    window.manttoEffects?.countUp(stat, finalNumber, 1500);
                }
            });
        }

        function setupTableSearch() {
            const tables = document.querySelectorAll('.table-container');
            tables.forEach(container => {
                const header = container.querySelector('.table-header');
                const table = container.querySelector('.table');
                
                // Crear caja de búsqueda
                const searchBox = document.createElement('div');
                searchBox.className = 'search-box';
                searchBox.innerHTML = `
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Buscar en la tabla...">
                `;
                
                header.appendChild(searchBox);
                
                const searchInput = searchBox.querySelector('.search-input');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                
                // Funcionalidad de búsqueda
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        const shouldShow = text.includes(searchTerm);
                        
                        row.style.display = shouldShow ? '' : 'none';
                        
                        if (shouldShow && searchTerm) {
                            row.style.background = 'rgba(255, 235, 59, 0.1)';
                        } else {
                            row.style.background = '';
                        }
                    });
                    
                    // Mostrar mensaje si no hay resultados
                    const visibleRows = rows.filter(row => row.style.display !== 'none');
                    if (visibleRows.length === 0 && searchTerm) {
                        if (!tbody.querySelector('.no-results')) {
                            const noResults = document.createElement('tr');
                            noResults.className = 'no-results';
                            noResults.innerHTML = `
                                <td colspan="100%" class="text-center text-muted py-4">
                                    <i class="fas fa-search-minus fa-2x mb-2"></i><br>
                                    No se encontraron resultados para: "${searchTerm}"
                                </td>
                            `;
                            tbody.appendChild(noResults);
                        }
                    } else {
                        const noResultsRow = tbody.querySelector('.no-results');
                        if (noResultsRow) {
                            noResultsRow.remove();
                        }
                    }
                });
            });
        }
    </script>
</body>
</html>

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
                        <a class="nav-link active" href="modificar.php"><i class="fas fa-edit me-1"></i>Modificar</a>
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

    <div class="container mt-4"></body>

        <!-- Tabla de Mantenimiento -->
        <div class="table-container fade-in">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-wrench me-2"></i>Modificar Registros de Mantenimiento
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
                            while ($row = $resultado->fetch_assoc()) {
                                echo "<tr>
                                        <td><span class='badge bg-primary'>{$row['id']}</span></td>
                                        <td>{$row['areaa']}</td>
                                        <td>{$row['actividad']}</td>
                                        <td>{$row['frecuencia']}</td>
                                        <td>{$row['folio']}</td>
                                        <td>{$row['observaciones']}</td>
                                        <td>{$row['material']}</td>
                                        <td>
                                            <a href='editar.php?id={$row['id']}' class='btn btn-sm btn-warning me-1'>
                                                <i class='fas fa-edit'></i> Editar
                                            </a>
                                        </td>
                                      </tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='8' class='text-center text-muted'>Error al cargar datos: " . $e->getMessage() . "</td></tr>";
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
                    <i class="fas fa-users me-2"></i>Modificar Registros de Personal
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
                            while ($row = $resultado->fetch_assoc()) {
                                echo "<tr>
                                        <td><span class='badge bg-info'>{$row['id']}</span></td>
                                        <td><strong>{$row['nombre']}</strong></td>
                                        <td>{$row['cargo']}</td>
                                        <td>{$row['telefono']}</td>
                                        <td>{$row['email']}</td>
                                        <td>
                                            <a href='editarp.php?id={$row['id']}' class='btn btn-sm btn-warning me-1'>
                                                <i class='fas fa-edit'></i> Editar
                                            </a>
                                        </td>
                                      </tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='6' class='text-center text-muted'>Error al cargar datos: " . $e->getMessage() . "</td></tr>";
                        }
                        ?>
</                    </tbody>
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
        // Animaciones y efectos avanzados
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar efectos de fondo
            createBackgroundEffects();
            
            // Animación de entrada simplificada
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((el, index) => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0) scale(1)';
                setTimeout(() => {
                    el.style.transition = 'all 0.3s ease';
                }, index * 100);
            });

            // Efectos avanzados en tablas
            enhanceTableInteractions();

            // Confirmación elegante antes de editar
            const editButtons = document.querySelectorAll('a[href*="editar"]');
            editButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.href.split('id=')[1];
                    const row = this.closest('tr');
                    const recordName = row.cells[1].textContent.trim();
                    
                    showCustomConfirmModal(
                        '¿Editar Registro?',
                        `¿Estás seguro de que quieres editar el registro: <strong>${recordName}</strong>?`,
                        () => {
                            // Efecto de loading antes de redirección
                            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...';
                            this.style.pointerEvents = 'none';
                            setTimeout(() => {
                                window.location.href = this.href;
                            }, 500);
                        }
                    );
                });
            });

            // Animación de contadores en badges
            animateBadgeNumbers();

            // Efecto de brillo en headers
            createShimmerEffect();
        });

        function createBackgroundEffects() {
            const particleCount = 10;
            const container = document.querySelector('.dashboard-container');

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.style.cssText = `
                    position: absolute;
                    width: ${Math.random() * 4 + 2}px;
                    height: ${Math.random() * 4 + 2}px;
                    background: rgba(102, 126, 234, ${Math.random() * 0.2 + 0.05});
                    border-radius: 50%;
                    left: ${Math.random() * 100}%;
                    top: ${Math.random() * 100}%;
                    animation: gentleFloat ${Math.random() * 15 + 10}s infinite ease-in-out;
                    pointer-events: none;
                    z-index: 1;
                `;
                container.appendChild(particle);
            }

            // Añadir estilos personalizados
            addEnhancedStyles();
        }

        function addEnhancedStyles() {
            if (!document.getElementById('modify-styles')) {
                const style = document.createElement('style');
                style.id = 'modify-styles';
                style.textContent = `
                    @keyframes gentleFloat {
                        0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
                        33% { transform: translateY(-20px) rotate(120deg); opacity: 0.6; }
                        66% { transform: translateY(20px) rotate(240deg); opacity: 0.4; }
                    }
                    
                    @keyframes shimmer {
                        0% { background-position: -200% 0; }
                        100% { background-position: 200% 0; }
                    }
                    
                    @keyframes badgePop {
                        0% { transform: scale(0.8) rotate(-10deg); }
                        50% { transform: scale(1.1) rotate(5deg); }
                        100% { transform: scale(1) rotate(0deg); }
                    }
                    
                    .shimmer-effect {
                        background: linear-gradient(
                            90deg,
                            transparent,
                            rgba(255, 255, 255, 0.2),
                            transparent
                        );
                        background-size: 200% 100%;
                        animation: shimmer 2s infinite;
                    }
                    
                    .modal-backdrop-custom {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.6);
                        backdrop-filter: blur(8px);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 9999;
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    }
                    
                    .modal-backdrop-custom.show {
                        opacity: 1;
                    }
                    
                    .custom-modal {
                        background: white;
                        border-radius: 20px;
                        padding: 2rem;
                        max-width: 400px;
                        width: 90%;
                        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                        transform: scale(0.7) translateY(50px);
                        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                    }
                    
                    .modal-backdrop-custom.show .custom-modal {
                        transform: scale(1) translateY(0);
                    }
                    
                    .table-row-hover {
                        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                    }
                    
                    .table-row-hover:hover {
                        transform: translateX(15px) scale(1.02);
                        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
                        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
                        z-index: 10;
                        position: relative;
                    }
                `;
                document.head.appendChild(style);
            }
        }

        function enhanceTableInteractions() {
            const tables = document.querySelectorAll('.table');
            tables.forEach(table => {
                const rows = table.querySelectorAll('tbody tr');
                
                rows.forEach((row, index) => {
                    row.classList.add('table-row-hover');
                    row.style.animationDelay = `${index * 0.1}s`;
                    
                    // Efecto de onda al hacer hover
                    row.addEventListener('mouseenter', function(e) {
                        createTableRowWave(this, e);
                    });
                    
                    // Efecto de selección
                    row.addEventListener('click', function() {
                        this.style.background = 'rgba(102, 126, 234, 0.1)';
                        setTimeout(() => {
                            this.style.background = '';
                        }, 300);
                    });
                });
            });
        }

        function createTableRowWave(row, event) {
            const wave = document.createElement('div');
            const rect = row.getBoundingClientRect();
            const x = event.clientX - rect.left;
            
            wave.style.cssText = `
                position: absolute;
                left: ${x}px;
                top: 0;
                width: 0;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
                animation: tableWave 0.6s ease-out;
                pointer-events: none;
                z-index: 1;
            `;
            
            row.style.position = 'relative';
            row.style.overflow = 'hidden';
            row.appendChild(wave);
            
            setTimeout(() => wave.remove(), 600);
            
            if (!document.querySelector('style[data-table-wave]')) {
                const style = document.createElement('style');
                style.setAttribute('data-table-wave', 'true');
                style.textContent = `
                    @keyframes tableWave {
                        to {
                            width: 200px;
                            left: -100px;
                        }
                    }
                `;
                document.head.appendChild(style);
            }
        }

        function showCustomConfirmModal(title, message, onConfirm) {
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop-custom';
            
            const modal = document.createElement('div');
            modal.className = 'custom-modal';
            modal.innerHTML = `
                <div class="text-center">
                    <div style="font-size: 3rem; color: #f6ad55; margin-bottom: 1rem;">
                        <i class="fas fa-edit"></i>
                    </div>
                    <h4 style="color: #2d3748; margin-bottom: 1rem;">${title}</h4>
                    <p style="color: #718096; margin-bottom: 2rem;">${message}</p>
                    <div style="display: flex; gap: 1rem; justify-content: center;">
                        <button class="btn-secondary" onclick="closeCustomModal()" style="padding: 0.8rem 1.5rem;">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button class="btn-primary" onclick="confirmCustomModal()" style="padding: 0.8rem 1.5rem;">
                            <i class="fas fa-check me-2"></i>Confirmar
                        </button>
                    </div>
                </div>
            `;
            
            backdrop.appendChild(modal);
            document.body.appendChild(backdrop);
            
            // Mostrar modal con animación
            setTimeout(() => backdrop.classList.add('show'), 10);
            
            // Configurar callbacks globales
            window.closeCustomModal = () => {
                backdrop.classList.remove('show');
                setTimeout(() => backdrop.remove(), 300);
            };
            
            window.confirmCustomModal = () => {
                onConfirm();
                backdrop.classList.remove('show');
                setTimeout(() => backdrop.remove(), 300);
            };
            
            // Cerrar con escape
            const handleEscape = (e) => {
                if (e.key === 'Escape') {
                    window.closeCustomModal();
                    document.removeEventListener('keydown', handleEscape);
                }
            };
            document.addEventListener('keydown', handleEscape);
        }

        function animateBadgeNumbers() {
            const badges = document.querySelectorAll('.badge');
            badges.forEach((badge, index) => {
                setTimeout(() => {
                    badge.style.animation = 'badgePop 0.6s ease-out';
                }, index * 100);
            });
        }

        function createShimmerEffect() {
            const headers = document.querySelectorAll('.table-title');
            headers.forEach(header => {
                header.classList.add('shimmer-effect');
            });
        }
    </script>
</body>
</html>

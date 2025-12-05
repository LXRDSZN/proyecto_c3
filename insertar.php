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

    try {
        $db = new Database();
        $conexion = $db->getConnection();

        if (isset($_POST['insert_mantto'])) {
            $ID = trim($_POST['ID']);
            $AREA = trim($_POST['AREA']);
            $ACTIVIDAD = trim($_POST['ACTIVIDAD']);
            $FRECUENCIA = trim($_POST['FRECUENCIA']);
            $FOLIO = trim($_POST['FOLIO']);
            $OBSERVACIONES = trim($_POST['OBSERVACIONES']);
            $MATERIAL = trim($_POST['MATERIAL']);

            $stmt = $conexion->prepare("SELECT * FROM mantenimiento WHERE id = ?");
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $mensaje_mantto = "El ID $ID ya existe en mantenimiento. Ingresa uno diferente.";
                $tipo_mensaje_mantto = 'error';
            } else {
                $stmt = $conexion->prepare("INSERT INTO mantenimiento (id, areaa, actividad, frecuencia, folio, observaciones, material) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssss", $ID, $AREA, $ACTIVIDAD, $FRECUENCIA, $FOLIO, $OBSERVACIONES, $MATERIAL);
                
                if ($stmt->execute()) {
                    $mensaje_mantto = "Registro de mantenimiento insertado correctamente.";
                    $tipo_mensaje_mantto = 'success';
                } else {
                    $mensaje_mantto = "Error al insertar el registro de mantenimiento.";
                    $tipo_mensaje_mantto = 'error';
                }
            }
            $stmt->close();
        }

        if (isset($_POST['insert_personal'])) {
            $ID = trim($_POST['ID']);
            $NOMBRE = trim($_POST['NOMBRE']);
            $CARGO = trim($_POST['CARGO']);
            $TELEFONO = trim($_POST['TELEFONO']);
            $EMAIL = trim($_POST['EMAIL']);

            $stmt = $conexion->prepare("SELECT * FROM personal WHERE id = ?");
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $mensaje_personal = "El ID $ID ya existe en personal. Ingresa uno diferente.";
                $tipo_mensaje_personal = 'error';
            } else {
                $stmt = $conexion->prepare("INSERT INTO personal (id, nombre, cargo, telefono, email) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $ID, $NOMBRE, $CARGO, $TELEFONO, $EMAIL);
                
                if ($stmt->execute()) {
                    $mensaje_personal = "Registro de personal insertado correctamente.";
                    $tipo_mensaje_personal = 'success';
                } else {
                    $mensaje_personal = "Error al insertar el registro de personal.";
                    $tipo_mensaje_personal = 'error';
                }
            }
            $stmt->close();
        }

    } catch (Exception $e) {
        $mensaje_mantto = "Error del sistema: " . $e->getMessage();
        $tipo_mensaje_mantto = 'error';
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
        <!-- Sección Mantenimiento -->
        <div class="form-container fade-in">
            <h2 class="form-title">
                <i class="fas fa-wrench me-3"></i>Registro de Mantenimiento
            </h2>

            <?php if ($mensaje_mantto): ?>
                <div class="alert alert-<?php echo $tipo_mensaje_mantto; ?>"><?php echo $mensaje_mantto; ?></div>
            <?php endif; ?>

            <form action="insertar.php" method="post">
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

        <!-- Tabla de Mantenimiento -->
        <div class="table-container fade-in">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-list me-2"></i>Registros de Mantenimiento
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $resultado = $conexion->query("SELECT * FROM mantenimiento ORDER BY id DESC");
                            while ($row = $resultado->fetch_assoc()) {
                                echo "<tr>
                                        <td><span class='badge bg-primary'>{$row['id']}</span></td>
                                        <td>{$row['areaa']}</td>
                                        <td>{$row['actividad']}</td>
                                        <td>{$row['frecuencia']}</td>
                                        <td>{$row['folio']}</td>
                                        <td>{$row['observaciones']}</td>
                                        <td>{$row['material']}</td>
                                      </tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='7' class='text-center text-muted'>Error al cargar datos</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sección Personal -->
        <div class="form-container fade-in">
            <h2 class="form-title">
                <i class="fas fa-users me-3"></i>Registro de Personal
            </h2>

            <?php if ($mensaje_personal): ?>
                <div class="alert alert-<?php echo $tipo_mensaje_personal; ?>"><?php echo $mensaje_personal; ?></div>
            <?php endif; ?>

            <form action="insertar.php" method="post">
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

        <!-- Tabla de Personal -->
        <div class="table-container fade-in">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-users me-2"></i>Registros de Personal
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $resultado = $conexion->query("SELECT * FROM personal ORDER BY id DESC");
                            while ($row = $resultado->fetch_assoc()) {
                                echo "<tr>
                                        <td><span class='badge bg-info'>{$row['id']}</span></td>
                                        <td><strong>{$row['nombre']}</strong></td>
                                        <td>{$row['cargo']}</td>
                                        <td>{$row['telefono']}</td>
                                        <td>{$row['email']}</td>
                                      </tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='5' class='text-center text-muted'>Error al cargar datos</td></tr>";
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
        // Animaciones avanzadas y efectos interactivos
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar partículas de fondo
            createBackgroundParticles();
            
            // Animación de entrada simplificada
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((el, index) => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
                setTimeout(() => {
                    el.style.transition = 'all 0.3s ease';
                }, index * 100);
            });

            // Validación y efectos avanzados de formularios
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                // Animación en envío con efectos visuales
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        // Crear efecto de ondas al hacer clic
                        createRippleEffect(submitBtn, e);
                        
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
                        submitBtn.disabled = true;
                        submitBtn.style.background = 'linear-gradient(135deg, #a0aec0, #718096)';
                        
                        // Animación de pulsación
                        submitBtn.style.animation = 'pulse 1.5s infinite';
                    }
                });

                // Efectos avanzados en inputs
                const inputs = form.querySelectorAll('.form-input');
                inputs.forEach(input => {
                    // Efecto de focus mejorado
                    input.addEventListener('focus', function() {
                        this.parentElement.classList.add('input-focused');
                        createInputGlow(this);
                        
                        // Animación del label
                        const label = this.previousElementSibling;
                        if (label && label.classList.contains('form-label')) {
                            label.style.transform = 'translateY(-2px) scale(0.95)';
                            label.style.color = '#667eea';
                        }
                    });

                    input.addEventListener('blur', function() {
                        this.parentElement.classList.remove('input-focused');
                        removeInputGlow(this);
                        
                        // Restaurar label
                        const label = this.previousElementSibling;
                        if (label && label.classList.contains('form-label')) {
                            label.style.transform = '';
                            label.style.color = '';
                        }
                    });

                    // Validación visual en tiempo real
                    input.addEventListener('input', function() {
                        validateInputVisually(this);
                    });

                    // Efecto de escritura
                    input.addEventListener('keydown', function(e) {
                        createTypingEffect(this);
                    });
                });
            });

            // Auto-hide alerts con animación suave
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach((alert, index) => {
                    setTimeout(() => {
                        alert.style.transition = 'all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                        alert.style.transform = 'translateX(-100%) scale(0.8) rotate(-5deg)';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 600);
                    }, index * 200);
                });
            }, 4000);

            // Efectos hover mejorados en tablas
            enhanceTableEffects();

            // Animación de números en badges
            animateNumbers();
        });

        function createBackgroundParticles() {
            const particleCount = 12;
            const container = document.querySelector('.dashboard-container');

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'bg-particle';
                particle.style.cssText = `
                    position: absolute;
                    width: ${Math.random() * 5 + 3}px;
                    height: ${Math.random() * 5 + 3}px;
                    background: rgba(102, 126, 234, ${Math.random() * 0.15 + 0.05});
                    border-radius: 50%;
                    left: ${Math.random() * 100}%;
                    top: ${Math.random() * 100}%;
                    animation: particleFloat ${Math.random() * 20 + 15}s infinite ease-in-out;
                    pointer-events: none;
                    z-index: 1;
                `;
                container.appendChild(particle);
            }

            // Añadir estilos CSS para efectos
            addCustomStyles();
        }

        function addCustomStyles() {
            if (!document.getElementById('enhanced-styles')) {
                const style = document.createElement('style');
                style.id = 'enhanced-styles';
                style.textContent = `
                    @keyframes particleFloat {
                        0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
                        25% { transform: translateY(-40px) rotate(90deg) scale(1.2); }
                        50% { transform: translateY(20px) rotate(180deg) scale(0.8); }
                        75% { transform: translateY(-20px) rotate(270deg) scale(1.1); }
                    }
                    
                    @keyframes pulse {
                        0%, 100% { transform: scale(1); }
                        50% { transform: scale(1.05); }
                    }
                    
                    @keyframes ripple {
                        to {
                            transform: scale(4);
                            opacity: 0;
                        }
                    }
                    
                    .input-focused .form-label {
                        color: #667eea !important;
                        font-weight: 700;
                    }
                    
                    .input-glow {
                        position: relative;
                    }
                    
                    .input-glow::after {
                        content: '';
                        position: absolute;
                        top: -3px;
                        left: -3px;
                        right: -3px;
                        bottom: -3px;
                        background: linear-gradient(135deg, #667eea, #764ba2);
                        border-radius: 19px;
                        z-index: -1;
                        opacity: 0.4;
                        filter: blur(10px);
                        animation: glowPulse 2s ease-in-out infinite;
                    }
                    
                    @keyframes glowPulse {
                        0%, 100% { opacity: 0.4; transform: scale(1); }
                        50% { opacity: 0.6; transform: scale(1.02); }
                    }
                    
                    .typing-effect {
                        position: relative;
                        overflow: hidden;
                    }
                    
                    .typing-effect::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 2px;
                        height: 100%;
                        background: #667eea;
                        animation: blink 1s infinite;
                    }
                    
                    @keyframes blink {
                        0%, 50% { opacity: 1; }
                        51%, 100% { opacity: 0; }
                    }
                    
                    .number-animate {
                        display: inline-block;
                        animation: numberBounce 0.6s ease-out;
                    }
                    
                    @keyframes numberBounce {
                        0% { transform: scale(0.3) rotate(-15deg); opacity: 0; }
                        50% { transform: scale(1.1) rotate(5deg); }
                        100% { transform: scale(1) rotate(0deg); opacity: 1; }
                    }
                `;
                document.head.appendChild(style);
            }
        }

        function createRippleEffect(button, event) {
            const rect = button.getBoundingClientRect();
            const ripple = document.createElement('span');
            const size = Math.max(rect.width, rect.height) * 2;
            const x = event.clientX - rect.left - size / 2;
            const y = event.clientY - rect.top - size / 2;

            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: radial-gradient(circle, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0.3) 50%, transparent 100%);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.8s ease-out;
                pointer-events: none;
                z-index: 10;
            `;

            button.style.position = 'relative';
            button.style.overflow = 'hidden';
            button.appendChild(ripple);
            setTimeout(() => ripple.remove(), 800);
        }

        function createInputGlow(input) {
            input.classList.add('input-glow');
            setTimeout(() => {
                input.classList.remove('input-glow');
            }, 3000);
        }

        function removeInputGlow(input) {
            input.classList.remove('input-glow');
        }

        function validateInputVisually(input) {
            const value = input.value.trim();
            const isRequired = input.hasAttribute('required');
            
            // Remover clases previas
            input.classList.remove('is-valid', 'is-invalid');
            
            if (isRequired && value.length === 0) {
                input.style.borderColor = '#f56565';
                input.style.boxShadow = '0 0 0 3px rgba(245, 101, 101, 0.1)';
                input.classList.add('is-invalid');
            } else if (value.length > 0) {
                input.style.borderColor = '#48bb78';
                input.style.boxShadow = '0 0 0 3px rgba(72, 187, 120, 0.1)';
                input.classList.add('is-valid');
                
                // Efecto de éxito
                createSuccessEffect(input);
            } else {
                input.style.borderColor = '#e2e8f0';
                input.style.boxShadow = '';
            }
        }

        function createTypingEffect(input) {
            input.classList.add('typing-effect');
            setTimeout(() => {
                input.classList.remove('typing-effect');
            }, 1000);
        }

        function createSuccessEffect(input) {
            const checkmark = document.createElement('i');
            checkmark.className = 'fas fa-check';
            checkmark.style.cssText = `
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%) scale(0);
                color: #48bb78;
                font-size: 1.2rem;
                animation: checkmarkPop 0.4s ease-out forwards;
                pointer-events: none;
            `;
            
            const container = input.parentElement;
            container.style.position = 'relative';
            container.appendChild(checkmark);
            
            setTimeout(() => checkmark.remove(), 2000);
            
            const style = document.createElement('style');
            style.textContent = `
                @keyframes checkmarkPop {
                    0% { transform: translateY(-50%) scale(0) rotate(-180deg); }
                    50% { transform: translateY(-50%) scale(1.2) rotate(-90deg); }
                    100% { transform: translateY(-50%) scale(1) rotate(0deg); }
                }
            `;
            if (!document.querySelector('style[data-checkmark]')) {
                style.setAttribute('data-checkmark', 'true');
                document.head.appendChild(style);
            }
        }

        function enhanceTableEffects() {
            const tableRows = document.querySelectorAll('.table tbody tr');
            tableRows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.1}s`;
                
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(12px) scale(1.02)';
                    this.style.zIndex = '10';
                    
                    // Efecto de sombra expansiva
                    this.style.boxShadow = '0 12px 40px rgba(102, 126, 234, 0.15)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                    this.style.zIndex = '';
                    this.style.boxShadow = '';
                });
            });
        }

        function animateNumbers() {
            const badges = document.querySelectorAll('.badge');
            badges.forEach(badge => {
                const number = parseInt(badge.textContent);
                if (!isNaN(number)) {
                    badge.textContent = '0';
                    let current = 0;
                    const increment = number / 20;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= number) {
                            badge.textContent = number;
                            badge.classList.add('number-animate');
                            clearInterval(timer);
                        } else {
                            badge.textContent = Math.floor(current);
                        }
                    }, 50);
                }
            });
        }
    </script>

</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CECYTE - Sistema de Mantenimiento</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .welcome-container {
            text-align: center;
            color: white;
            animation: fadeInUp 1s ease-out;
        }
        
        .logo {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: bounce 2s infinite;
        }
        
        .title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }
        
        .redirect-btn {
            background: rgba(255,255,255,0.2);
            border: 2px solid white;
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .redirect-btn:hover {
            background: white;
            color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        
        .particle {
            position: absolute;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            50% {
                transform: translateY(-100px) rotate(180deg);
            }
        }
        
        .countdown {
            margin-top: 1rem;
            font-size: 0.9rem;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="particles"></div>
    
    <div class="welcome-container">
        <div class="logo">
            <i class="fas fa-tools"></i>
        </div>
        <h1 class="title">CECYTE</h1>
        <p class="subtitle">Sistema de Gestión de Mantenimiento</p>
        
        <a href="login.php" class="redirect-btn">
            <i class="fas fa-sign-in-alt me-2"></i>
            Acceder al Sistema
        </a>
        
        <div class="countdown">
            Redirigiendo automáticamente en <span id="counter">5</span> segundos...
        </div>
    </div>

    <script>
        // Crear partículas flotantes
        function createParticles() {
            const particlesContainer = document.querySelector('.particles');
            const particleCount = 20;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                const size = Math.random() * 5 + 3;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 4) + 's';
                
                particlesContainer.appendChild(particle);
            }
        }
        
        // Contador de redirección
        let counter = 5;
        const countdownElement = document.getElementById('counter');
        
        const countdown = setInterval(() => {
            counter--;
            countdownElement.textContent = counter;
            
            if (counter <= 0) {
                clearInterval(countdown);
                window.location.href = 'login.php';
            }
        }, 1000);
        
        // Inicializar
        createParticles();
        
        // Redirección inmediata al hacer clic
        document.querySelector('.redirect-btn').addEventListener('click', () => {
            clearInterval(countdown);
        });
    </script>
</body>
</html>
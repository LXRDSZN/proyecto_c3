// Login animation and interactions
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.login-form');
    const inputs = document.querySelectorAll('.form-input');
    const submitBtn = document.querySelector('.btn-login');
    const btnText = submitBtn.querySelector('.btn-text');
    
    // Input focus animations
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
        
        // Check if input has value on load
        if (input.value) {
            input.parentElement.classList.add('focused');
        }
    });
    
    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        if (submitBtn && btnText) {
            btnText.innerHTML = '<span class="loading"></span> Iniciando sesión...';
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.8';
        }
    });
    
    // Password visibility toggle
    const passwordInput = document.getElementById('contraseña');
    if (passwordInput) {
        const togglePassword = document.createElement('i');
        togglePassword.className = 'fas fa-eye-slash input-icon password-toggle';
        togglePassword.style.cursor = 'pointer';
        togglePassword.style.right = '2.5rem';
        
        passwordInput.parentElement.appendChild(togglePassword);
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            this.className = type === 'password' 
                ? 'fas fa-eye-slash input-icon password-toggle' 
                : 'fas fa-eye input-icon password-toggle';
        });
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
            const nextInput = findNextInput(e.target);
            if (nextInput) {
                nextInput.focus();
            } else {
                form.submit();
            }
        }
    });
    
    function findNextInput(currentInput) {
        const inputsArray = Array.from(inputs);
        const currentIndex = inputsArray.indexOf(currentInput);
        return inputsArray[currentIndex + 1] || null;
    }
    
    // Auto-hide alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'all 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(-20px)';
            setTimeout(() => alert.remove(), 500);
        });
    }, 4000);
    
    // Add ripple effect to login button
    submitBtn.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        `;
        
        this.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
    });
    
    // Add CSS for ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        .focused .input-icon {
            color: #667eea !important;
        }
        
        .password-toggle:hover {
            color: #667eea !important;
        }
    `;
    document.head.appendChild(style);
});
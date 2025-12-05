// Enhanced Effects and Animations Library for MANTTO System

class ManttoEffects {
    constructor() {
        this.init();
    }

    init() {
        this.addGlobalStyles();
        this.setupScrollEffects();
        this.setupPageTransitions();
        this.setupCustomCursor();
    }

    addGlobalStyles() {
        if (!document.getElementById('mantto-enhanced-styles')) {
            const style = document.createElement('style');
            style.id = 'mantto-enhanced-styles';
            style.textContent = `
                /* Smooth page transitions - disabled for compatibility */

                /* Custom cursor effects */
                .custom-cursor {
                    width: 20px;
                    height: 20px;
                    border: 2px solid #667eea;
                    border-radius: 50%;
                    position: fixed;
                    pointer-events: none;
                    z-index: 9999;
                    transition: all 0.2s ease;
                    backdrop-filter: blur(2px);
                }

                .custom-cursor.hovering {
                    width: 40px;
                    height: 40px;
                    background: rgba(102, 126, 234, 0.1);
                    border-color: #764ba2;
                }

                /* Scroll progress indicator */
                .scroll-progress {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 0%;
                    height: 4px;
                    background: linear-gradient(90deg, #667eea, #764ba2);
                    z-index: 9999;
                    transition: width 0.1s ease;
                }

                /* Enhanced hover states */
                .enhanced-hover {
                    position: relative;
                    overflow: hidden;
                }

                .enhanced-hover::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: -100%;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                    transition: left 0.5s ease;
                }

                .enhanced-hover:hover::before {
                    left: 100%;
                }

                /* Glassmorphism effect */
                .glass-effect {
                    background: rgba(255, 255, 255, 0.15);
                    backdrop-filter: blur(20px);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                }

                /* Neon glow effect */
                .neon-glow {
                    box-shadow: 
                        0 0 5px rgba(102, 126, 234, 0.5),
                        0 0 10px rgba(102, 126, 234, 0.5),
                        0 0 20px rgba(102, 126, 234, 0.5),
                        0 0 40px rgba(102, 126, 234, 0.5);
                }

                /* Floating elements */
                .float-element {
                    animation: floatUpDown 6s ease-in-out infinite;
                }

                @keyframes floatUpDown {
                    0%, 100% { transform: translateY(0px); }
                    50% { transform: translateY(-10px); }
                }

                /* Morphing shapes */
                .morphing-shape {
                    border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
                    animation: morph 8s ease-in-out infinite;
                }

                @keyframes morph {
                    0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
                    50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
                }
            `;
            document.head.appendChild(style);
        }
    }

    setupScrollEffects() {
        // Scroll progress indicator
        const progressBar = document.createElement('div');
        progressBar.className = 'scroll-progress';
        document.body.appendChild(progressBar);

        window.addEventListener('scroll', () => {
            const scrollTop = document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrollPercent = (scrollTop / scrollHeight) * 100;
            progressBar.style.width = scrollPercent + '%';
        });

        // Parallax effect for headers
        const headers = document.querySelectorAll('.dashboard-header');
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            headers.forEach(header => {
                header.style.transform = `translateY(${scrolled * 0.4}px)`;
            });
        });
    }

    setupPageTransitions() {
        // Transiciones deshabilitadas por compatibilidad
        return;
    }

    setupCustomCursor() {
        if (window.innerWidth > 768) { // Only on desktop
            const cursor = document.createElement('div');
            cursor.className = 'custom-cursor';
            document.body.appendChild(cursor);

            document.addEventListener('mousemove', (e) => {
                cursor.style.left = e.clientX - 10 + 'px';
                cursor.style.top = e.clientY - 10 + 'px';
            });

            // Hover effects
            const hoverElements = document.querySelectorAll('a, button, .nav-card, .btn');
            hoverElements.forEach(el => {
                el.addEventListener('mouseenter', () => cursor.classList.add('hovering'));
                el.addEventListener('mouseleave', () => cursor.classList.remove('hovering'));
            });
        }
    }

    // Utility methods for specific effects
    createRipple(element, event) {
        const rect = element.getBoundingClientRect();
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
            background: radial-gradient(circle, rgba(255,255,255,0.6) 0%, transparent 70%);
            border-radius: 50%;
            transform: scale(0);
            animation: rippleEffect 0.6s ease-out;
            pointer-events: none;
        `;

        element.style.position = 'relative';
        element.style.overflow = 'hidden';
        element.appendChild(ripple);

        setTimeout(() => ripple.remove(), 600);

        // Add ripple animation if not exists
        if (!document.querySelector('style[data-ripple]')) {
            const style = document.createElement('style');
            style.setAttribute('data-ripple', 'true');
            style.textContent = `
                @keyframes rippleEffect {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }

    typeWriter(element, text, speed = 100) {
        let i = 0;
        element.textContent = '';
        
        function type() {
            if (i < text.length) {
                element.textContent += text.charAt(i);
                i++;
                setTimeout(type, speed);
            }
        }
        
        type();
    }

    countUp(element, target, duration = 1000) {
        let start = 0;
        const increment = target / (duration / 16);
        
        function count() {
            start += increment;
            if (start < target) {
                element.textContent = Math.floor(start);
                requestAnimationFrame(count);
            } else {
                element.textContent = target;
            }
        }
        
        count();
    }

    shake(element, intensity = 5, duration = 500) {
        const originalTransform = element.style.transform;
        let startTime = null;

        function animate(timestamp) {
            if (!startTime) startTime = timestamp;
            const elapsed = timestamp - startTime;
            
            if (elapsed < duration) {
                const x = (Math.random() - 0.5) * intensity;
                const y = (Math.random() - 0.5) * intensity;
                element.style.transform = `translate(${x}px, ${y}px)`;
                requestAnimationFrame(animate);
            } else {
                element.style.transform = originalTransform;
            }
        }
        
        requestAnimationFrame(animate);
    }

    pulse(element, scale = 1.1, duration = 300) {
        const originalTransform = element.style.transform;
        element.style.transition = `transform ${duration}ms ease`;
        element.style.transform = `scale(${scale})`;
        
        setTimeout(() => {
            element.style.transform = originalTransform;
        }, duration);
    }
}

// Initialize effects when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Solo inicializar si no causa problemas
    try {
        window.manttoEffects = new ManttoEffects();
    } catch (e) {
        console.log('Effects disabled for compatibility');
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ManttoEffects;
}
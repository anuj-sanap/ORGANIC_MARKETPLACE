// Premium Interactive Features
document.addEventListener('DOMContentLoaded', function() {
    
    // Smooth Scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Animated Counters
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const increment = target / 100;
            let current = 0;
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    counter.innerText = Math.floor(current) + '+';
                    setTimeout(updateCounter, 20);
                } else {
                    counter.innerText = target + '+';
                }
            };
            updateCounter();
        });
    }

    // Intersection Observer for Animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                if (entry.target.classList.contains('counter-container')) {
                    animateCounters();
                }
            }
        });
    }, observerOptions);

    // Observe all animated elements
    document.querySelectorAll('.floating-card, .crop-card, .dashboard-card, .counter-container').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        observer.observe(el);
    });

    // Parallax Effect
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelector('.hero-bg');
        if (parallax) {
            parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });

    // Quantity Input Enhancement
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', function() {
            const max = parseInt(this.max);
            const min = parseInt(this.min);
            if (this.value > max) this.value = max;
            if (this.value < min) this.value = min;
        });
    });

    // Add to Cart Animation
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const btnRect = this.getBoundingClientRect();
            const ripple = document.createElement('span');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255,255,255,0.6)';
            ripple.style.transform = 'scale(0)';
            ripple.style.width = '20px';
            ripple.style.height = '20px';
            ripple.style.left = e.clientX - btnRect.left - 10 + 'px';
            ripple.style.top = e.clientY - btnRect.top - 10 + 'px';
            ripple.style.animation = 'ripple 0.6s linear';
            this.style.position = 'relative';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
});

/**
 * SAO System — Landing Page Interactions
 * Scroll reveals, counter animations, status bars, particles
 */

document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
    initStatusBars();
    initCounters();
    initParticles();
    initNavScroll();
});

/* ── Scroll Reveal (Intersection Observer) ── */
function initScrollReveal() {
    const elements = document.querySelectorAll('[data-animate], [data-stagger]');
    if (!elements.length) return;

    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced) {
        elements.forEach(el => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15, rootMargin: '0px 0px -50px 0px' }
    );

    elements.forEach(el => observer.observe(el));
}

/* ── Status Bars (animate fill on scroll) ── */
function initStatusBars() {
    const bars = document.querySelectorAll('.status-bar-fill');
    if (!bars.length) return;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-filled');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.5 }
    );

    bars.forEach(bar => observer.observe(bar));
}

/* ── Counter Animation ── */
function initCounters() {
    const counters = document.querySelectorAll('[data-counter]');
    if (!counters.length) return;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = parseInt(el.dataset.counter, 10);
                    const suffix = el.dataset.counterSuffix || '';
                    animateCounter(el, 0, target, 1500, suffix);
                    observer.unobserve(el);
                }
            });
        },
        { threshold: 0.5 }
    );

    counters.forEach(c => observer.observe(c));
}

function animateCounter(el, start, end, duration, suffix) {
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const value = Math.round(start + (end - start) * eased);

        el.textContent = value + suffix;

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
}

/* ── Lightweight Particle System (Canvas) ── */
function initParticles() {
    const canvas = document.getElementById('particles-bg');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let width, height;
    let particles = [];
    let animId;

    const isMobile = window.innerWidth < 768;
    const PARTICLE_COUNT = isMobile ? 20 : 45;
    const LINK_DISTANCE = 120;

    function resize() {
        width = canvas.width = canvas.parentElement.offsetWidth;
        height = canvas.height = canvas.parentElement.offsetHeight;
    }

    function createParticles() {
        particles = [];
        for (let i = 0; i < PARTICLE_COUNT; i++) {
            particles.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 0.4,
                vy: (Math.random() - 0.5) * 0.4,
                size: Math.random() * 1.5 + 0.5,
            });
        }
    }

    function draw() {
        ctx.clearRect(0, 0, width, height);

        // Links
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < LINK_DISTANCE) {
                    const opacity = (1 - dist / LINK_DISTANCE) * 0.08;
                    ctx.strokeStyle = `rgba(0, 245, 255, ${opacity})`;
                    ctx.lineWidth = 0.5;
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.stroke();
                }
            }
        }

        // Dots
        particles.forEach(p => {
            p.x += p.vx;
            p.y += p.vy;

            if (p.x < 0 || p.x > width) p.vx *= -1;
            if (p.y < 0 || p.y > height) p.vy *= -1;

            ctx.fillStyle = 'rgba(0, 245, 255, 0.2)';
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
            ctx.fill();
        });

        animId = requestAnimationFrame(draw);
    }

    resize();
    createParticles();
    draw();

    window.addEventListener('resize', () => {
        resize();
        createParticles();
    });

    // Pause when hero not visible
    const heroSection = canvas.closest('section');
    if (heroSection) {
        const obs = new IntersectionObserver(([entry]) => {
            if (entry.isIntersecting) {
                if (!animId) draw();
            } else {
                cancelAnimationFrame(animId);
                animId = null;
            }
        }, { threshold: 0 });
        obs.observe(heroSection);
    }
}

/* ── Navbar scroll effect ── */
function initNavScroll() {
    const nav = document.getElementById('nav-hud');
    if (!nav) return;

    const handleScroll = () => {
        if (window.scrollY > 80) {
            nav.classList.add('bg-hud-dark/95', 'backdrop-blur-sm', 'shadow-lg', 'shadow-black/30');
            nav.classList.remove('bg-transparent');
        } else {
            nav.classList.remove('bg-hud-dark/95', 'backdrop-blur-sm', 'shadow-lg', 'shadow-black/30');
            nav.classList.add('bg-transparent');
        }
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
}

document.addEventListener('DOMContentLoaded', () => {
    initHPBar();
    initStatusBars();
    initScrollReveal();
});

/* ── HP Bar Animation (Header) ── */
function initHPBar() {
    const hpBar = document.getElementById('hp-bar');
    const hpText = document.getElementById('hp-text');
    if (!hpBar || !hpText) return;

    hpBar.style.width = '0%';
    setTimeout(() => {
        hpBar.style.width = '100%';
        animateCounter(hpText, 0, 12500, 1500, ' / 12500');
    }, 600);
}

/* ── Status Bars (Scroll Triggered) ── */
function initStatusBars() {
    const bars = document.querySelectorAll('.status-fill');
    if (!bars.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                const targetWidth = target.style.getPropertyValue('--target-width');
                target.style.width = targetWidth;
                observer.unobserve(target);
            }
        });
    }, { threshold: 0.3 });

    bars.forEach(bar => {
        bar.style.width = '0%';
        observer.observe(bar);
    });
}

/* ── Scroll Reveal ── */
function initScrollReveal() {
    const elements = document.querySelectorAll('[data-animate]');
    if (!elements.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    elements.forEach(el => observer.observe(el));
}

/* ── Counter Utility ── */
function animateCounter(el, start, end, duration, suffix) {
    let startTime = null;
    const step = (ts) => {
        if (!startTime) startTime = ts;
        const progress = Math.min((ts - startTime) / duration, 1);
        el.textContent = Math.floor(progress * (end - start) + start) + (suffix || '');
        if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
}

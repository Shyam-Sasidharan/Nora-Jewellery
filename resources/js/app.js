import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('.site-header');
    const menuButton = document.querySelector('[data-menu-toggle]');
    const menu = document.querySelector('[data-menu]');

    const setHeaderState = () => {
        header?.classList.toggle('is-scrolled', window.scrollY > 20);
    };

    setHeaderState();
    window.addEventListener('scroll', setHeaderState, { passive: true });

    menuButton?.addEventListener('click', () => {
        menu?.classList.toggle('is-open');
    });

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.14 });

    document.querySelectorAll('.reveal').forEach((element, index) => {
        element.style.transitionDelay = `${Math.min(index % 8, 6) * 55}ms`;
        revealObserver.observe(element);
    });

    document.querySelectorAll('[data-tilt]').forEach((card) => {
        card.addEventListener('mousemove', (event) => {
            const bounds = card.getBoundingClientRect();
            const x = event.clientX - bounds.left;
            const y = event.clientY - bounds.top;
            const rotateY = ((x / bounds.width) - 0.5) * 10;
            const rotateX = ((y / bounds.height) - 0.5) * -10;
            card.style.transform = `perspective(900px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-6px)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });

    document.querySelectorAll('[data-slider]').forEach((slider) => {
        const slides = Array.from(slider.querySelectorAll('.hero-slide'));
        if (slides.length < 2) {
            return;
        }

        let index = 0;
        setInterval(() => {
            slides[index].classList.remove('is-active');
            index = (index + 1) % slides.length;
            slides[index].classList.add('is-active');
        }, 5200);
    });

    document.querySelectorAll('[data-luxury-hero]').forEach((hero) => {
        hero.addEventListener('mousemove', (event) => {
            const bounds = hero.getBoundingClientRect();
            const x = ((event.clientX - bounds.left) / bounds.width - 0.5) * 18;
            const y = ((event.clientY - bounds.top) / bounds.height - 0.5) * 18;
            hero.style.setProperty('--mx', `${x}px`);
            hero.style.setProperty('--my', `${y}px`);
        });
    });

    document.querySelectorAll('.gold-button, .admin-button, .nav-cta').forEach((button) => {
        button.addEventListener('pointerdown', (event) => {
            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            ripple.className = 'button-ripple';
            ripple.style.left = `${event.clientX - rect.left}px`;
            ripple.style.top = `${event.clientY - rect.top}px`;
            button.appendChild(ripple);
            window.setTimeout(() => ripple.remove(), 650);
        });
    });
});

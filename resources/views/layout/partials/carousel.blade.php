<script>
    // Lightweight carousel controller shared across marketplace pages
    document.addEventListener('DOMContentLoaded', function() {
        const carousels = document.querySelectorAll('.carousel');
        carousels.forEach((carousel) => {
            const inner = carousel.querySelector('.carousel-inner');
            if (!inner) return;
            const items = Array.from(inner.querySelectorAll('.carousel-item'));
            if (items.length === 0) return;

            let idx = items.findIndex((el) => el.classList.contains('active'));
            if (idx < 0) idx = 0;

            const intervalAttr = carousel.getAttribute('data-bs-interval');
            const autoAttr = carousel.getAttribute('data-bs-ride');
            const interval = intervalAttr ? parseInt(intervalAttr, 10) : 4000;
            const autoRotate = autoAttr === 'carousel';

            const indicators = Array.from(
                carousel.querySelectorAll(
                    '.carousel-indicators [data-carousel-to], .carousel-indicators [data-bs-slide-to]'
                )
            );

            function updateActive() {
                items.forEach((it, i) => {
                    if (i === idx) {
                        it.classList.add('active');
                    } else {
                        it.classList.remove('active');
                    }
                });
                indicators.forEach((dot, i) => {
                    if (i === idx) {
                        dot.classList.add('active');
                        dot.setAttribute('aria-current', 'true');
                    } else {
                        dot.classList.remove('active');
                        dot.removeAttribute('aria-current');
                    }
                });
            }

            function next() {
                idx = (idx + 1) % items.length;
                updateActive();
            }

            function prev() {
                idx = (idx - 1 + items.length) % items.length;
                updateActive();
            }

            // Support Bootstrap-like controls
            const controls = carousel.querySelectorAll('[data-bs-slide], [data-action]');
            controls.forEach((ctrl) => {
                const action = ctrl.getAttribute('data-bs-slide') || ctrl.getAttribute(
                    'data-action');
                if (action === 'next') {
                    ctrl.addEventListener('click', (e) => {
                        e.preventDefault();
                        next();
                    });
                } else if (action === 'prev') {
                    ctrl.addEventListener('click', (e) => {
                        e.preventDefault();
                        prev();
                    });
                }
            });

            indicators.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    idx = i;
                    updateActive();
                });
            });

            updateActive();

            if (autoRotate && items.length > 1) {
                let timer = setInterval(next, isNaN(interval) ? 4000 : interval);
                carousel.addEventListener('mouseenter', () => {
                    clearInterval(timer);
                });
                carousel.addEventListener('mouseleave', () => {
                    timer = setInterval(next, isNaN(interval) ? 4000 : interval);
                });
            }
        });
    });
</script>

/**
 * Error Page Parallax Effect
 * Vanilla JS implementation for parallax mouse movement
 * Optimized with requestAnimationFrame for smooth performance
 */
(function () {
    'use strict';

    const shapes = [
        { id: 'shape1', rate: 0.2 },
        { id: 'shape2', rate: 0.4 },
        { id: 'shape3', rate: 0.12 },
        { id: 'shape4', rate: 0.3 },
        { id: 'shape5', rate: 0.2 },
        { id: 'shape6', rate: 0.25 },
        { id: 'shape7', rate: 0.19 }
    ];

    let shapeElements = [];
    let rafId = null;

    function isDesktop() {
        return window.innerWidth >= 1024; // Desktop breakpoint
    }

    function initParallax() {
        const pageSection = document.querySelector('.page-section');
        if (!pageSection) return;

        // Only initialize parallax on desktop
        if (!isDesktop()) {
            return;
        }

        // Initialize shape positions after CSS is loaded
        shapeElements = shapes.map(shape => {
            const el = document.getElementById(shape.id);
            if (!el) return null;

            const computedStyle = window.getComputedStyle(el);
            const isLeft = el.classList.contains('left');
            const isTop = el.classList.contains('top');

            // Get initial position from CSS
            let initialX = 0;
            let initialY = 0;

            if (isLeft) {
                initialX = parseFloat(computedStyle.left) || 0;
            } else {
                initialX = parseFloat(computedStyle.right) || 0;
            }

            if (isTop) {
                initialY = parseFloat(computedStyle.top) || 0;
            } else {
                initialY = parseFloat(computedStyle.bottom) || 0;
            }

            return {
                element: el,
                rate: shape.rate,
                initialX,
                initialY,
                isLeft,
                isTop
            };
        }).filter(Boolean);

        // Parallax mouse move handler with throttling
        pageSection.addEventListener('mousemove', handleMouseMove, { passive: true });
    }

    function handleMouseMove(e) {
        if (rafId) return; // Skip if already scheduled

        rafId = requestAnimationFrame(() => {
            const pageSection = document.querySelector('.page-section');
            if (!pageSection) return;

            const rect = pageSection.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const normalizedX = (x / rect.width) * 2 - 1; // -1 to 1
            const normalizedY = (y / rect.height) * 2 - 1; // -1 to 1

            const range = 400;
            const invert = true;

            shapeElements.forEach(shape => {
                const moveX = (invert ? normalizedX : -normalizedX) * range * shape.rate;
                const moveY = (invert ? normalizedY : -normalizedY) * range * shape.rate;

                if (shape.isLeft) {
                    shape.element.style.left = `${shape.initialX + moveX}px`;
                } else {
                    shape.element.style.right = `${shape.initialX - moveX}px`;
                }

                if (shape.isTop) {
                    shape.element.style.top = `${shape.initialY + moveY}px`;
                } else {
                    shape.element.style.bottom = `${shape.initialY - moveY}px`;
                }
            });

            rafId = null;
        });
    }

    function initPreloader() {
        const loaderWrapper = document.getElementById('loader-wrapper');
        if (!loaderWrapper) return;

        function hideLoader() {
            loaderWrapper.style.opacity = '0';
            loaderWrapper.style.transition = 'opacity 0.5s ease-out';
            setTimeout(() => {
                loaderWrapper.style.display = 'none';
            }, 500);
        }

        // Hide loader after page load
        if (document.readyState === 'complete') {
            setTimeout(hideLoader, 500);
        } else {
            window.addEventListener('load', () => {
                setTimeout(hideLoader, 500);
            });
        }
    }

    // Handle window resize to enable/disable parallax
    let resizeTimeout;
    function handleResize() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const pageSection = document.querySelector('.page-section');
            if (pageSection && isDesktop()) {
                // Reinitialize if switched to desktop
                if (shapeElements.length === 0) {
                    initParallax();
                }
            } else {
                // Remove event listener on mobile
                if (pageSection) {
                    pageSection.removeEventListener('mousemove', handleMouseMove);
                    shapeElements = [];
                }
            }
        }, 250);
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            initParallax();
            initPreloader();
            window.addEventListener('resize', handleResize, { passive: true });
        });
    } else {
        // DOM already loaded
        initParallax();
        initPreloader();
        window.addEventListener('resize', handleResize, { passive: true });
    }
})();


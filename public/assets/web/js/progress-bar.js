document.addEventListener("DOMContentLoaded", () => {
    "use strict";

    const progressBars = document.querySelectorAll('.ed-progressbar');
    progressBars.forEach(progressBar => {
        const targetValue = parseInt(progressBar.getAttribute('data-value'), 10);
        const progressLabel = progressBar.querySelector(".ed-progress-label");
        let currentValue = 0;

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const updateProgressBar = () => {
                        if (currentValue < targetValue) {
                            currentValue++;
                            progressBar.style.width = `${currentValue}%`;
                            progressLabel.textContent = `${currentValue}%`;
                            requestAnimationFrame(updateProgressBar);
                        }
                    };

                    updateProgressBar();
                    observer.unobserve(progressBar);
                }
            });
        });

        observer.observe(progressBar);
    });
});
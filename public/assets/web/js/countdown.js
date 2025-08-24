document.addEventListener("DOMContentLoaded", () => {
    "use strict";
    (function () {
        function countdown(countdownElem, date) {
            var originalTargetDate = new Date(date).getTime();
            var targetDate = originalTargetDate;

            function updateCountdown() {
                var now = new Date().getTime();
                var distance = targetDate - now;

                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const countdownElement = document.querySelector(countdownElem);
                if (countdownElement.querySelector(".days")) {
                    countdownElement.querySelector(".days").innerHTML = days;
                }
                countdownElement.querySelector(".hours").innerHTML = hours;
                countdownElement.querySelector(".minutes").innerHTML = minutes;
                countdownElement.querySelector(".seconds").innerHTML = seconds;

                if (distance < 0) {
                    targetDate = originalTargetDate + ((Math.floor(now / originalTargetDate) + 1) * (originalTargetDate - new Date(date).getTime()));
                    updateCountdown();
                } else {
                    setTimeout(updateCountdown, 1000);
                }
            }

            updateCountdown();
        }

        countdown(".ed-countdown", "2024-07-03T00:00:00");
    })();
});

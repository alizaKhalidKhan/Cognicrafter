// scroll.js

document.addEventListener('DOMContentLoaded', function () {
    // Function to handle smooth scrolling
    function smoothScroll(target, duration) {
        var targetElement = document.querySelector(target);
        var targetPosition = targetElement.getBoundingClientRect().top;
        var startPosition = window.pageYOffset;
        var startTime = null;

        function animation(currentTime) {
            if (startTime === null) startTime = currentTime;
            var timeElapsed = currentTime - startTime;
            var progress = timeElapsed / duration;
            var ease = Math.easeInOutQuad(progress);

            window.scrollTo(0, startPosition + targetPosition * ease);

            if (timeElapsed < duration) requestAnimationFrame(animation);
        }

        // Easing function (optional, for smooth animation)
        Math.easeInOutQuad = function (t) {
            return t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
        };

        requestAnimationFrame(animation);
    }

    // Add smooth scrolling to the "About Us" link
    var scrollToAboutUs = document.querySelector('a[href="#about-us"]');
    scrollToAboutUs.addEventListener('click', function (e) {
        e.preventDefault();
        smoothScroll('#about-us', 1000); // 1000ms (1 second) duration for the scroll
    });

    // Add smooth scrolling to the "Features" link
    var scrollToFeatures = document.querySelector('a[href="#features"]');
    scrollToFeatures.addEventListener('click', function (e) {
        e.preventDefault();
        smoothScroll('#features', 1000); // 1000ms (1 second) duration for the scroll
    });

    // Add smooth scrolling to the "Contact Us" link
    var scrollToContactUs = document.querySelector('a[href="#contact-us"]');
    scrollToContactUs.addEventListener('click', function (e) {
        e.preventDefault();
        smoothScroll('#contact-us', 1000); // 1000ms (1 second) duration for the scroll
    });
});

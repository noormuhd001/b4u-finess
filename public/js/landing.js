const landingToggle = document.querySelector('.landing-menu-toggle');
const landingNav = document.querySelector('.landing-nav');

if (landingToggle && landingNav) {
    landingToggle.addEventListener('click', function () {
        const isOpen = landingNav.classList.toggle('is-open');
        landingToggle.setAttribute('aria-expanded', String(isOpen));
        landingToggle.setAttribute('aria-label', isOpen ? 'Close navigation' : 'Open navigation');
    });

    landingNav.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
            landingNav.classList.remove('is-open');
            landingToggle.setAttribute('aria-expanded', 'false');
            landingToggle.setAttribute('aria-label', 'Open navigation');
        });
    });
}

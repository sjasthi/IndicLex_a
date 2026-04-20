
// theme for toogle_theme.js

// Set a cookie
function setCookie(n, val, days) {
    const d = new Date();
    d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = n + "=" + val + ";path=/;expires=" + d.toUTCString();
}

// Read a cookie
function readCookie(n) {
    const value = "; " + document.cookie;
    const parts = value.split("; " + n + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
    return null;
}

// Apply theme on page load
function apply(theme) {

    const toggleBtn = document.getElementById('toggleThemeBtn');
    const icon = document.getElementById('themeIcon');

    // Bootstrap 5.3 theme system
    document.documentElement.setAttribute('data-bs-theme', theme);

    // button styling
    if (toggleBtn) {
        toggleBtn.classList.remove('btn-outline-light', 'btn-outline-dark');
        toggleBtn.classList.add(theme === 'dark' ? 'btn-outline-light' : 'btn-outline-dark');
    }

    if (icon) {
        icon.className = "bi"; // reset everything

        if (theme === "dark") {
            icon.classList.add("bi-sun");   // ☀️
        } else {
            icon.classList.add("bi-moon");  // 🌙
        }
    }
}

// Init
window.addEventListener('DOMContentLoaded', function () {

    let theme = readCookie('pref_theme') || 'light';

    apply(theme);

    const toggleBtn = document.getElementById('toggleThemeBtn');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {

            theme = (theme === 'dark') ? 'light' : 'dark';

            apply(theme);

            setCookie('pref_theme', theme, 365);
        });
    }
});
// theme for toogle_theme.js

// Set a cookie
function setCookie(n, val, days) {
    const d = new Date();
    d.setTime(d.getTime() + (days*24*60*60*1000));
    document.cookie = n + "=" + val + ";path=/;expires=" + d.toUTCString();
}

// read a cookie
function readCookie(n) {
    const value = "; " + document.cookie;
    const parts = value.split("; " + n + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
    return null;
}

// Apply theme on page load
function apply(theme) {
    const body = document.body;
    const toggleBtn = document.getElementById('toggleThemeBtn');

    if (theme === 'dark') {
        body.classList.add('bg-dark','text-light');
        body.classList.remove('bg-light','text-dark');
        if (toggleBtn) {
            toggleBtn.classList.remove('btn-outline-dark');
            toggleBtn.classList.add('btn-outline-light');
        }
    } else {
        body.classList.add('bg-light','text-dark');
        body.classList.remove('bg-dark','text-light');
        if (toggleBtn) {
            toggleBtn.classList.remove('btn-outline-light');
            toggleBtn.classList.add('btn-outline-dark');
        }
    }
}

// Initialize theme
window.addEventListener('DOMContentLoaded', function() {
    let theme = readCookie('pref_theme') || 'light';
    apply(theme);

    const toggleBtn = document.getElementById('toggleThemeBtn');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            theme = (theme === 'dark') ? 'light' : 'dark';
            apply(theme);
            setCookie('pref_theme', theme, 365);
        });
    }
});
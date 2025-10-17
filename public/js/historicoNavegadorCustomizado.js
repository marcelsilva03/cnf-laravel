window.addEventListener('popstate', (ev) => {
    const previousUrl = document.referrer || '/';
    history.pushState(null, null, window.location.href);
    if (previousUrl) {
        window.location.href = previousUrl;
    }
});
window.addEventListener('DOMContentLoaded', () => {
    history.pushState(null, null, window.location.href);
});

<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#0d6efd">

<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js');
    });
}
</script>

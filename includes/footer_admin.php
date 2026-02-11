    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> - Menma Shop - Tous droits réservés - By Menma</p>
    </footer>

<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('/admin/service-worker.js', { scope: '/admin/' })
      .then(function(reg) { 
        console.log('Admin ServiceWorker registered', reg); 
        
        // Check for updates on page load
        reg.update();

        // If a new SW is waiting, skip waiting (should be handled by SW itself, but good to have)
        if (reg.waiting) {
            reg.waiting.postMessage({ type: 'SKIP_WAITING' });
        }
      })
      .catch(function(err) { console.warn('Admin ServiceWorker registration failed', err); });

    // Force reload when a new SW takes control
    navigator.serviceWorker.addEventListener('controllerchange', () => {
        console.log('New ServiceWorker activated. Reloading...');
        window.location.reload();
    });
  });
}
</script>
</body>
</html>
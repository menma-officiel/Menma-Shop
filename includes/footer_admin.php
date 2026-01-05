    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> - Menma Shop - Tous droits réservés - By Menma</p>
    </footer>

<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('/admin/service-worker.js', { scope: '/admin/' })
      .then(function(reg) { console.log('Admin ServiceWorker registered', reg); })
      .catch(function(err) { console.warn('Admin ServiceWorker registration failed', err); });
  });
}
</script>
</body>
</html>
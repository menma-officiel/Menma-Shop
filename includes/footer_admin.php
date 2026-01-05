    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> - Menma Shop - Tous droits réservés - By Menma</p>
    </footer>

<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('/service-worker.js')
      .then(function(reg) { console.log('ServiceWorker registered', reg); })
      .catch(function(err) { console.warn('ServiceWorker registration failed', err); });
  });
}
</script>
</body>
</html>
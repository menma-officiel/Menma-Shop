const CACHE_NAME = 'menma-shop-v1';
const PRECACHE_URLS = [
  '/',
  '/index.php',
  '/admin/index.php',
  '/admin/login.php',
  '/admin/stats.php',
  '/assets/css/style.css',
  '/assets/css/admin.css',
  '/assets/css/login.css',
  '/assets/js/login.js',
  '/offline.html'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(PRECACHE_URLS))
  );
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => Promise.all(
      keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))
    ))
  );
  self.clients.claim();
});

self.addEventListener('fetch', event => {
  if (event.request.method !== 'GET') return;

  event.respondWith(
    caches.match(event.request).then(cached => {
      if (cached) return cached;
      return fetch(event.request).then(response => {
        // Put a copy in cache
        return caches.open(CACHE_NAME).then(cache => {
          // clone response because response is a stream
          cache.put(event.request, response.clone());
          return response;
        });
      }).catch(() => caches.match('/offline.html'));
    })
  );
});
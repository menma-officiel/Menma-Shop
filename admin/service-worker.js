const CACHE_NAME = 'menma-admin-v1';
const PRECACHE_URLS = [
  '/admin/index.php',
  '/admin/login.php',
  '/admin/stats.php',
  '/admin/offline.html',
  '/assets/css/admin.css',
  '/assets/css/login.css',
  '/assets/js/login.js'
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

  // Only handle requests inside /admin/ scope
  const url = new URL(event.request.url);
  if (!url.pathname.startsWith('/admin/') && !url.pathname.startsWith('/assets/')) return;

  event.respondWith(
    caches.match(event.request).then(cached => {
      if (cached) return cached;
      return fetch(event.request).then(response => {
        return caches.open(CACHE_NAME).then(cache => {
          cache.put(event.request, response.clone());
          return response;
        });
      }).catch(() => caches.match('/admin/offline.html'));
    })
  );
});
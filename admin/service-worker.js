const CACHE_NAME = 'menma-admin-v4';
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

  const url = new URL(event.request.url);

  // Stratégie "Network First" pour les pages HTML (navigation) dans /admin/
  // On tente le réseau, si ça échoue on prend le cache, sinon offline.html
  if (event.request.mode === 'navigate' && url.pathname.startsWith('/admin/')) {
    event.respondWith(
      fetch(event.request)
        .then(response => {
          // Si on a une réponse valide, on la met en cache et on la retourne
          if (!response || response.status !== 200 || response.type !== 'basic') {
            return response;
          }
          const responseToCache = response.clone();
          caches.open(CACHE_NAME).then(cache => {
            cache.put(event.request, responseToCache);
          });
          return response;
        })
        .catch(() => {
          // Si réseau échoue, on regarde le cache
          return caches.match(event.request).then(response => {
            return response || caches.match('/admin/offline.html');
          });
        })
    );
    return;
  }

  // Pour les autres requêtes (CSS, JS, images) dans /admin/ ou /assets/, on garde Cache First ou Stale-While-Revalidate
  // Ici on reste sur un Cache First simple pour la rapidité des assets, mais on pourrait changer si besoin.
  if (url.pathname.startsWith('/admin/') || url.pathname.startsWith('/assets/')) {
    event.respondWith(
      caches.match(event.request).then(cached => {
        if (cached) return cached;
        return fetch(event.request).then(response => {
          return caches.open(CACHE_NAME).then(cache => {
            cache.put(event.request, response.clone());
            return response;
          });
        }); // Pas de fallback offline pour les assets individuels, ça pourrait casser le style de offline.html lui-même s'il manque
      })
    );
  }
});
/**
 * MenúVital — Service Worker
 * Cachea los estáticos para que la app cargue rápido y funcione sin conexión.
 * Las páginas y llamadas a /api/ siempre van primero a la red (datos frescos);
 * si no hay conexión, se sirve la última copia guardada cuando exista.
 */
const CACHE_NAME = 'menuvital-v1';
const STATIC_ASSETS = [
  '/assets/css/style.css',
  '/assets/js/app.js',
  '/assets/img/icon-192.png',
  '/assets/img/icon-512.png',
];

self.addEventListener('install', (event) => {
  event.waitUntil(caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS)));
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => Promise.all(
      keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k))
    ))
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);
  if (event.request.method !== 'GET' || url.origin !== location.origin) {
    return;
  }

  const isStatic = STATIC_ASSETS.some((a) => url.pathname === a);

  if (isStatic) {
    event.respondWith(
      caches.match(event.request).then((cached) => cached || fetch(event.request))
    );
    return;
  }

  // Páginas y APIs: red primero, caché de respaldo si falla
  event.respondWith(
    fetch(event.request)
      .then((res) => {
        const copy = res.clone();
        caches.open(CACHE_NAME).then((cache) => cache.put(event.request, copy));
        return res;
      })
      .catch(() => caches.match(event.request))
  );
});

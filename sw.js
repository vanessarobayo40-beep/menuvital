/**
 * MenúVital — Service Worker
 * Cachea los estáticos para que la app cargue rápido y funcione sin conexión.
 * Las páginas y llamadas a /api/ siempre van primero a la red (datos frescos);
 * si no hay conexión, se sirve la última copia guardada cuando exista.
 */
const CACHE_NAME = 'menuvital-v8';
const STATIC_ASSETS = [
  '/assets/css/style.css',
  '/assets/js/app.js',
  '/assets/img/icon-192-v3.png',
  '/assets/img/icon-512-v3.png',
  '/offline.html',
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
    // ignoreSearch es la parte que faltaba: style.css/app.js se piden con
    // ?v=<versión> para romper caché en cada deploy, pero se precachearon
    // en install() SIN ese query — sin ignoreSearch, caches.match() nunca
    // encontraba la entrada guardada y la app quedaba sin CSS/JS offline.
    event.respondWith(
      caches.match(event.request, { ignoreSearch: true }).then((cached) => {
        if (cached) return cached;
        return fetch(event.request).then((res) => {
          const copy = res.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(event.request, copy));
          return res;
        });
      })
    );
    return;
  }

  // Páginas y APIs: red primero, caché de respaldo si falla. Si es una
  // navegación de página (no una llamada a /api/) y tampoco hay copia en
  // caché, se muestra la página de sin-conexión en vez del error genérico
  // del navegador.
  event.respondWith(
    fetch(event.request)
      .then((res) => {
        const copy = res.clone();
        caches.open(CACHE_NAME).then((cache) => cache.put(event.request, copy));
        return res;
      })
      .catch(() => caches.match(event.request).then((cached) => {
        if (cached) return cached;
        if (event.request.mode === 'navigate') {
          return caches.match('/offline.html');
        }
        return new Response('', { status: 503, statusText: 'Sin conexión' });
      }))
  );
});

// ---------- Notificaciones push (recordatorios de agua y de mercado) ----------
// Los push que enviamos no traen contenido (por seguridad y simplicidad del
// servidor): el mismo cron de cada 2h decide qué mostrar según el día en que
// llega. Entre semana, recordatorio de agua; sábado y domingo (día de mercado),
// recordatorio de la lista de compras — reutilizando la misma suscripción.
self.addEventListener('push', (event) => {
  const day = new Date().getDay(); // 0 = domingo, 6 = sábado
  const isShoppingDay = day === 0 || day === 6;

  const notification = isShoppingDay
    ? {
        title: 'MenúVital 🛒',
        body: 'Antes de ir al mercado, revisa tu lista de compras de la semana — ya la calculamos con tu menú.',
        tag: 'shopping-reminder',
      }
    : {
        title: 'MenúVital 💧',
        body: 'Hora de tomar un vaso de agua. ¡Tu cuerpo te lo agradece!',
        tag: 'water-reminder',
      };

  event.waitUntil(
    self.registration.showNotification(notification.title, {
      body: notification.body,
      icon: '/assets/img/icon-192-v3.png',
      badge: '/assets/img/icon-192-v3.png',
      tag: notification.tag,
      renotify: true,
    })
  );
});

self.addEventListener('notificationclick', (event) => {
  const targetPath = event.notification.tag === 'shopping-reminder' ? '/app/mercado.php' : '/app/progreso.php';
  event.notification.close();
  event.waitUntil(
    self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clients) => {
      for (const client of clients) {
        if (client.url.includes('/app/') && 'focus' in client) {
          return client.focus();
        }
      }
      if (self.clients.openWindow) {
        return self.clients.openWindow(targetPath);
      }
    })
  );
});

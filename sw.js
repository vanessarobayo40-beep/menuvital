/**
 * MenúVital — Service Worker
 * Cachea los estáticos para que la app cargue rápido y funcione sin conexión.
 * Las páginas y llamadas a /api/ siempre van primero a la red (datos frescos);
 * si no hay conexión, se sirve la última copia guardada cuando exista.
 */
const CACHE_NAME = 'menuvital-v3';
const STATIC_ASSETS = [
  '/assets/css/style.css',
  '/assets/js/app.js',
  '/assets/img/icon-192-v3.png',
  '/assets/img/icon-512-v3.png',
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

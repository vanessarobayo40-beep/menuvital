/**
 * MenúVital — Utilidades JS compartidas
 */

const MV = (() => {
  function toast(message, isError = false) {
    let el = document.getElementById('toast');
    if (!el) {
      el = document.createElement('div');
      el.id = 'toast';
      document.body.appendChild(el);
    }
    el.textContent = message;
    el.className = isError ? 'error' : '';
    requestAnimationFrame(() => el.classList.add('show'));
    clearTimeout(el._timer);
    el._timer = setTimeout(() => el.classList.remove('show'), 3000);
  }

  function csrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.content : '';
  }

  async function api(url, options = {}) {
    const isFormData = options.body instanceof FormData;
    const baseHeaders = Object.assign(
      { 'X-CSRF-Token': csrfToken() },
      isFormData ? {} : { 'Content-Type': 'application/json' },
      options.headers || {}
    );
    const opts = Object.assign({ method: 'GET', credentials: 'same-origin' }, options, { headers: baseHeaders });
    if (!isFormData && opts.body && typeof opts.body !== 'string') {
      opts.body = JSON.stringify(opts.body);
    }
    let res, data;
    try {
      res = await fetch(url, opts);
    } catch (e) {
      throw new Error('No hay conexión a internet. Intenta de nuevo.');
    }
    try {
      data = await res.json();
    } catch (e) {
      data = {};
    }
    if (!res.ok) {
      if (res.status === 401) {
        window.location.href = '/login.php';
        throw new Error('Sesión expirada');
      }
      throw new Error(data.error || 'Ocurrió un error. Intenta de nuevo.');
    }
    return data;
  }

  function debounce(fn, wait = 1500) {
    let t;
    return (...args) => {
      clearTimeout(t);
      t = setTimeout(() => fn(...args), wait);
    };
  }

  function saveLocal(key, value) {
    try { localStorage.setItem('mv_' + key, JSON.stringify(value)); } catch (e) {}
  }

  function loadLocal(key, fallback = null) {
    try {
      const raw = localStorage.getItem('mv_' + key);
      return raw ? JSON.parse(raw) : fallback;
    } catch (e) { return fallback; }
  }

  // Evita que el botón "atrás" del celular saque de la app (SPA-like within pages)
  function lockBackButton() {
    if (!history.state || !history.state.mvLocked) {
      history.pushState({ mvLocked: true }, '');
    }
    window.addEventListener('popstate', () => {
      history.pushState({ mvLocked: true }, '');
    });
  }

  // ---------- Instalación de la app (PWA), robusta y multiplataforma ----------
  const install = (() => {
    let deferred = null;

    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      deferred = e;
      document.dispatchEvent(new CustomEvent('mv-installable'));
    });
    window.addEventListener('appinstalled', () => {
      deferred = null;
      document.dispatchEvent(new CustomEvent('mv-installed'));
    });

    function isStandalone() {
      return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
    }

    function platform() {
      const ua = navigator.userAgent || '';
      if (/iphone|ipad|ipod/i.test(ua)) return 'ios';
      if (/android/i.test(ua)) return 'android';
      return 'desktop';
    }

    // Instrucciones manuales cuando el navegador no ofrece el botón automático
    function manualSteps() {
      switch (platform()) {
        case 'ios':
          return 'En tu iPhone: toca el botón <strong>Compartir</strong> (el cuadro con la flecha ↑) en la barra de Safari y elige <strong>“Agregar a inicio”</strong>.';
        case 'android':
          return 'En tu celular: abre el menú <strong>⋮</strong> (arriba a la derecha del navegador) y toca <strong>“Instalar app”</strong> o <strong>“Agregar a pantalla de inicio”</strong>.';
        default:
          return 'En tu computador: toca el ícono de instalar <strong>⊕</strong> en la barra de direcciones, o abre el menú <strong>⋮</strong> del navegador y elige <strong>“Instalar MenúVital”</strong>.';
      }
    }

    // Intenta instalar. Devuelve: 'installed' | 'dismissed' | 'manual'
    async function trigger() {
      if (deferred) {
        deferred.prompt();
        const choice = await deferred.userChoice;
        deferred = null;
        return choice.outcome === 'accepted' ? 'installed' : 'dismissed';
      }
      return 'manual';
    }

    return { trigger, isStandalone, platform, manualSteps, hasPrompt: () => !!deferred };
  })();

  // ---------- Notificaciones push (recordatorios de agua) ----------
  const push = (() => {
    function isSupported() {
      return 'serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window;
    }

    function urlBase64ToUint8Array(base64url) {
      const padding = '='.repeat((4 - (base64url.length % 4)) % 4);
      const base64 = (base64url + padding).replace(/-/g, '+').replace(/_/g, '/');
      const raw = atob(base64);
      const arr = new Uint8Array(raw.length);
      for (let i = 0; i < raw.length; i++) arr[i] = raw.charCodeAt(i);
      return arr;
    }

    async function getSubscription() {
      if (!isSupported()) return null;
      const reg = await navigator.serviceWorker.ready;
      return reg.pushManager.getSubscription();
    }

    async function isSubscribed() {
      const sub = await getSubscription();
      return !!sub;
    }

    // Devuelve: 'subscribed' | 'denied' | 'unsupported' | 'error'
    async function subscribe() {
      if (!isSupported()) return 'unsupported';
      if (Notification.permission === 'denied') return 'denied';

      const permission = await Notification.requestPermission();
      if (permission !== 'granted') return 'denied';

      try {
        const { key } = await api('/api/push.php?action=vapid_key');
        if (!key) return 'error';
        const reg = await navigator.serviceWorker.ready;
        let sub = await reg.pushManager.getSubscription();
        if (!sub) {
          sub = await reg.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(key),
          });
        }
        const json = sub.toJSON();
        await api('/api/push.php?action=subscribe', {
          method: 'POST',
          body: { endpoint: json.endpoint, p256dh: json.keys.p256dh, auth: json.keys.auth },
        });
        return 'subscribed';
      } catch (e) {
        return 'error';
      }
    }

    async function unsubscribe() {
      const sub = await getSubscription();
      if (!sub) return true;
      const endpoint = sub.endpoint;
      try { await sub.unsubscribe(); } catch (e) {}
      try { await api('/api/push.php?action=unsubscribe', { method: 'POST', body: { endpoint } }); } catch (e) {}
      return true;
    }

    return { isSupported, isSubscribed, subscribe, unsubscribe };
  })();

  return { toast, api, debounce, saveLocal, loadLocal, csrfToken, lockBackButton, install, push };
})();

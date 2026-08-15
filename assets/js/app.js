/**
 * MenúVital — Utilidades JS compartidas
 */

/**
 * Escapa texto para insertarlo en HTML de forma segura, tanto en contexto de
 * texto como de ATRIBUTO (`data-item="${escapeHtml(x)}"`, `alt="..."`, etc).
 * OJO: la versión anterior (que vivía duplicada en cada página) usaba un
 * <div>.textContent -> innerHTML, que escapa &/</> pero NO escapa comillas —
 * un nombre de receta o de ingrediente con una comilla doble podía cerrar el
 * atributo y ejecutar HTML/JS propio (XSS). Esta sí escapa las 5 entidades.
 */
function escapeHtml(s) {
  return String(s ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

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

  // ---------- Modo Cocina: overlay paso a paso, se puede abrir desde cualquier página ----------
  function cookMode(meal) {
    if (!meal || !meal.steps || !meal.steps.length) {
      toast('Esta receta no tiene pasos para el Modo Cocina.', true);
      return;
    }
    const esc = (s) => { const d = document.createElement('div'); d.textContent = s ?? ''; return d.innerHTML; };
    let step = 0;
    let wakeLock = null;
    let doneClicked = false;

    const overlay = document.createElement('div');
    overlay.id = 'cook-mode-overlay';
    overlay.style.cssText = 'position:fixed;inset:0;background:#fff;z-index:90;display:flex;flex-direction:column;';
    overlay.innerHTML = `
      <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid var(--border);">
        <button type="button" id="cm-close" aria-label="Cerrar" style="background:var(--surface-2);border:none;width:34px;height:34px;border-radius:50%;font-size:17px;color:var(--t2);">×</button>
        <span style="font-weight:700;font-size:13px;color:var(--t2);">${esc(meal.name)}</span>
        <button type="button" id="cm-ingredients-toggle" style="background:none;border:none;font-size:12px;font-weight:600;color:var(--green-dark);">Ingredientes</button>
      </div>
      <div id="cm-ingredients" style="display:none;padding:14px 18px;background:var(--surface);border-bottom:1px solid var(--border);max-height:35vh;overflow-y:auto;">
        <ul style="margin:0;padding-left:20px;font-size:14px;">
          ${meal.ingredients.map(i => `<li>${esc(i.item)}${i.qty ? ' — ' + esc(i.qty) : ''}</li>`).join('')}
        </ul>
      </div>
      <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:28px 24px;text-align:center;">
        <p id="cm-counter" class="muted" style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:0.4px;margin:0 0 18px;"></p>
        <p id="cm-step-text" style="font-size:22px;line-height:1.5;font-weight:600;color:var(--t1);margin:0;max-width:440px;"></p>
      </div>
      <div style="display:flex;gap:10px;padding:16px 18px calc(16px + env(safe-area-inset-bottom));border-top:1px solid var(--border);">
        <button type="button" id="cm-prev" class="btn btn-secondary" style="flex:1;">← Atrás</button>
        <button type="button" id="cm-next" class="btn btn-primary" style="flex:2;">Siguiente →</button>
      </div>`;
    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';

    if ('wakeLock' in navigator) {
      navigator.wakeLock.request('screen').then(lock => { wakeLock = lock; }).catch(() => {});
    }

    function render() {
      const last = step === meal.steps.length - 1;
      overlay.querySelector('#cm-counter').textContent = `Paso ${step + 1} de ${meal.steps.length}`;
      overlay.querySelector('#cm-step-text').textContent = meal.steps[step];
      overlay.querySelector('#cm-prev').disabled = step === 0;
      const nextBtn = overlay.querySelector('#cm-next');
      nextBtn.textContent = last ? '✅ ¡Listo, ya la hice!' : 'Siguiente →';
      nextBtn.className = 'btn btn-primary';
      nextBtn.style.flex = last ? '1' : '2';
      overlay.querySelector('#cm-prev').style.display = last ? 'none' : 'inline-flex';
    }

    function close() {
      if (wakeLock) { wakeLock.release().catch(() => {}); wakeLock = null; }
      document.body.style.overflow = '';
      overlay.remove();
    }

    overlay.querySelector('#cm-close').addEventListener('click', close);
    overlay.querySelector('#cm-ingredients-toggle').addEventListener('click', () => {
      const el = overlay.querySelector('#cm-ingredients');
      el.style.display = el.style.display === 'none' ? 'block' : 'none';
    });
    overlay.querySelector('#cm-prev').addEventListener('click', () => {
      if (step > 0) { step--; render(); }
    });
    overlay.querySelector('#cm-next').addEventListener('click', async () => {
      if (step < meal.steps.length - 1) { step++; render(); return; }
      if (doneClicked) { close(); return; }
      const defaultPortions = (meal.kcal_total && meal.kcal_porcion) ? Math.round(meal.kcal_total / meal.kcal_porcion) : 1;
      const portions = await askPortions(defaultPortions);
      if (portions === null) return;
      doneClicked = true;
      const btn = overlay.querySelector('#cm-next');
      btn.disabled = true;
      btn.textContent = 'Guardando...';
      try {
        const res = meal.entry_id
          ? await api('/api/menu.php?action=done', { method: 'POST', body: { entry_id: meal.entry_id, portions } })
          : await api('/api/pantry.php?action=consume_recipe', { method: 'POST', body: { recipe_id: meal.id, portions } });
        toast(res.consumed && res.consumed.length ? `Descontamos de tu despensa: ${res.consumed.join(', ')}` : '¡Buen provecho! 🍽️');
        document.dispatchEvent(new CustomEvent('mv-meal-cooked', { detail: { recipeId: meal.id, entryId: meal.entry_id || null } }));
      } catch (err) {
        toast(err.message, true);
      }
      close();
    });

    render();
  }

  // ---------- Modo claro / oscuro ----------
  const theme = (() => {
    // null/ausente = "automático" (sigue la preferencia del sistema operativo)
    function get() {
      try { return localStorage.getItem('mv_theme'); } catch (e) { return null; }
    }
    function set(value) {
      try {
        if (!value || value === 'auto') {
          localStorage.removeItem('mv_theme');
          document.documentElement.removeAttribute('data-theme');
        } else {
          localStorage.setItem('mv_theme', value);
          document.documentElement.setAttribute('data-theme', value);
        }
      } catch (e) {}
    }
    function current() {
      const saved = get();
      if (saved === 'light' || saved === 'dark') return saved;
      return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    return { get, set, current };
  })();

  // ---------- Zoom de fotos: toca cualquier foto de receta para verla en grande ----------
  // Soporta pellizcar para acercar (pinch), doble toque, arrastrar cuando está
  // ampliada, y rueda del mouse en computador. Un solo overlay compartido por
  // toda la app, creado la primera vez que se usa.
  const imageZoom = (() => {
    let overlay = null, imgEl = null, scale = 1, panX = 0, panY = 0;
    const pointers = new Map();
    let startDist = 0, startScale = 1, startMid = { x: 0, y: 0 }, startPan = { x: 0, y: 0 };
    let dragging = false, dragStart = { x: 0, y: 0 };

    function dist(a, b) { return Math.hypot(a.x - b.x, a.y - b.y); }
    function mid(a, b) { return { x: (a.x + b.x) / 2, y: (a.y + b.y) / 2 }; }

    function applyTransform() {
      imgEl.style.transform = `translate(${panX}px, ${panY}px) scale(${scale})`;
    }

    function clampPan() {
      const maxX = (imgEl.clientWidth * (scale - 1)) / 2 + 60;
      const maxY = (imgEl.clientHeight * (scale - 1)) / 2 + 60;
      panX = Math.max(-maxX, Math.min(maxX, panX));
      panY = Math.max(-maxY, Math.min(maxY, panY));
    }

    function resetZoom(animate) {
      scale = 1; panX = 0; panY = 0;
      imgEl.style.transition = animate ? 'transform 0.2s ease' : 'none';
      applyTransform();
    }

    function ensureOverlay() {
      if (overlay) return;
      overlay = document.createElement('div');
      overlay.id = 'mv-zoom-overlay';
      overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.92);z-index:95;display:none;align-items:center;justify-content:center;overflow:hidden;touch-action:none;';
      overlay.innerHTML = `
        <button type="button" id="mv-zoom-close" aria-label="Cerrar" style="position:absolute;top:calc(14px + env(safe-area-inset-top));right:14px;background:rgba(255,255,255,0.15);color:#fff;border:none;width:38px;height:38px;border-radius:50%;font-size:20px;z-index:2;">×</button>
        <p style="position:absolute;bottom:calc(16px + env(safe-area-inset-bottom));left:0;right:0;text-align:center;color:rgba(255,255,255,0.7);font-size:12px;margin:0;pointer-events:none;">Pellizca o doble toque para acercar</p>
        <img id="mv-zoom-img" src="" alt="" style="max-width:100%;max-height:100%;user-select:none;-webkit-user-drag:none;will-change:transform;">`;
      document.body.appendChild(overlay);
      imgEl = overlay.querySelector('#mv-zoom-img');

      overlay.querySelector('#mv-zoom-close').addEventListener('click', close);
      overlay.addEventListener('click', (e) => { if (e.target === overlay) close(); });
      overlay.addEventListener('pointerdown', onPointerDown);
      overlay.addEventListener('pointermove', onPointerMove);
      overlay.addEventListener('pointerup', onPointerUp);
      overlay.addEventListener('pointercancel', onPointerUp);
      overlay.addEventListener('wheel', onWheel, { passive: false });
      imgEl.addEventListener('dblclick', onDblClick);
    }

    function open(src, alt) {
      if (!src) return;
      ensureOverlay();
      imgEl.src = src;
      imgEl.alt = alt || '';
      resetZoom(false);
      overlay.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function close() {
      if (!overlay || overlay.style.display === 'none') return;
      overlay.style.display = 'none';
      document.body.style.overflow = '';
      pointers.clear();
      dragging = false;
    }

    function onPointerDown(e) {
      overlay.setPointerCapture && overlay.setPointerCapture(e.pointerId);
      pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });
      imgEl.style.transition = 'none';
      if (pointers.size === 2) {
        const [a, b] = [...pointers.values()];
        startDist = dist(a, b);
        startScale = scale;
        startMid = mid(a, b);
        startPan = { x: panX, y: panY };
      } else if (pointers.size === 1 && scale > 1) {
        dragging = true;
        dragStart = { x: e.clientX - panX, y: e.clientY - panY };
      }
    }

    function onPointerMove(e) {
      if (!pointers.has(e.pointerId)) return;
      pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });
      if (pointers.size === 2) {
        const [a, b] = [...pointers.values()];
        const newDist = dist(a, b) || 1;
        scale = Math.max(1, Math.min(4, startScale * (newDist / (startDist || 1))));
        const m = mid(a, b);
        panX = startPan.x + (m.x - startMid.x);
        panY = startPan.y + (m.y - startMid.y);
        clampPan();
        applyTransform();
      } else if (dragging && pointers.size === 1) {
        panX = e.clientX - dragStart.x;
        panY = e.clientY - dragStart.y;
        clampPan();
        applyTransform();
      }
    }

    function onPointerUp(e) {
      pointers.delete(e.pointerId);
      dragging = false;
      imgEl.style.transition = 'transform 0.15s ease';
      if (scale <= 1.02) resetZoom(true);
    }

    function onWheel(e) {
      e.preventDefault();
      scale = Math.max(1, Math.min(4, scale - e.deltaY * 0.0015));
      if (scale <= 1.02) { resetZoom(false); return; }
      clampPan();
      imgEl.style.transition = 'none';
      applyTransform();
    }

    function onDblClick() {
      imgEl.style.transition = 'transform 0.2s ease';
      if (scale > 1) {
        scale = 1; panX = 0; panY = 0;
      } else {
        scale = 2.2;
      }
      applyTransform();
    }

    return { open, close };
  })();

  /** Vuelve una <img> existente "zoomeable": tocarla la abre en grande. Se le
   * agrega un cursor de lupa como pista visual; no cambia el layout. */
  function makeZoomable(imgEl) {
    if (!imgEl || imgEl.dataset.mvZoomBound) return;
    imgEl.dataset.mvZoomBound = '1';
    imgEl.style.cursor = 'zoom-in';
    imgEl.addEventListener('click', () => imageZoom.open(imgEl.currentSrc || imgEl.src, imgEl.alt));
  }

  /** Pregunta cuántas porciones se cocinaron, para descontar la despensa exacta. */
  function askPortions(defaultN = 1) {
    return new Promise((resolve) => {
      const old = document.getElementById('mv-portions-backdrop');
      if (old) old.remove();
      const backdrop = document.createElement('div');
      backdrop.id = 'mv-portions-backdrop';
      backdrop.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:90;display:flex;align-items:flex-end;justify-content:center;';
      backdrop.innerHTML = `
        <div class="card" style="width:100%;max-width:480px;border-radius:20px 20px 0 0;padding:20px;">
          <h3 style="margin:0 0 4px;">¿Cuántas porciones hiciste?</h3>
          <p class="muted" style="margin:0 0 16px;font-size:13px;">Así descontamos justo lo que usaste de tu despensa.</p>
          <div style="display:flex;align-items:center;gap:14px;justify-content:center;margin-bottom:18px;">
            <button type="button" id="mv-portions-minus" class="btn btn-secondary" style="width:44px;height:44px;padding:0;font-size:20px;">−</button>
            <input type="number" id="mv-portions-input" min="1" max="50" value="${Math.max(1, defaultN)}" style="width:70px;text-align:center;font-size:20px;font-weight:700;border:1.5px solid var(--border);border-radius:var(--radius-sm);padding:8px;background:var(--card-bg);color:var(--t1);">
            <button type="button" id="mv-portions-plus" class="btn btn-secondary" style="width:44px;height:44px;padding:0;font-size:20px;">+</button>
          </div>
          <button type="button" id="mv-portions-confirm" class="btn btn-primary btn-block">Listo, ya la hice</button>
          <button type="button" id="mv-portions-cancel" class="btn btn-secondary btn-block" style="margin-top:8px;">Cancelar</button>
        </div>`;
      document.body.appendChild(backdrop);
      const input = backdrop.querySelector('#mv-portions-input');
      const finish = (value) => { backdrop.remove(); resolve(value); };
      backdrop.querySelector('#mv-portions-minus').addEventListener('click', () => {
        input.value = Math.max(1, (parseInt(input.value, 10) || 1) - 1);
      });
      backdrop.querySelector('#mv-portions-plus').addEventListener('click', () => {
        input.value = Math.min(50, (parseInt(input.value, 10) || 1) + 1);
      });
      backdrop.querySelector('#mv-portions-confirm').addEventListener('click', () => {
        finish(Math.max(1, Math.min(50, parseInt(input.value, 10) || 1)));
      });
      backdrop.querySelector('#mv-portions-cancel').addEventListener('click', () => finish(null));
      backdrop.addEventListener('click', (e) => { if (e.target === backdrop) finish(null); });
    });
  }

  /**
   * Confirmación con ventana propia (más confiable que confirm() nativo, que
   * en apps instaladas en el celular a veces no aparece o se comporta raro).
   */
  function confirmDialog(message, opts = {}) {
    return new Promise((resolve) => {
      const old = document.getElementById('mv-confirm-backdrop');
      if (old) old.remove();
      const backdrop = document.createElement('div');
      backdrop.id = 'mv-confirm-backdrop';
      backdrop.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:95;display:flex;align-items:flex-end;justify-content:center;';
      backdrop.innerHTML = `
        <div class="card" style="width:100%;max-width:480px;border-radius:20px 20px 0 0;padding:20px;">
          <p style="margin:0 0 18px;font-size:15px;line-height:1.4;">${message}</p>
          <button type="button" id="mv-confirm-ok" class="btn btn-primary btn-block">${opts.confirmText || 'Sí, continuar'}</button>
          <button type="button" id="mv-confirm-cancel" class="btn btn-secondary btn-block" style="margin-top:8px;">${opts.cancelText || 'Cancelar'}</button>
        </div>`;
      document.body.appendChild(backdrop);
      const finish = (value) => { backdrop.remove(); resolve(value); };
      backdrop.querySelector('#mv-confirm-ok').addEventListener('click', () => finish(true));
      backdrop.querySelector('#mv-confirm-cancel').addEventListener('click', () => finish(false));
      backdrop.addEventListener('click', (e) => { if (e.target === backdrop) finish(false); });
    });
  }

  return { toast, api, debounce, saveLocal, loadLocal, csrfToken, lockBackButton, install, push, cookMode, theme, askPortions, imageZoom, makeZoomable, confirmDialog };
})();

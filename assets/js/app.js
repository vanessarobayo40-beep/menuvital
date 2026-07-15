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
    const opts = Object.assign({
      method: 'GET',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken() },
      credentials: 'same-origin',
    }, options);
    if (opts.body && typeof opts.body !== 'string') {
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

  return { toast, api, debounce, saveLocal, loadLocal, csrfToken, lockBackButton };
})();

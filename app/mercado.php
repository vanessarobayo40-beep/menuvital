<?php
require_once __DIR__ . '/../includes/auth.php';
secure_session_start();
send_security_headers();
$user = require_login_page();
$PAGE_TITLE = 'Mercado';
$ACTIVE_NAV = 'mercado';
require __DIR__ . '/../includes/layout_top.php';
?>

<h2 style="margin-bottom:4px;">Mi mercado</h2>
<p class="muted" style="margin-top:0;font-size:14px;">Escribe lo que tienes disponible en casa. Con eso armamos tu menú.</p>

<div class="tabs">
  <button class="active" data-tab="tab-despensa">Mi despensa</button>
  <button data-tab="tab-compras">Lista de compras</button>
</div>

<div id="tab-despensa">
  <div class="autocomplete-wrap field">
    <input type="text" id="pantry-input" placeholder="Ej: pollo, arroz, tomate..." autocomplete="off">
    <div id="autocomplete-list" class="autocomplete-list" style="display:none;"></div>
  </div>

  <div style="display:flex;gap:10px;margin-bottom:16px;">
    <button type="button" id="btn-voice" class="btn btn-secondary btn-sm" style="flex:1;">🎤 Por voz</button>
    <button type="button" id="btn-photo" class="btn btn-secondary btn-sm" style="flex:1;">📷 Foto de factura</button>
    <input type="file" id="photo-input" accept="image/*" capture="environment" style="display:none;">
  </div>

  <div id="voice-recording" class="card-soft" style="display:none;margin-bottom:16px;text-align:center;">
    <p style="margin:0 0 8px;font-size:14px;">🔴 Grabando... di los ingredientes que tienes</p>
    <p class="muted" style="margin:0 0 12px;font-size:22px;font-weight:700;color:var(--t1);" id="voice-timer">0:00</p>
    <button type="button" id="btn-voice-stop" class="btn btn-primary btn-sm">Detener y enviar</button>
  </div>

  <div id="scan-loading" class="card-soft" style="display:none;margin-bottom:16px;text-align:center;">
    <div class="spinner dark" style="display:inline-block;margin-bottom:8px;"></div>
    <p style="margin:0;font-size:13px;" id="scan-loading-text">Analizando...</p>
  </div>

  <div class="chips" id="pantry-chips" style="margin-bottom:16px;"></div>

  <div id="pantry-empty" class="empty-state" style="display:none;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 6h15l-1.5 8.5a2 2 0 0 1-2 1.5H8.5a2 2 0 0 1-2-1.5L4.5 3H2"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg>
    <p>Aún no has agregado ingredientes.</p>
  </div>

  <button id="btn-clear" class="btn btn-danger btn-sm" style="display:none;">Vaciar mi despensa</button>
</div>

<div id="tab-compras" style="display:none;">
  <p class="muted" style="font-size:13px;margin-top:0;">Esto es lo que necesitas comprar para completar tu plan de la semana (o del día, si aún no generas la semana).</p>
  <div id="shopping-list"></div>
  <div id="shopping-empty" class="empty-state" style="display:none;">
    <p>Genera tu menú del día o de la semana para ver tu lista de compras.</p>
    <a href="/app/plan.php" class="btn btn-primary btn-sm">Ver plan semanal</a>
  </div>
</div>

<div id="review-backdrop" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:60;align-items:flex-end;justify-content:center;">
  <div style="background:#fff;border-radius:20px 20px 0 0;padding:20px;width:100%;max-width:520px;max-height:80vh;overflow-y:auto;">
    <h3 style="margin:0 0 4px;font-size:16px;">Esto fue lo que encontramos</h3>
    <p class="muted" style="margin:0 0 6px;font-size:13px;">Toca un ingrediente para quitarlo si no es correcto.</p>
    <p id="review-transcript" class="muted" style="display:none;margin:0 0 10px;font-size:12px;font-style:italic;"></p>
    <div class="chips" id="review-chips" style="margin-bottom:18px;"></div>
    <div style="display:flex;gap:10px;">
      <button type="button" id="review-cancel" class="btn btn-secondary" style="flex:1;">Cancelar</button>
      <button type="button" id="review-confirm" class="btn btn-primary" style="flex:2;">Agregar a mi despensa</button>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/layout_bottom.php'; ?>

<script>
const input = document.getElementById('pantry-input');
const listEl = document.getElementById('autocomplete-list');
const chipsEl = document.getElementById('pantry-chips');
let currentItems = [];
let activeIndex = -1;

function renderChips() {
  document.getElementById('pantry-empty').style.display = currentItems.length ? 'none' : 'block';
  document.getElementById('btn-clear').style.display = currentItems.length ? 'inline-flex' : 'none';
  chipsEl.innerHTML = currentItems.map(item => `
    <span class="chip">${escapeHtml(item)}<button type="button" data-item="${escapeHtml(item)}" aria-label="Quitar">×</button></span>
  `).join('');
  chipsEl.querySelectorAll('button').forEach(btn => {
    btn.addEventListener('click', () => removeItem(btn.dataset.item));
  });
}

function escapeHtml(s) {
  const d = document.createElement('div');
  d.textContent = s;
  return d.innerHTML;
}

async function loadPantry() {
  const res = await MV.api('/api/pantry.php?action=list');
  currentItems = res.items;
  renderChips();
}

async function addItem(item) {
  item = item.trim();
  if (!item) return;
  input.value = '';
  listEl.style.display = 'none';
  try {
    const res = await MV.api('/api/pantry.php?action=add', { method: 'POST', body: { item } });
    currentItems = res.items;
    renderChips();
  } catch (err) {
    MV.toast(err.message, true);
  }
}

async function removeItem(item) {
  try {
    const res = await MV.api('/api/pantry.php?action=remove', { method: 'POST', body: { item } });
    currentItems = res.items;
    renderChips();
  } catch (err) {
    MV.toast(err.message, true);
  }
}

const searchDebounced = MV.debounce(async (q) => {
  if (!q) { listEl.style.display = 'none'; return; }
  try {
    const res = await MV.api('/api/pantry.php?action=search&q=' + encodeURIComponent(q));
    if (!res.results.length) { listEl.style.display = 'none'; return; }
    activeIndex = -1;
    listEl.innerHTML = res.results.map(r => `<button type="button" data-name="${escapeHtml(r)}">${escapeHtml(r)}</button>`).join('');
    listEl.querySelectorAll('button').forEach(b => b.addEventListener('click', () => addItem(b.dataset.name)));
    listEl.style.display = 'block';
  } catch (err) { /* silencioso: autocompletado no es crítico */ }
}, 200);

input.addEventListener('input', () => searchDebounced(input.value));
input.addEventListener('keydown', (e) => {
  const options = listEl.querySelectorAll('button');
  if (e.key === 'ArrowDown' && options.length) {
    e.preventDefault();
    activeIndex = Math.min(activeIndex + 1, options.length - 1);
    options.forEach((o, i) => o.classList.toggle('active', i === activeIndex));
  } else if (e.key === 'ArrowUp' && options.length) {
    e.preventDefault();
    activeIndex = Math.max(activeIndex - 1, 0);
    options.forEach((o, i) => o.classList.toggle('active', i === activeIndex));
  } else if (e.key === 'Enter') {
    e.preventDefault();
    if (activeIndex >= 0 && options[activeIndex]) {
      addItem(options[activeIndex].dataset.name);
    } else {
      addItem(input.value);
    }
  } else if (e.key === 'Escape') {
    listEl.style.display = 'none';
  }
});
document.addEventListener('click', (e) => {
  if (!e.target.closest('.autocomplete-wrap')) listEl.style.display = 'none';
});

document.getElementById('btn-clear').addEventListener('click', async () => {
  if (!confirm('¿Vaciar toda tu despensa?')) return;
  const res = await MV.api('/api/pantry.php?action=clear', { method: 'POST' });
  currentItems = res.items;
  renderChips();
});

// ---------- Tabs ----------
document.querySelectorAll('.tabs button').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tabs button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-despensa').style.display = btn.dataset.tab === 'tab-despensa' ? 'block' : 'none';
    document.getElementById('tab-compras').style.display = btn.dataset.tab === 'tab-compras' ? 'block' : 'none';
    if (btn.dataset.tab === 'tab-compras') loadShoppingList();
  });
});

async function loadShoppingList() {
  const el = document.getElementById('shopping-list');
  el.innerHTML = '<div class="skeleton" style="height:120px;"></div>';
  try {
    const res = await MV.api('/api/planner.php?action=shopping_list');
    const categories = Object.keys(res.list);
    if (!categories.length) {
      el.innerHTML = '';
      document.getElementById('shopping-empty').style.display = 'block';
      return;
    }
    document.getElementById('shopping-empty').style.display = 'none';
    el.innerHTML = categories.map(cat => `
      <p class="section-title">${escapeHtml(cat)}</p>
      <div class="card-soft" style="margin-bottom:14px;">
        ${res.list[cat].map(i => `
          <label class="checkbox-row" style="padding:6px 0;" data-row>
            <input type="checkbox" data-buy-item="${escapeHtml(i.item)}">
            <span data-label>${escapeHtml(i.item)}${i.qty ? ' <span class="muted">— ' + escapeHtml(i.qty) + '</span>' : ''}</span>
          </label>`).join('')}
      </div>`).join('');
    el.querySelectorAll('[data-buy-item]').forEach(cb => {
      cb.addEventListener('change', () => markAsBought(cb));
    });
  } catch (err) {
    el.innerHTML = '';
    MV.toast(err.message, true);
  }
}

async function markAsBought(checkbox) {
  const item = checkbox.dataset.buyItem;
  const label = checkbox.closest('[data-row]').querySelector('[data-label]');
  checkbox.disabled = true;
  try {
    if (checkbox.checked) {
      await MV.api('/api/pantry.php?action=add', { method: 'POST', body: { item } });
      label.style.textDecoration = 'line-through';
      label.style.opacity = '0.55';
      MV.toast(`"${item}" agregado a tu despensa ✅`);
    } else {
      await MV.api('/api/pantry.php?action=remove', { method: 'POST', body: { item } });
      label.style.textDecoration = 'none';
      label.style.opacity = '1';
      MV.toast(`"${item}" quitado de tu despensa`);
    }
  } catch (err) {
    checkbox.checked = !checkbox.checked;
    MV.toast(err.message, true);
  } finally {
    checkbox.disabled = false;
  }
}

// ---------- Ingresar mercado por voz ----------
let mediaRecorder = null;
let audioChunks = [];
let voiceTimerInterval = null;
let voiceSeconds = 0;
const MAX_VOICE_SECONDS = 45;

document.getElementById('btn-voice').addEventListener('click', async () => {
  if (!navigator.mediaDevices || !window.MediaRecorder) {
    MV.toast('Tu navegador no soporta grabar audio. Escribe los ingredientes por ahora.', true);
    return;
  }
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    audioChunks = [];
    const mimeType = MediaRecorder.isTypeSupported('audio/webm') ? 'audio/webm' : '';
    mediaRecorder = mimeType ? new MediaRecorder(stream, { mimeType }) : new MediaRecorder(stream);
    mediaRecorder.ondataavailable = (e) => { if (e.data.size > 0) audioChunks.push(e.data); };
    mediaRecorder.onstop = () => {
      stream.getTracks().forEach(t => t.stop());
      clearInterval(voiceTimerInterval);
      document.getElementById('voice-recording').style.display = 'none';
      const blob = new Blob(audioChunks, { type: mediaRecorder.mimeType || 'audio/webm' });
      if (blob.size > 0) sendVoice(blob);
    };
    mediaRecorder.start();
    voiceSeconds = 0;
    document.getElementById('voice-timer').textContent = '0:00';
    document.getElementById('voice-recording').style.display = 'block';
    voiceTimerInterval = setInterval(() => {
      voiceSeconds++;
      const m = Math.floor(voiceSeconds / 60), s = voiceSeconds % 60;
      document.getElementById('voice-timer').textContent = `${m}:${String(s).padStart(2, '0')}`;
      if (voiceSeconds >= MAX_VOICE_SECONDS) stopVoiceRecording();
    }, 1000);
  } catch (err) {
    MV.toast('No pudimos acceder al micrófono. Revisa los permisos del navegador.', true);
  }
});

function stopVoiceRecording() {
  if (mediaRecorder && mediaRecorder.state !== 'inactive') mediaRecorder.stop();
}
document.getElementById('btn-voice-stop').addEventListener('click', stopVoiceRecording);

async function sendVoice(blob) {
  document.getElementById('scan-loading-text').textContent = 'Escuchando tu audio...';
  document.getElementById('scan-loading').style.display = 'block';
  try {
    const fd = new FormData();
    fd.append('audio', blob, 'audio.webm');
    const res = await MV.api('/api/pantry.php?action=voice', { method: 'POST', body: fd });
    openReview(res.items, `Escuchamos: "${res.transcript}"`);
  } catch (err) {
    MV.toast(err.message, true);
  } finally {
    document.getElementById('scan-loading').style.display = 'none';
  }
}

// ---------- Ingresar mercado con foto de la factura ----------
document.getElementById('btn-photo').addEventListener('click', () => {
  document.getElementById('photo-input').click();
});

document.getElementById('photo-input').addEventListener('change', async (e) => {
  const file = e.target.files[0];
  e.target.value = '';
  if (!file) return;
  document.getElementById('scan-loading-text').textContent = 'Leyendo tu factura...';
  document.getElementById('scan-loading').style.display = 'block';
  try {
    const blob = await compressImage(file);
    const fd = new FormData();
    fd.append('photo', blob, 'factura.jpg');
    const res = await MV.api('/api/pantry.php?action=photo', { method: 'POST', body: fd });
    openReview(res.items, null);
  } catch (err) {
    MV.toast(err.message || 'No pudimos leer la foto.', true);
  } finally {
    document.getElementById('scan-loading').style.display = 'none';
  }
});

function compressImage(file, maxDim = 1600, quality = 0.75) {
  return new Promise((resolve, reject) => {
    const img = new Image();
    const reader = new FileReader();
    reader.onload = (e) => { img.src = e.target.result; };
    reader.onerror = () => reject(new Error('No pudimos leer la imagen.'));
    img.onload = () => {
      let { width, height } = img;
      if (width > height && width > maxDim) { height = Math.round(height * maxDim / width); width = maxDim; }
      else if (height > maxDim) { width = Math.round(width * maxDim / height); height = maxDim; }
      const canvas = document.createElement('canvas');
      canvas.width = width; canvas.height = height;
      canvas.getContext('2d').drawImage(img, 0, 0, width, height);
      canvas.toBlob(blob => blob ? resolve(blob) : reject(new Error('No se pudo procesar la imagen.')), 'image/jpeg', quality);
    };
    img.onerror = () => reject(new Error('No pudimos leer la imagen.'));
    reader.readAsDataURL(file);
  });
}

// ---------- Modal de revisión (voz y foto comparten esto) ----------
let reviewSelection = [];

function openReview(items, subtitleText) {
  reviewSelection = items.map(item => ({ item, on: true }));
  renderReviewChips();
  const subEl = document.getElementById('review-transcript');
  if (subtitleText) {
    subEl.textContent = subtitleText;
    subEl.style.display = 'block';
  } else {
    subEl.style.display = 'none';
  }
  document.getElementById('review-backdrop').style.display = 'flex';
}

function renderReviewChips() {
  const el = document.getElementById('review-chips');
  el.innerHTML = reviewSelection.map((r, i) => `
    <span class="chip ${r.on ? '' : 'tag'}" data-review-idx="${i}" style="cursor:pointer;${r.on ? '' : 'opacity:0.45;text-decoration:line-through;'}">
      ${escapeHtml(r.item)}
    </span>`).join('');
  el.querySelectorAll('[data-review-idx]').forEach(chip => {
    chip.addEventListener('click', () => {
      const idx = parseInt(chip.dataset.reviewIdx, 10);
      reviewSelection[idx].on = !reviewSelection[idx].on;
      renderReviewChips();
    });
  });
}

document.getElementById('review-cancel').addEventListener('click', () => {
  document.getElementById('review-backdrop').style.display = 'none';
});

document.getElementById('review-confirm').addEventListener('click', async () => {
  const items = reviewSelection.filter(r => r.on).map(r => r.item);
  if (!items.length) {
    MV.toast('Selecciona al menos un ingrediente.', true);
    return;
  }
  const btn = document.getElementById('review-confirm');
  btn.disabled = true;
  try {
    const res = await MV.api('/api/pantry.php?action=add', { method: 'POST', body: { items } });
    currentItems = res.items;
    renderChips();
    document.getElementById('review-backdrop').style.display = 'none';
    MV.toast(`${items.length} ingrediente(s) agregado(s) a tu despensa ✅`);
  } catch (err) {
    MV.toast(err.message, true);
  } finally {
    btn.disabled = false;
  }
});

loadPantry();
</script>

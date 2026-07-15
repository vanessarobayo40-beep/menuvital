<?php
require_once __DIR__ . '/../includes/auth.php';
secure_session_start();
send_security_headers();
$user = require_login_page();
$PAGE_TITLE = 'Progreso';
$ACTIVE_NAV = 'progreso';
require __DIR__ . '/../includes/layout_top.php';
?>

<h2 style="margin-bottom:4px;">Tu progreso</h2>
<p class="muted" style="margin-top:0;font-size:14px;">Un registro sencillo para llevar una vida balanceada, sin obsesionarte.</p>

<div class="stat-row">
  <div class="stat-tile"><div class="value" id="stat-streak">–</div><div class="label">días seguidos</div></div>
  <div class="stat-tile"><div class="value" id="stat-water">–</div><div class="label">vasos hoy</div></div>
  <div class="stat-tile"><div class="value" id="stat-weight">–</div><div class="label">peso actual (kg)</div></div>
</div>

<div class="card" style="margin-bottom:18px;">
  <h3 style="margin:0 0 4px;font-size:15px;">Agua de hoy</h3>
  <p class="muted" style="margin:0 0 12px;font-size:13px;">Mantente hidratada durante el día.</p>
  <div style="display:flex;align-items:center;gap:14px;">
    <button id="water-minus" class="btn btn-secondary btn-sm" aria-label="Quitar vaso">−</button>
    <span id="water-count" style="font-size:20px;font-weight:700;color:var(--green-dark);min-width:24px;text-align:center;">0</span>
    <button id="water-plus" class="btn btn-primary btn-sm" aria-label="Agregar vaso">+ 1 vaso</button>
  </div>
</div>

<div class="card" style="margin-bottom:18px;">
  <h3 style="margin:0 0 12px;font-size:15px;">Tendencia de peso</h3>
  <div id="chart-wrap"><svg id="weight-chart" viewBox="0 0 300 100" style="width:100%;height:100px;"></svg></div>
  <p id="chart-empty" class="muted" style="font-size:13px;display:none;">Registra tu peso algunos días para ver tu tendencia aquí.</p>
</div>

<div class="card">
  <h3 style="margin:0 0 12px;font-size:15px;">Registrar hoy</h3>
  <div class="field">
    <label>Peso (kg) — opcional</label>
    <input type="number" id="log-weight" step="0.1" placeholder="Ej: 68.5">
  </div>
  <div class="field">
    <label>Hábitos de hoy</label>
    <div class="chips" id="habits-chips"></div>
  </div>
  <div class="field mb-0">
    <label>Nota — opcional</label>
    <input type="text" id="log-note" maxlength="300" placeholder="¿Cómo te sentiste hoy?">
  </div>
  <button id="btn-save-log" class="btn btn-primary btn-block" style="margin-top:16px;">Guardar registro de hoy</button>
</div>

<?php require __DIR__ . '/../includes/layout_bottom.php'; ?>

<script>
const HABIT_LABELS = {
  verduras: '🥦 Comí verduras',
  ejercicio: '🏃 Hice ejercicio',
  dormir_bien: '😴 Dormí bien',
  sin_gaseosa: '🚫 Sin gaseosa',
};
let selectedHabits = new Set();
let waterCount = 0;

function renderHabits() {
  const el = document.getElementById('habits-chips');
  el.innerHTML = Object.keys(HABIT_LABELS).map(key => `
    <button type="button" class="chip tag" data-habit="${key}" style="${selectedHabits.has(key) ? 'background:var(--green-light);color:var(--green-dark);' : ''}">
      ${HABIT_LABELS[key]}
    </button>`).join('');
  el.querySelectorAll('button').forEach(btn => {
    btn.addEventListener('click', () => {
      const key = btn.dataset.habit;
      selectedHabits.has(key) ? selectedHabits.delete(key) : selectedHabits.add(key);
      renderHabits();
    });
  });
}

function drawChart(logs) {
  const points = logs.filter(l => l.weight !== null);
  const svg = document.getElementById('weight-chart');
  if (points.length < 2) {
    document.getElementById('chart-empty').style.display = 'block';
    svg.innerHTML = '';
    return;
  }
  document.getElementById('chart-empty').style.display = 'none';
  const weights = points.map(p => p.weight);
  const min = Math.min(...weights), max = Math.max(...weights);
  const range = (max - min) || 1;
  const w = 300, h = 100, pad = 10;
  const stepX = (w - pad * 2) / (points.length - 1);
  const coords = points.map((p, i) => {
    const x = pad + i * stepX;
    const y = h - pad - ((p.weight - min) / range) * (h - pad * 2);
    return [x, y];
  });
  const path = coords.map((c, i) => (i === 0 ? 'M' : 'L') + c[0].toFixed(1) + ',' + c[1].toFixed(1)).join(' ');
  const dots = coords.map(c => `<circle cx="${c[0].toFixed(1)}" cy="${c[1].toFixed(1)}" r="2.5" fill="#0F9D6B"/>`).join('');
  svg.innerHTML = `<path d="${path}" fill="none" stroke="#0F9D6B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>${dots}`;
}

async function loadProgress() {
  try {
    const res = await MV.api('/api/progress.php?action=list');
    document.getElementById('stat-streak').textContent = res.streak;
    waterCount = res.today ? res.today.water : 0;
    document.getElementById('water-count').textContent = waterCount;
    document.getElementById('stat-water').textContent = waterCount;
    document.getElementById('stat-weight').textContent = res.today && res.today.weight ? res.today.weight : '–';
    selectedHabits = new Set(res.today ? res.today.habits : []);
    renderHabits();
    if (res.today) {
      document.getElementById('log-weight').value = res.today.weight ?? '';
      document.getElementById('log-note').value = res.today.note ?? '';
    }
    drawChart(res.logs);
  } catch (err) {
    MV.toast(err.message, true);
  }
}

async function adjustWater(delta) {
  try {
    const res = await MV.api('/api/progress.php?action=water', { method: 'POST', body: { delta } });
    waterCount = res.water;
    document.getElementById('water-count').textContent = waterCount;
    document.getElementById('stat-water').textContent = waterCount;
  } catch (err) {
    MV.toast(err.message, true);
  }
}
document.getElementById('water-plus').addEventListener('click', () => adjustWater(1));
document.getElementById('water-minus').addEventListener('click', () => adjustWater(-1));

document.getElementById('btn-save-log').addEventListener('click', async () => {
  const btn = document.getElementById('btn-save-log');
  btn.disabled = true;
  try {
    await MV.api('/api/progress.php?action=save', {
      method: 'POST',
      body: {
        weight: document.getElementById('log-weight').value,
        water: waterCount,
        habits: Array.from(selectedHabits),
        note: document.getElementById('log-note').value,
      },
    });
    MV.toast('¡Registro guardado! Sigue así 💪');
    loadProgress();
  } catch (err) {
    MV.toast(err.message, true);
  } finally {
    btn.disabled = false;
  }
});

loadProgress();
</script>

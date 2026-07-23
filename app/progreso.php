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
  <div class="stat-tile"><div class="value" id="stat-imc">–</div><div class="label">tu IMC</div></div>
</div>

<div id="progress-summary" class="card-soft" style="display:none;margin-bottom:18px;">
  <p style="margin:0;font-size:14px;" id="progress-summary-text"></p>
</div>

<div id="profile-nudge" class="card-soft" style="display:none;margin-bottom:18px;">
  <p style="margin:0;font-size:13px;">💡 Completa tu <a href="/app/perfil.php" style="color:var(--green-dark);font-weight:600;">estatura y peso inicial en tu perfil</a> para ver tu IMC y tu avance real.</p>
</div>

<div class="card" style="margin-bottom:18px;">
  <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:4px;">
    <h3 style="margin:0;font-size:15px;">Agua de hoy</h3>
    <span class="muted" style="font-size:12px;" id="water-target-label">meta: 8 vasos</span>
  </div>
  <p class="muted" style="margin:0 0 10px;font-size:13px;">Mantente hidratada durante el día.</p>
  <div style="height:8px;background:var(--surface-2);border-radius:999px;overflow:hidden;margin-bottom:14px;">
    <div id="water-bar" style="height:100%;width:0%;background:var(--grad-v);border-radius:999px;transition:width 0.3s ease;"></div>
  </div>
  <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">
    <button id="water-minus" class="btn btn-secondary btn-sm" aria-label="Quitar vaso">−</button>
    <span id="water-count" style="font-size:20px;font-weight:700;color:var(--green-dark);min-width:24px;text-align:center;">0</span>
    <button id="water-plus" class="btn btn-primary btn-sm" aria-label="Agregar vaso">+ 1 vaso</button>
  </div>
  <button id="btn-water-reminders" class="btn btn-outline btn-block btn-sm">🔔 Activar recordatorios de agua</button>
  <p id="water-reminders-status" class="muted" style="display:none;margin:8px 0 0;font-size:12px;text-align:center;">✅ Recordatorios activados — de agua entre semana (cada 2 horas, 7am–9pm) y de tu lista de mercado los fines de semana. <a href="#" id="btn-water-test" style="color:var(--green-dark);font-weight:600;">Enviar una de prueba</a></p>
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
let waterTarget = 8;

function renderWaterBar() {
  document.getElementById('water-target-label').textContent = `meta: ${waterTarget} vasos`;
  const pct = Math.min(100, Math.round((waterCount / waterTarget) * 100));
  document.getElementById('water-bar').style.width = pct + '%';
}

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
  const dots = coords.map(c => `<circle cx="${c[0].toFixed(1)}" cy="${c[1].toFixed(1)}" r="2.5" fill="#0E6B45"/>`).join('');
  svg.innerHTML = `<path d="${path}" fill="none" stroke="#0E6B45" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>${dots}`;
}

function imcInfo(imc) {
  if (imc < 18.5) return 'bajo peso';
  if (imc < 25) return 'rango saludable 💚';
  if (imc < 30) return 'sobrepeso leve';
  return 'zona de cuidado';
}

async function loadProgress() {
  try {
    const [res, prof] = await Promise.all([
      MV.api('/api/progress.php?action=list'),
      MV.api('/api/profile.php?action=get'),
    ]);
    document.getElementById('stat-streak').textContent = res.streak;
    waterCount = res.today ? res.today.water : 0;
    waterTarget = prof.profile.water_target || 8;
    document.getElementById('water-count').textContent = waterCount;
    document.getElementById('stat-water').textContent = `${waterCount}/${waterTarget}`;
    renderWaterBar();

    const logged = res.logs.filter(l => l.weight !== null);
    const lastWeight = (res.today && res.today.weight) ? res.today.weight
      : (logged.length ? logged[logged.length - 1].weight : null);
    document.getElementById('stat-weight').textContent = lastWeight ?? '–';

    const height = prof.profile.height_cm;
    const startWeight = prof.profile.starting_weight;

    if (height && lastWeight) {
      const imc = lastWeight / Math.pow(height / 100, 2);
      document.getElementById('stat-imc').textContent = imc.toFixed(1);
    } else {
      document.getElementById('stat-imc').textContent = '–';
    }

    if (!height || !startWeight) {
      document.getElementById('profile-nudge').style.display = 'block';
    } else if (lastWeight) {
      const diff = lastWeight - startWeight;
      const imc = lastWeight / Math.pow(height / 100, 2);
      let text;
      if (Math.abs(diff) < 0.1) {
        text = `⚖️ Estás igual que tu peso inicial (${startWeight} kg). Tu IMC es ${imc.toFixed(1)} (${imcInfo(imc)}).`;
      } else if (diff < 0) {
        text = `🎉 ¡Has bajado ${Math.abs(diff).toFixed(1)} kg desde que empezaste (${startWeight} kg)! Tu IMC es ${imc.toFixed(1)} (${imcInfo(imc)}).`;
      } else {
        text = `📈 Llevas ${diff.toFixed(1)} kg más que tu peso inicial (${startWeight} kg). Tu IMC es ${imc.toFixed(1)} (${imcInfo(imc)}). ¡Un día a la vez!`;
      }
      document.getElementById('progress-summary-text').textContent = text;
      document.getElementById('progress-summary').style.display = 'block';
    }

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
    document.getElementById('stat-water').textContent = `${waterCount}/${waterTarget}`;
    renderWaterBar();
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

// ---------- Recordatorios de agua (notificaciones push) ----------
const btnReminders = document.getElementById('btn-water-reminders');
const remindersStatus = document.getElementById('water-reminders-status');

async function refreshReminderUI() {
  try {
    const subscribed = await MV.push.isSubscribed();
    if (subscribed) {
      btnReminders.textContent = '🔕 Desactivar recordatorios';
      remindersStatus.style.display = 'block';
    } else {
      btnReminders.textContent = '🔔 Activar recordatorios de agua';
      remindersStatus.style.display = 'none';
    }
  } catch (e) { /* si algo falla aquí, el botón simplemente ofrece activar de nuevo */ }
}

btnReminders.addEventListener('click', async () => {
  btnReminders.disabled = true;
  try {
    const alreadySubscribed = await MV.push.isSubscribed();
    if (alreadySubscribed) {
      await MV.push.unsubscribe();
      MV.toast('Recordatorios desactivados.');
    } else {
      const result = await MV.push.subscribe();
      if (result === 'subscribed') {
        MV.toast('¡Listo! Te recordaremos tomar agua 💧');
      } else if (result === 'denied') {
        MV.toast('Bloqueaste los permisos de notificación. Actívalos en los ajustes de tu navegador.', true);
      } else if (result === 'unsupported') {
        MV.toast('Tu navegador no soporta notificaciones push.', true);
      } else {
        MV.toast('No pudimos activar los recordatorios. Intenta de nuevo.', true);
      }
    }
  } finally {
    btnReminders.disabled = false;
    refreshReminderUI();
  }
});

document.getElementById('btn-water-test').addEventListener('click', async (e) => {
  e.preventDefault();
  try {
    await MV.api('/api/push.php?action=test', { method: 'POST' });
    MV.toast('Notificación de prueba enviada — revisa tu celular 📲');
  } catch (err) {
    MV.toast(err.message, true);
  }
});

if (MV.push.isSupported()) {
  refreshReminderUI();
} else {
  btnReminders.style.display = 'none';
}
</script>

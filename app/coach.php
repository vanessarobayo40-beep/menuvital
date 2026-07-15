<?php
require_once __DIR__ . '/../includes/auth.php';
secure_session_start();
send_security_headers();
$user = require_login_page();
$PAGE_TITLE = 'Coach';
$ACTIVE_NAV = 'coach';
require __DIR__ . '/../includes/layout_top.php';
?>

<h2 style="margin-bottom:4px;">Tu coach de nutrición</h2>
<p class="muted" style="margin-top:0;font-size:14px;">Pregúntale lo que quieras: recetas, sustituciones, motivación...</p>

<div id="chat-scroll" class="chat-scroll" style="padding-bottom:110px;"></div>

<div id="chat-empty" class="empty-state" style="display:none;">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
  <p>Empieza la conversación. Por ejemplo: "¿qué merienda saludable puedo llevar al trabajo?"</p>
</div>

<div class="chat-input-bar">
  <textarea id="chat-input" rows="1" placeholder="Escribe tu mensaje..." maxlength="600"></textarea>
  <button id="chat-send" aria-label="Enviar">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
  </button>
</div>

<?php require __DIR__ . '/../includes/layout_bottom.php'; ?>

<script>
const scrollEl = document.getElementById('chat-scroll');

function escapeHtml(s) {
  const d = document.createElement('div');
  d.textContent = s ?? '';
  return d.innerHTML;
}

function appendMessage(role, content) {
  document.getElementById('chat-empty').style.display = 'none';
  const div = document.createElement('div');
  div.className = 'msg ' + (role === 'user' ? 'user' : 'assistant');
  div.textContent = content;
  scrollEl.appendChild(div);
  window.scrollTo(0, document.body.scrollHeight);
  return div;
}

async function loadHistory() {
  try {
    const res = await MV.api('/api/coach.php?action=history');
    if (!res.messages.length) {
      document.getElementById('chat-empty').style.display = 'block';
      return;
    }
    res.messages.forEach(m => appendMessage(m.role, m.content));
  } catch (err) {
    MV.toast(err.message, true);
  }
}

const input = document.getElementById('chat-input');
const sendBtn = document.getElementById('chat-send');

input.addEventListener('input', () => {
  input.style.height = 'auto';
  input.style.height = Math.min(input.scrollHeight, 100) + 'px';
});

async function send() {
  const text = input.value.trim();
  if (!text) return;
  input.value = '';
  input.style.height = 'auto';
  appendMessage('user', text);
  sendBtn.disabled = true;
  const typing = appendMessage('assistant', 'Escribiendo...');
  try {
    const res = await MV.api('/api/coach.php?action=send', { method: 'POST', body: { message: text } });
    typing.textContent = res.reply;
  } catch (err) {
    typing.textContent = err.message;
  } finally {
    sendBtn.disabled = false;
    window.scrollTo(0, document.body.scrollHeight);
  }
}

sendBtn.addEventListener('click', send);
input.addEventListener('keydown', (e) => {
  if (e.key === 'Enter' && !e.shiftKey) {
    e.preventDefault();
    send();
  }
});

loadHistory();
</script>

const msgs = document.getElementById('msgs'), f = document.getElementById('f'), q = document.getElementById('q');
function add(text, cls) {
  const d = document.createElement('div'); d.className = 'msg ' + cls; d.textContent = text;
  msgs.appendChild(d); msgs.scrollTop = msgs.scrollHeight; return d;
}
async function ask(text) {
  add(text, 'me'); const wait = add('Ойланып жатырмын…', 'ai');
  try {
    const r = await fetch('api_chat.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ question: text }) });
    const j = await r.json(); wait.textContent = j.answer || j.error || 'Жауап алынбады.';
  } catch (e) { wait.textContent = 'Байланыс қатесі. Қайта көріңіз.'; }
  msgs.scrollTop = msgs.scrollHeight;
}
f.addEventListener('submit', e => { e.preventDefault(); const t = q.value.trim(); if (!t) return; q.value = ''; ask(t); });
document.querySelectorAll('.chips button').forEach(b => b.addEventListener('click', () => ask(b.dataset.q)));

<?php require 'config.php'; require_login(); $title='ЖИ көмекші'; include 'includes/header.php'; ?>
<h1>ЖИ көмекші</h1>
<p class="meta">Дрондар туралы кез келген сұрақты қазақша қойыңыз. Көмекші платформадағы сабақтарға сүйеніп жауап береді.</p>
<div class="chips">
  <button type="button" data-q="Квадрокоптер қалай ұшады?">Квадрокоптер қалай ұшады?</button>
  <button type="button" data-q="Дронды ұшыру ережелері қандай?">Ұшыру ережелері</button>
  <button type="button" data-q="Дронда жасанды интеллект қалай қолданылады?">Дронда ЖИ</button>
</div>
<div class="chat">
  <div id="msgs"><div class="msg ai">Сәлеметсіз бе! Мен дрондар бойынша көмекшімін. Не білгіңіз келеді?</div></div>
  <form id="f"><input id="q" placeholder="Сұрағыңызды жазыңыз…" autocomplete="off" required><button>Жіберу</button></form>
</div>
<script src="assets/app.js"></script>
<?php include 'includes/footer.php'; ?>

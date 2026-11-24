<?php require 'config.php'; $title='Басты бет';
$stats = [
 'lessons'=>$pdo->query('SELECT COUNT(*) FROM lessons')->fetchColumn(),
 'drones'=>$pdo->query('SELECT COUNT(*) FROM drones')->fetchColumn(),
 'students'=>$pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn(),
];
include 'includes/header.php'; ?>
<section class="hero">
  <div>
    <h1>Дрондар әлемін бірге зерттейік</h1>
    <p>Дрондардың құрылымын, түрлерін және қауіпсіз ұшу ережелерін қазақ тілінде үйреніңіз. Сабақтар, тесттер және жасанды интеллект көмекшісі бір жерде.</p>
    <div class="row">
      <?php if(logged()): ?><a class="btn" href="student.php">Оқушы панеліне өту</a>
      <?php else: ?><a class="btn" href="register.php">Тегін тіркелу</a><a class="btn ghost" href="drones.php">Каталогты қарау</a><?php endif; ?>
    </div>
  </div>
  <div class="hero-art" aria-hidden="true">
    <svg viewBox="0 0 200 200" fill="none" stroke="#F2B705" stroke-width="5" stroke-linecap="round">
      <rect x="82" y="86" width="36" height="28" rx="8" fill="#0E8A8A" stroke="none"/>
      <path d="M82 92 L45 60M118 92 L155 60M82 108 L45 140M118 108 L155 140"/>
      <circle cx="45" cy="60" r="20"/><circle cx="155" cy="60" r="20"/><circle cx="45" cy="140" r="20"/><circle cx="155" cy="140" r="20"/>
      <circle cx="100" cy="124" r="5" fill="#fff" stroke="none"/>
    </svg>
  </div>
</section>
<div class="stats">
  <div class="card stat"><b><?= $stats['lessons'] ?></b>сабақ</div>
  <div class="card stat"><b><?= $stats['drones'] ?></b>каталогтағы дрон</div>
  <div class="card stat"><b><?= $stats['students'] ?></b>оқушы</div>
</div>
<h2>Платформа не береді</h2>
<div class="grid">
  <div class="card"><h3>Сабақтар мен тесттер</h3><p>Әр сабақтан кейін білімді тексеріп, прогресті бақылаңыз.</p></div>
  <div class="card"><h3>ЖИ көмекші</h3><p>Дрондар туралы кез келген сұрақты қазақша қойыңыз.</p></div>
  <div class="card"><h3>Дрондар каталогы</h3><p>Модельдерді түрі, ұшу уақыты және қашықтығы бойынша салыстырыңыз.</p></div>
</div>
<?php include 'includes/footer.php'; ?>

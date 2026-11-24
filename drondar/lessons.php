<?php require 'config.php'; $title='Сабақтар';
$done=[]; if(logged()){ $q=$pdo->prepare('SELECT lesson_id FROM progress WHERE user_id=?'); $q->execute([logged()['id']]); $done=$q->fetchAll(PDO::FETCH_COLUMN); }
$rows=$pdo->query('SELECT * FROM lessons ORDER BY id')->fetchAll();
include 'includes/header.php'; ?>
<h1>Сабақтар</h1>
<div class="grid">
<?php foreach($rows as $l): ?>
  <article class="card"><span class="tag"><?= e($l['category']) ?></span>
  <h3 style="margin-top:.7rem"><?= e($l['title']) ?></h3>
  <p class="meta"><?= e(mb_substr($l['content'],0,90)) ?>…</p>
  <a class="btn small" href="lesson.php?id=<?= $l['id'] ?>">Ашу</a>
  <?php if(in_array($l['id'],$done)): ?> <span class="tag" style="background:#DFF3E8;color:var(--ok)">Өтілді</span><?php endif; ?></article>
<?php endforeach; ?>
</div>
<?php include 'includes/footer.php'; ?>

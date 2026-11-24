<?php require 'config.php'; require_login(); $title='Оқушы панелі'; $uid=logged()['id'];
$total=(int)$pdo->query('SELECT COUNT(*) FROM lessons')->fetchColumn();
$q=$pdo->prepare('SELECT COUNT(*) FROM progress WHERE user_id=?'); $q->execute([$uid]); $done=(int)$q->fetchColumn();
$q=$pdo->prepare('SELECT COUNT(*) c, COALESCE(SUM(is_correct),0) s FROM results WHERE user_id=?'); $q->execute([$uid]); $r=$q->fetch();
$pct=$total?round($done/$total*100):0; $acc=$r['c']?round($r['s']/$r['c']*100):0;
$q=$pdo->prepare('SELECT l.id,l.title FROM lessons l LEFT JOIN progress p ON p.lesson_id=l.id AND p.user_id=? WHERE p.user_id IS NULL ORDER BY l.id LIMIT 5'); $q->execute([$uid]); $next=$q->fetchAll();
$q=$pdo->prepare('SELECT question,answer,created_at FROM chat_logs WHERE user_id=? ORDER BY id DESC LIMIT 5'); $q->execute([$uid]); $chats=$q->fetchAll();
include 'includes/header.php'; ?>
<h1>Сәлем, <?= e(logged()['name']) ?>!</h1>
<div class="stats">
  <div class="card stat"><b><?= $done ?>/<?= $total ?></b>өтілген сабақ</div>
  <div class="card stat"><b><?= $acc ?>%</b>тест дәлдігі</div>
  <div class="card stat"><b><?= (int)$r['c'] ?></b>жауап берілген сұрақ</div>
</div>
<div class="card" style="margin-bottom:2rem"><b>Жалпы прогресс: <?= $pct ?>%</b><div class="bar" style="margin-top:.5rem"><i style="width:<?= $pct ?>%"></i></div></div>
<h2>Келесі сабақтар</h2>
<?php if($next): ?><ul><?php foreach($next as $n): ?><li><a href="lesson.php?id=<?= $n['id'] ?>"><?= e($n['title']) ?></a></li><?php endforeach; ?></ul>
<?php else: ?><p>Барлық сабақ өтілді. Жарайсың!</p><?php endif; ?>
<h2>ЖИ көмекшімен соңғы сұрақтарыңыз</h2>
<?php foreach($chats as $c): ?><div class="card" style="margin-bottom:.8rem"><b><?= e($c['question']) ?></b><p><?= e(mb_substr($c['answer'],0,200)) ?>…</p><span class="meta"><?= e($c['created_at']) ?></span></div>
<?php endforeach; if(!$chats): ?><p>Әзірге сұрақ жоқ. <a href="chat.php">Көмекшіден сұрап көріңіз.</a></p><?php endif; ?>
<?php include 'includes/footer.php'; ?>

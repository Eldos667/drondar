<?php require 'config.php'; $title='Дрондар каталогы';
$type=$_GET['type']??''; $types=$pdo->query('SELECT DISTINCT type FROM drones ORDER BY type')->fetchAll(PDO::FETCH_COLUMN);
if ($type!=='') { $q=$pdo->prepare('SELECT * FROM drones WHERE type=? ORDER BY name'); $q->execute([$type]); $rows=$q->fetchAll(); }
else $rows=$pdo->query('SELECT * FROM drones ORDER BY name')->fetchAll();
include 'includes/header.php'; ?>
<h1>Дрондар каталогы</h1>
<div class="tabs"><a href="drones.php" class="<?= $type===''?'on':'' ?>">Барлығы</a>
<?php foreach($types as $t): ?><a href="?type=<?= urlencode($t) ?>" class="<?= $type===$t?'on':'' ?>"><?= e($t) ?></a><?php endforeach; ?></div>
<div class="grid">
<?php foreach($rows as $d): ?>
  <article class="card"><span class="tag"><?= e($d['type']) ?></span><h3 style="margin-top:.7rem"><?= e($d['name']) ?></h3>
  <p><?= e($d['description']) ?></p>
  <p class="meta">Ұшу уақыты: <?= (int)$d['flight_min'] ?> мин · Қашықтық: <?= e($d['range_km']) ?> км</p></article>
<?php endforeach; if(!$rows): ?><p>Бұл санатта дрон жоқ.</p><?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>

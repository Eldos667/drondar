<?php $u = logged(); $in_admin = strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false; $base = $in_admin ? '../' : ''; ?>
<!DOCTYPE html>
<html lang="kk">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Дрондар әлемі') ?> — Дрондар әлемі</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@500;700&family=Onest:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $base ?>assets/style.css">
</head>
<body>
<header class="top">
  <a class="brand" href="<?= $base ?>index.php"><span class="rotor"></span>Дрондар әлемі</a>
  <nav>
    <a href="<?= $base ?>index.php">Басты бет</a>
    <a href="<?= $base ?>drones.php">Дрондар каталогы</a>
    <a href="<?= $base ?>lessons.php">Сабақтар</a>
    <?php if ($u): ?>
      <a href="<?= $base ?>chat.php">ЖИ көмекші</a>
      <a href="<?= $base ?>student.php">Оқушы панелі</a>
      <?php if ($u['role'] === 'admin'): ?><a href="<?= $base ?>admin/index.php">Әзірлеуші панелі</a><?php endif; ?>
      <a class="btn small" href="<?= $base ?>logout.php">Шығу (<?= e($u['name']) ?>)</a>
    <?php else: ?>
      <a href="<?= $base ?>login.php">Кіру</a>
      <a class="btn small" href="<?= $base ?>register.php">Тіркелу</a>
    <?php endif; ?>
  </nav>
</header>
<main>

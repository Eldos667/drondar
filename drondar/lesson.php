<?php require 'config.php'; require_login();
$q=$pdo->prepare('SELECT * FROM lessons WHERE id=?'); $q->execute([(int)($_GET['id']??0)]); $l=$q->fetch();
if(!$l){ http_response_code(404); die('Сабақ табылмады'); }
$uid=logged()['id']; $msg='';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['done'])){ check_csrf();
  $pdo->prepare('INSERT IGNORE INTO progress (user_id,lesson_id) VALUES (?,?)')->execute([$uid,$l['id']]); $msg='Сабақ өтілді деп белгіленді.'; }
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['answers'])){ check_csrf();
  $qs=$pdo->prepare('SELECT * FROM quizzes WHERE lesson_id=?'); $qs->execute([$l['id']]); $score=0; $total=0;
  foreach($qs->fetchAll() as $z){ $total++; $ok=(($_POST['answers'][$z['id']]??'')===$z['correct'])?1:0; $score+=$ok;
    $pdo->prepare('INSERT INTO results (user_id,quiz_id,is_correct) VALUES (?,?,?)')->execute([$uid,$z['id'],$ok]); }
  $msg="Тест нәтижесі: $score / $total"; if($total && $score==$total) $pdo->prepare('INSERT IGNORE INTO progress (user_id,lesson_id) VALUES (?,?)')->execute([$uid,$l['id']]);
}
$qs=$pdo->prepare('SELECT * FROM quizzes WHERE lesson_id=?'); $qs->execute([$l['id']]); $quiz=$qs->fetchAll();
$title=$l['title']; include 'includes/header.php'; ?>
<span class="tag"><?= e($l['category']) ?></span>
<h1 style="margin-top:.6rem"><?= e($l['title']) ?></h1>
<?php if($msg): ?><div class="alert ok"><?= e($msg) ?></div><?php endif; ?>
<div class="lesson-body"><?= e($l['content']) ?></div>
<form method="post" style="margin:1rem 0"><?= csrf_field() ?><button name="done" value="1">Сабақты өттім</button></form>
<?php if($quiz): ?>
<h2>Білімді тексеру</h2>
<form method="post" class="card" style="max-width:72ch"><?= csrf_field() ?>
<?php foreach($quiz as $z): ?>
  <div class="quiz-q"><b><?= e($z['question']) ?></b>
  <?php foreach(['a','b','c','d'] as $o): ?><label><input type="radio" name="answers[<?= $z['id'] ?>]" value="<?= $o ?>" required> <?= e($z[$o]) ?></label><?php endforeach; ?></div>
<?php endforeach; ?>
<button>Жауаптарды жіберу</button></form>
<?php endif; ?>
<p><a href="chat.php">Түсінбегеніңіз бар ма? ЖИ көмекшіден сұраңыз.</a></p>
<?php include 'includes/footer.php'; ?>

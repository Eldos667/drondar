<?php require '../config.php'; require_admin(); $title='Әзірлеуші панелі';
$tab=$_GET['tab']??'stats'; $msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){ check_csrf(); $a=$_POST['action']??'';
  if($a==='add_lesson'){ $pdo->prepare('INSERT INTO lessons (title,category,content) VALUES (?,?,?)')->execute([trim($_POST['title']),trim($_POST['category']),trim($_POST['content'])]); $msg='Сабақ қосылды.'; }
  if($a==='del_lesson'){ $pdo->prepare('DELETE FROM lessons WHERE id=?')->execute([(int)$_POST['id']]); $msg='Сабақ жойылды.'; }
  if($a==='add_drone'){ $pdo->prepare('INSERT INTO drones (name,type,flight_min,range_km,description) VALUES (?,?,?,?,?)')->execute([trim($_POST['name']),trim($_POST['type']),(int)$_POST['flight_min'],(float)$_POST['range_km'],trim($_POST['description'])]); $msg='Дрон қосылды.'; }
  if($a==='del_drone'){ $pdo->prepare('DELETE FROM drones WHERE id=?')->execute([(int)$_POST['id']]); $msg='Дрон жойылды.'; }
  if($a==='add_quiz'){ $pdo->prepare('INSERT INTO quizzes (lesson_id,question,a,b,c,d,correct) VALUES (?,?,?,?,?,?,?)')->execute([(int)$_POST['lesson_id'],trim($_POST['question']),trim($_POST['a']),trim($_POST['b']),trim($_POST['c']),trim($_POST['d']),$_POST['correct']]); $msg='Сұрақ қосылды.'; }
  if($a==='del_quiz'){ $pdo->prepare('DELETE FROM quizzes WHERE id=?')->execute([(int)$_POST['id']]); $msg='Сұрақ жойылды.'; }
  if($a==='del_user'){ $pdo->prepare("DELETE FROM users WHERE id=? AND role='student'")->execute([(int)$_POST['id']]); $msg='Оқушы жойылды.'; }
}
$tabs=['stats'=>'Статистика','lessons'=>'Сабақтар','drones'=>'Дрондар','quizzes'=>'Тесттер','users'=>'Оқушылар','chats'=>'ЖИ сұхбаттары'];
function del_form($action,$id){ return '<form method="post" onsubmit="return confirm(\'Жоюды растайсыз ба?\')" style="display:inline">'.csrf_field().'<input type="hidden" name="action" value="'.$action.'"><input type="hidden" name="id" value="'.$id.'"><button class="danger">Жою</button></form>'; }
include '../includes/header.php'; ?>
<h1>Әзірлеуші панелі</h1>
<div class="tabs"><?php foreach($tabs as $k=>$v): ?><a href="?tab=<?= $k ?>" class="<?= $tab===$k?'on':'' ?>"><?= $v ?></a><?php endforeach; ?></div>
<?php if($msg): ?><div class="alert ok"><?= e($msg) ?></div><?php endif; ?>

<?php if($tab==='stats'):
 $s=[ 'Оқушылар'=>"SELECT COUNT(*) FROM users WHERE role='student'", 'Сабақтар'=>'SELECT COUNT(*) FROM lessons', 'Дрондар'=>'SELECT COUNT(*) FROM drones', 'Тест сұрақтары'=>'SELECT COUNT(*) FROM quizzes', 'ЖИ сұрақтары'=>'SELECT COUNT(*) FROM chat_logs' ];
 $top=$pdo->query("SELECT u.name, COUNT(p.lesson_id) c FROM users u LEFT JOIN progress p ON p.user_id=u.id WHERE u.role='student' GROUP BY u.id ORDER BY c DESC LIMIT 5")->fetchAll(); ?>
<div class="stats"><?php foreach($s as $k=>$sql): ?><div class="card stat"><b><?= (int)$pdo->query($sql)->fetchColumn() ?></b><?= $k ?></div><?php endforeach; ?></div>
<h2>Белсенді оқушылар</h2>
<table><tr><th>Аты</th><th>Өтілген сабақ</th></tr><?php foreach($top as $t): ?><tr><td><?= e($t['name']) ?></td><td><?= (int)$t['c'] ?></td></tr><?php endforeach; ?></table>

<?php elseif($tab==='lessons'): ?>
<h2>Жаңа сабақ</h2>
<form method="post" class="stack"><?= csrf_field() ?><input type="hidden" name="action" value="add_lesson">
 <label>Тақырыбы<input name="title" required></label><label>Санаты<input name="category" value="Негіздер" required></label>
 <label>Мазмұны<textarea name="content" rows="6" required></textarea></label><button>Қосу</button></form>
<h2 style="margin-top:2rem">Барлық сабақ</h2>
<table><tr><th>Тақырып</th><th>Санат</th><th></th></tr>
<?php foreach($pdo->query('SELECT * FROM lessons ORDER BY id DESC') as $l): ?><tr><td><?= e($l['title']) ?></td><td><?= e($l['category']) ?></td><td><?= del_form('del_lesson',$l['id']) ?></td></tr><?php endforeach; ?></table>

<?php elseif($tab==='drones'): ?>
<h2>Жаңа дрон</h2>
<form method="post" class="stack"><?= csrf_field() ?><input type="hidden" name="action" value="add_drone">
 <label>Атауы<input name="name" required></label><label>Түрі<input name="type" placeholder="Квадрокоптер" required></label>
 <label>Ұшу уақыты (мин)<input type="number" name="flight_min" min="0" required></label>
 <label>Қашықтық (км)<input type="number" step="0.1" name="range_km" min="0" required></label>
 <label>Сипаттама<textarea name="description" rows="3"></textarea></label><button>Қосу</button></form>
<h2 style="margin-top:2rem">Каталог</h2>
<table><tr><th>Атауы</th><th>Түрі</th><th>Мин</th><th>Км</th><th></th></tr>
<?php foreach($pdo->query('SELECT * FROM drones ORDER BY id DESC') as $d): ?><tr><td><?= e($d['name']) ?></td><td><?= e($d['type']) ?></td><td><?= (int)$d['flight_min'] ?></td><td><?= e($d['range_km']) ?></td><td><?= del_form('del_drone',$d['id']) ?></td></tr><?php endforeach; ?></table>

<?php elseif($tab==='quizzes'): $ls=$pdo->query('SELECT id,title FROM lessons ORDER BY id')->fetchAll(); ?>
<h2>Жаңа тест сұрағы</h2>
<form method="post" class="stack"><?= csrf_field() ?><input type="hidden" name="action" value="add_quiz">
 <label>Сабақ<select name="lesson_id"><?php foreach($ls as $l): ?><option value="<?= $l['id'] ?>"><?= e($l['title']) ?></option><?php endforeach; ?></select></label>
 <label>Сұрақ<input name="question" required></label>
 <label>A<input name="a" required></label><label>B<input name="b" required></label><label>C<input name="c" required></label><label>D<input name="d" required></label>
 <label>Дұрыс жауап<select name="correct"><option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option></select></label>
 <button>Қосу</button></form>
<h2 style="margin-top:2rem">Сұрақтар</h2>
<table><tr><th>Сабақ</th><th>Сұрақ</th><th>Дұрыс</th><th></th></tr>
<?php foreach($pdo->query('SELECT q.*,l.title FROM quizzes q JOIN lessons l ON l.id=q.lesson_id ORDER BY q.id DESC') as $z): ?><tr><td><?= e($z['title']) ?></td><td><?= e($z['question']) ?></td><td><?= strtoupper($z['correct']) ?></td><td><?= del_form('del_quiz',$z['id']) ?></td></tr><?php endforeach; ?></table>

<?php elseif($tab==='users'): ?>
<table><tr><th>Аты</th><th>Email</th><th>Рөлі</th><th>Тіркелген</th><th></th></tr>
<?php foreach($pdo->query('SELECT * FROM users ORDER BY id DESC') as $u): ?><tr><td><?= e($u['name']) ?></td><td><?= e($u['email']) ?></td><td><?= e($u['role']) ?></td><td><?= e($u['created_at']) ?></td><td><?= $u['role']==='student'?del_form('del_user',$u['id']):'' ?></td></tr><?php endforeach; ?></table>

<?php else: ?>
<table><tr><th>Оқушы</th><th>Сұрақ</th><th>Жауап</th><th>Уақыты</th></tr>
<?php foreach($pdo->query('SELECT c.*,u.name FROM chat_logs c JOIN users u ON u.id=c.user_id ORDER BY c.id DESC LIMIT 50') as $c): ?><tr><td><?= e($c['name']) ?></td><td><?= e($c['question']) ?></td><td><?= e(mb_substr($c['answer'],0,160)) ?>…</td><td><?= e($c['created_at']) ?></td></tr><?php endforeach; ?></table>
<?php endif; ?>
<?php include '../includes/footer.php'; ?>

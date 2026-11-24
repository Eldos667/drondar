<?php require 'config.php'; $title='Кіру'; $err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  check_csrf();
  $q=$pdo->prepare('SELECT * FROM users WHERE email=?'); $q->execute([trim($_POST['email']??'')]); $u=$q->fetch();
  if ($u && password_verify($_POST['password']??'',$u['password'])) {
    session_regenerate_id(true);
    $_SESSION['user']=['id'=>$u['id'],'name'=>$u['name'],'role'=>$u['role']];
    header('Location: '.($u['role']==='admin'?'admin/index.php':'student.php')); exit;
  }
  $err='Email немесе құпиясөз қате.';
}
include 'includes/header.php'; ?>
<h1>Кіру</h1>
<?php if($err): ?><div class="alert"><?= e($err) ?></div><?php endif; ?>
<form method="post" class="stack"><?= csrf_field() ?>
  <label>Email<input type="email" name="email" required></label>
  <label>Құпиясөз<input type="password" name="password" required></label>
  <button>Кіру</button>
  <span class="meta">Аккаунтыңыз жоқ па? <a href="register.php">Тіркелу</a></span>
</form>
<?php include 'includes/footer.php'; ?>

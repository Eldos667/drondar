<?php require 'config.php'; $title='Тіркелу'; $err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  check_csrf();
  $name=trim($_POST['name']??''); $email=trim($_POST['email']??''); $pass=$_POST['password']??'';
  if ($name===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($pass)<6) $err='Аты-жөні, дұрыс email және кемінде 6 таңбалы құпиясөз енгізіңіз.';
  else {
    $q=$pdo->prepare('SELECT id FROM users WHERE email=?'); $q->execute([$email]);
    if ($q->fetch()) $err='Бұл email тіркелген. Кіру бетіне өтіңіз.';
    else {
      $pdo->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,"student")')->execute([$name,$email,password_hash($pass,PASSWORD_DEFAULT)]);
      $_SESSION['user']=['id'=>$pdo->lastInsertId(),'name'=>$name,'role'=>'student'];
      header('Location: student.php'); exit;
    }
  }
}
include 'includes/header.php'; ?>
<h1>Тіркелу</h1>
<?php if($err): ?><div class="alert"><?= e($err) ?></div><?php endif; ?>
<form method="post" class="stack"><?= csrf_field() ?>
  <label>Аты-жөні<input name="name" required value="<?= e($_POST['name']??'') ?>"></label>
  <label>Email<input type="email" name="email" required value="<?= e($_POST['email']??'') ?>"></label>
  <label>Құпиясөз (кемінде 6 таңба)<input type="password" name="password" required minlength="6"></label>
  <button>Тіркелу</button>
</form>
<?php include 'includes/footer.php'; ?>

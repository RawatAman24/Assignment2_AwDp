<?php require_once 'config.php';
if (is_logged_in()) redirect('index.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = trim($_POST['email'] ?? ''); $pw = $_POST['password'] ?? '';
    $st = $pdo->prepare('SELECT * FROM users WHERE email=?'); $st->execute([$email]); $u = $st->fetch();
    if ($u && password_verify($pw, $u['password'])) {
        if (!$u['status']) $error = 'Your account has been deactivated. Contact support.';
        else {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $u['id']; $_SESSION['user_name'] = $u['name'];
            $to = $_SESSION['redirect_after_login'] ?? 'index.php'; unset($_SESSION['redirect_after_login']);
            redirect($to);
        }
    } else $error = 'Invalid email or password.';
}
$pageTitle = 'Login'; require 'includes/header.php'; ?>
<section class="auth panel"><h2>Customer Login</h2>
<?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<form method="post"><?= csrf_field() ?>
<label>Email</label><input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
<label>Password</label><input type="password" name="password" required><br><br>
<button class="btn">Login</button> <span class="muted">New here? <a href="register.php"><u>Create an account</u></a></span></form></section>
<?php require 'includes/footer.php'; ?>

<?php require_once 'config.php';
if (is_logged_in()) redirect('index.php');
$errors = []; $d = ['name'=>'','email'=>'','mobile'=>'','address'=>'','city'=>'','state'=>'','pincode'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    foreach ($d as $k => $_) $d[$k] = trim($_POST[$k] ?? '');
    $pw = $_POST['password'] ?? ''; $cpw = $_POST['confirm'] ?? '';
    if (!preg_match("/^[A-Za-z .'-]{2,}$/", $d['name'])) $errors['name'] = 'Enter a valid name';
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Invalid email';
    if (!preg_match('/^[6-9][0-9]{9}$/', $d['mobile'])) $errors['mobile'] = 'Enter valid 10-digit mobile';
    if (strlen($pw) < 6 || !preg_match('/[A-Za-z]/', $pw) || !preg_match('/[0-9]/', $pw)) $errors['password'] = 'Min 6 chars with letters and numbers';
    if ($pw !== $cpw) $errors['confirm'] = 'Passwords do not match';
    if ($d['address'] === '') $errors['address'] = 'Address is required';
    if ($d['pincode'] !== '' && !preg_match('/^[0-9]{6}$/', $d['pincode'])) $errors['pincode'] = 'Pincode must be 6 digits';
    if (!$errors) {
        $c = $pdo->prepare('SELECT id FROM users WHERE email=?'); $c->execute([$d['email']]);
        if ($c->fetch()) $errors['email'] = 'Email already registered';
        else {
            $pdo->prepare('INSERT INTO users (name,email,mobile,password,address,city,state,pincode) VALUES (?,?,?,?,?,?,?,?)')
                ->execute([$d['name'], $d['email'], $d['mobile'], password_hash($pw, PASSWORD_DEFAULT), $d['address'], $d['city'], $d['state'], $d['pincode']]);
            flash('success', 'Registration successful! Please log in.');
            redirect('login.php');
        }
    }
}
$pageTitle = 'Register'; require 'includes/header.php'; ?>
<section class="auth panel"><h2>Create Account</h2>
<form method="post" novalidate><?= csrf_field() ?>
<div class="row">
<div><label>Full Name</label><input name="name" value="<?= e($d['name']) ?>"><span class="err"><?= $errors['name'] ?? '' ?></span></div>
<div><label>Email</label><input type="email" name="email" value="<?= e($d['email']) ?>"><span class="err"><?= $errors['email'] ?? '' ?></span></div>
<div><label>Mobile</label><input name="mobile" value="<?= e($d['mobile']) ?>"><span class="err"><?= $errors['mobile'] ?? '' ?></span></div>
<div><label>Pincode</label><input name="pincode" value="<?= e($d['pincode']) ?>"><span class="err"><?= $errors['pincode'] ?? '' ?></span></div>
<div><label>Password</label><input type="password" name="password"><span class="err"><?= $errors['password'] ?? '' ?></span></div>
<div><label>Confirm Password</label><input type="password" name="confirm"><span class="err"><?= $errors['confirm'] ?? '' ?></span></div>
<div><label>City</label><input name="city" value="<?= e($d['city']) ?>"></div>
<div><label>State</label><input name="state" value="<?= e($d['state']) ?>"></div></div>
<label>Address</label><textarea name="address" rows="3"><?= e($d['address']) ?></textarea><span class="err"><?= $errors['address'] ?? '' ?></span><br><br>
<button class="btn">Register</button> <span class="muted">Already registered? <a href="login.php"><u>Login</u></a></span></form></section>
<?php require 'includes/footer.php'; ?>

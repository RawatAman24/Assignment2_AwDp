<?php require_once 'config.php'; require_login();
$st = $pdo->prepare('SELECT * FROM users WHERE id=?'); $st->execute([$_SESSION['user_id']]); $u = $st->fetch();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $d = []; foreach (['name','email','mobile','address','city','state','pincode'] as $k) $d[$k] = trim($_POST[$k] ?? '');
    if ($d['name'] === '') $errors['name'] = 'Required';
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Invalid email';
    if (!preg_match('/^[6-9][0-9]{9}$/', $d['mobile'])) $errors['mobile'] = 'Enter valid 10-digit mobile';
    if ($d['pincode'] !== '' && !preg_match('/^[0-9]{6}$/', $d['pincode'])) $errors['pincode'] = 'Pincode must be 6 digits';
    if (!$errors) {
        $c = $pdo->prepare('SELECT id FROM users WHERE email=? AND id<>?'); $c->execute([$d['email'], $u['id']]);
        if ($c->fetch()) $errors['email'] = 'Email already used by another account';
    }
    if (!$errors) {
        $pdo->prepare('UPDATE users SET name=?,email=?,mobile=?,address=?,city=?,state=?,pincode=? WHERE id=?')
            ->execute([$d['name'], $d['email'], $d['mobile'], $d['address'], $d['city'], $d['state'], $d['pincode'], $u['id']]);
        $_SESSION['user_name'] = $d['name'];
        flash('success', 'Profile updated successfully.'); redirect('profile.php');
    }
    $u = array_merge($u, $d);
}
$pageTitle = 'My Profile'; require 'includes/header.php'; ?>
<section class="auth panel"><h2>My Profile</h2>
<form method="post"><?= csrf_field() ?><div class="row">
<?php foreach (['name'=>'Name','email'=>'Email','mobile'=>'Mobile','city'=>'City','state'=>'State','pincode'=>'Pincode'] as $k => $l): ?>
<div><label><?= $l ?></label><input name="<?= $k ?>" value="<?= e($u[$k]) ?>"><span class="err"><?= $errors[$k] ?? '' ?></span></div><?php endforeach; ?></div>
<label>Address</label><textarea name="address" rows="3"><?= e($u['address']) ?></textarea><br><br>
<button class="btn">Update Profile</button> <a class="btn light" href="orders.php">My Orders</a></form></section>
<?php require 'includes/footer.php'; ?>

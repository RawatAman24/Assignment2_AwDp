<?php require_once 'config.php'; require_login();
[$items, $total] = cart_items($pdo);
if (!$items) { flash('error', 'Your cart is empty.'); redirect('cart.php'); }
$u = $pdo->prepare('SELECT * FROM users WHERE id=?'); $u->execute([$_SESSION['user_id']]); $user = $u->fetch();
$errors = []; $d = [
  'name' => $user['name'], 'email' => $user['email'], 'mobile' => $user['mobile'],
  'billing' => $user['address'], 'shipping' => $user['address'], 'city' => $user['city'],
  'state' => $user['state'], 'pincode' => $user['pincode'], 'payment_method' => 'COD'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    foreach (['name','email','mobile','billing','shipping','city','state','pincode','payment_method'] as $k) $d[$k] = trim($_POST[$k] ?? '');
    foreach (['name','billing','shipping','city','state'] as $k) if ($d[$k] === '') $errors[$k] = 'Required';
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Invalid email';
    if (!preg_match('/^[6-9][0-9]{9}$/', $d['mobile'])) $errors['mobile'] = 'Enter valid 10-digit mobile';
    if (!preg_match('/^[0-9]{6}$/', $d['pincode'])) $errors['pincode'] = 'Enter 6-digit pincode';
    if (!in_array($d['payment_method'], ['COD', 'UPI', 'Card'])) $errors['payment_method'] = 'Select a method';
    if (!$errors) { $_SESSION['checkout'] = $d; redirect('payment.php'); }
}
$pageTitle = 'Checkout'; require 'includes/header.php'; ?>
<section class="block"><h2 class="title">Checkout</h2>
<form method="post" class="panel"><?= csrf_field() ?>
<h3>Customer &amp; Delivery Details</h3>
<div class="row">
<?php
$fields = ['name'=>'Customer Name','email'=>'Email','mobile'=>'Mobile Number','city'=>'City','state'=>'State','pincode'=>'Pincode'];
foreach ($fields as $k => $l): ?>
<div><label><?= $l ?></label><input name="<?= $k ?>" value="<?= e($d[$k]) ?>"><span class="err"><?= $errors[$k] ?? '' ?></span></div>
<?php endforeach; ?></div>
<div class="row"><div><label>Billing Address</label><textarea name="billing" rows="3"><?= e($d['billing']) ?></textarea><span class="err"><?= $errors['billing'] ?? '' ?></span></div>
<div><label>Shipping Address</label><textarea name="shipping" rows="3"><?= e($d['shipping']) ?></textarea><span class="err"><?= $errors['shipping'] ?? '' ?></span></div></div>
<h3 style="margin-top:20px">Order Summary</h3>
<div class="table-wrap"><table><tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
<?php foreach ($items as $i): ?><tr><td><?= e($i['name']) ?></td><td><?= $i['qty'] ?></td><td><?= money($i['unit']) ?></td><td><?= money($i['line']) ?></td></tr><?php endforeach; ?></table></div>
<div class="summary total"><span>Total Amount</span><span><?= money($total) ?></span></div>
<h3>Payment Method</h3>
<?php foreach (['COD'=>'Cash on Delivery','UPI'=>'UPI','Card'=>'Credit / Debit Card'] as $k => $l): ?>
<label><input type="radio" style="width:auto" name="payment_method" value="<?= $k ?>" <?= $d['payment_method'] === $k ? 'checked' : '' ?>> <?= $l ?></label><?php endforeach; ?>
<span class="err"><?= $errors['payment_method'] ?? '' ?></span><br><br>
<button class="btn">Continue to Payment</button></form></section>
<?php require 'includes/footer.php'; ?>

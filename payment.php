<?php require_once 'config.php'; require_login();
[$items, $total] = cart_items($pdo);
$d = $_SESSION['checkout'] ?? null;
if (!$items || !$d) redirect('checkout.php');
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    // Demo validation of payment details (no real gateway)
    if ($d['payment_method'] === 'UPI' && !preg_match('/^[\w.\-]{2,}@[\w]{2,}$/', trim($_POST['upi'] ?? ''))) $errors[] = 'Enter a valid UPI ID (e.g. name@bank).';
    if ($d['payment_method'] === 'Card') {
        if (!preg_match('/^[0-9]{16}$/', preg_replace('/\s+/', '', $_POST['card'] ?? ''))) $errors[] = 'Card number must be 16 digits.';
        if (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', trim($_POST['exp'] ?? ''))) $errors[] = 'Expiry must be MM/YY.';
        if (!preg_match('/^[0-9]{3}$/', trim($_POST['cvv'] ?? ''))) $errors[] = 'CVV must be 3 digits.';
    }
    if (!$errors) {
        try {
            $pdo->beginTransaction();
            // Re-check and lock stock
            foreach ($items as $i) {
                $s = $pdo->prepare('SELECT stock FROM products WHERE id=? FOR UPDATE'); $s->execute([$i['id']]);
                if ((int)$s->fetchColumn() < $i['qty']) throw new Exception('Insufficient stock for ' . $i['name']);
            }
            $pstatus = $d['payment_method'] === 'COD' ? 'Pending' : 'Paid';
            $pdo->prepare('INSERT INTO orders (user_id,customer_name,email,mobile,billing_address,shipping_address,city,state,pincode,total,payment_method,payment_status)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?)')->execute([$_SESSION['user_id'], $d['name'], $d['email'], $d['mobile'], $d['billing'], $d['shipping'], $d['city'], $d['state'], $d['pincode'], $total, $d['payment_method'], $pstatus]);
            $oid = $pdo->lastInsertId();
            foreach ($items as $i) {
                $pdo->prepare('INSERT INTO order_items (order_id,product_id,product_name,price,quantity) VALUES (?,?,?,?,?)')->execute([$oid, $i['id'], $i['name'], $i['unit'], $i['qty']]);
                $pdo->prepare('UPDATE products SET stock=stock-? WHERE id=?')->execute([$i['qty'], $i['id']]);
            }
            $pdo->commit();
            unset($_SESSION['cart'], $_SESSION['checkout']);
            redirect('order_success.php?id=' . $oid);
        } catch (Exception $ex) { $pdo->rollBack(); $errors[] = $ex->getMessage(); }
    }
}
$pageTitle = 'Payment'; require 'includes/header.php'; ?>
<section class="block auth"><h2 class="title">Payment</h2>
<div class="panel">
<div class="summary"><span>Payable Amount</span><strong><?= money($total) ?></strong></div>
<div class="summary"><span>Method</span><strong><?= e(['COD'=>'Cash on Delivery','UPI'=>'UPI','Card'=>'Credit / Debit Card'][$d['payment_method']]) ?></strong></div>
<?php foreach ($errors as $er): ?><div class="alert error"><?= e($er) ?></div><?php endforeach; ?>
<form method="post"><?= csrf_field() ?>
<?php if ($d['payment_method'] === 'UPI'): ?><label>UPI ID</label><input name="upi" placeholder="name@bank">
<?php elseif ($d['payment_method'] === 'Card'): ?>
<label>Card Number</label><input name="card" placeholder="1234 5678 9012 3456" maxlength="19">
<div class="row"><div><label>Expiry (MM/YY)</label><input name="exp" placeholder="08/28"></div><div><label>CVV</label><input name="cvv" type="password" maxlength="3"></div></div>
<?php else: ?><p class="muted">Pay in cash when your order is delivered.</p><?php endif; ?>
<p class="muted" style="margin-top:8px">Demo payment: no real transaction is made.</p><br>
<div class="actions"><button class="btn green"><?= $d['payment_method'] === 'COD' ? 'Place Order' : 'Pay &amp; Place Order' ?></button><a class="btn light" href="checkout.php">Back</a></div>
</form></div></section>
<?php require 'includes/footer.php'; ?>

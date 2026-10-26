<?php require_once 'config.php'; require_login(); $pageTitle = 'Order Details'; require 'includes/header.php';
$o = $pdo->prepare('SELECT * FROM orders WHERE id=? AND user_id=?'); $o->execute([(int)($_GET['id'] ?? 0), $_SESSION['user_id']]); $o = $o->fetch();
if (!$o) { echo '<div class="alert error">Order not found.</div>'; require 'includes/footer.php'; exit; }
$it = $pdo->prepare('SELECT * FROM order_items WHERE order_id=?'); $it->execute([$o['id']]); $items = $it->fetchAll(); ?>
<section class="block"><div class="panel"><h2>Order #<?= $o['id'] ?></h2>
<p><?= date('d M Y, h:i A', strtotime($o['created_at'])) ?> | Status: <span class="status <?= $o['order_status'] ?>"><?= $o['order_status'] ?></span> | Payment: <?= e($o['payment_method']) ?> (<?= $o['payment_status'] ?>)</p>
<p style="margin:10px 0"><strong>Ship to:</strong> <?= e($o['customer_name']) ?>, <?= e($o['shipping_address']) ?>, <?= e($o['city']) ?>, <?= e($o['state']) ?> - <?= e($o['pincode']) ?></p>
<div class="table-wrap"><table><tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
<?php foreach ($items as $i): ?><tr><td><?= e($i['product_name']) ?></td><td><?= $i['quantity'] ?></td><td><?= money($i['price']) ?></td><td><?= money($i['price'] * $i['quantity']) ?></td></tr><?php endforeach; ?></table></div>
<div class="summary total"><span>Total</span><span><?= money($o['total']) ?></span></div>
<a class="btn dark" href="orders.php">Back to Orders</a></div></section>
<?php require 'includes/footer.php'; ?>

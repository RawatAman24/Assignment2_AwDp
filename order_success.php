<?php require_once 'config.php'; require_login(); $pageTitle = 'Order Placed'; require 'includes/header.php';
$o = $pdo->prepare('SELECT * FROM orders WHERE id=? AND user_id=?'); $o->execute([(int)($_GET['id'] ?? 0), $_SESSION['user_id']]); $o = $o->fetch();
if (!$o) { echo '<div class="alert error">Order not found.</div>'; require 'includes/footer.php'; exit; }
$it = $pdo->prepare('SELECT * FROM order_items WHERE order_id=?'); $it->execute([$o['id']]); $items = $it->fetchAll(); ?>
<section class="block"><div class="panel">
<div class="alert success"><strong>✔ Thank you! Your order has been placed successfully.</strong></div>
<p><strong>Order ID:</strong> #<?= $o['id'] ?> &nbsp; <strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($o['created_at'])) ?></p>
<p><strong>Payment:</strong> <?= e($o['payment_method']) ?> (<?= e($o['payment_status']) ?>)</p>
<h3 style="margin-top:14px">Customer Information</h3>
<p><?= e($o['customer_name']) ?> | <?= e($o['email']) ?> | <?= e($o['mobile']) ?><br>Ship to: <?= e($o['shipping_address']) ?>, <?= e($o['city']) ?>, <?= e($o['state']) ?> - <?= e($o['pincode']) ?></p>
<h3 style="margin-top:14px">Ordered Products</h3>
<div class="table-wrap"><table><tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
<?php foreach ($items as $i): ?><tr><td><?= e($i['product_name']) ?></td><td><?= $i['quantity'] ?></td><td><?= money($i['price']) ?></td><td><?= money($i['price'] * $i['quantity']) ?></td></tr><?php endforeach; ?></table></div>
<div class="summary total"><span>Total Amount</span><span><?= money($o['total']) ?></span></div>
<div class="actions"><a class="btn" href="products.php">Continue Shopping</a><a class="btn dark" href="orders.php">View Order History</a></div>
</div></section>
<?php require 'includes/footer.php'; ?>

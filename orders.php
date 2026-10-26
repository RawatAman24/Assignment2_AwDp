<?php require_once 'config.php'; require_login(); $pageTitle = 'Order History'; require 'includes/header.php';
$st = $pdo->prepare('SELECT o.*, (SELECT COALESCE(SUM(quantity),0) FROM order_items WHERE order_id=o.id) AS items FROM orders o WHERE user_id=? ORDER BY o.id DESC');
$st->execute([$_SESSION['user_id']]); $orders = $st->fetchAll(); ?>
<section class="block"><h2 class="title">My Orders</h2>
<?php if (!$orders): ?><div class="panel" style="text-align:center">You haven't placed any orders yet.<br><br><a class="btn" href="products.php">Start Shopping</a></div><?php else: ?>
<div class="table-wrap"><table><tr><th>Order ID</th><th>Date</th><th>Products</th><th>Total</th><th>Payment</th><th>Status</th><th></th></tr>
<?php foreach ($orders as $o): ?><tr><td>#<?= $o['id'] ?></td><td><?= date('d M Y', strtotime($o['created_at'])) ?></td><td><?= $o['items'] ?></td>
<td><?= money($o['total']) ?></td><td><?= e($o['payment_method']) ?></td><td><span class="status <?= $o['order_status'] ?>"><?= $o['order_status'] ?></span></td>
<td><a class="btn sm" href="order_details.php?id=<?= $o['id'] ?>">View Details</a></td></tr><?php endforeach; ?></table></div><?php endif; ?></section>
<?php require 'includes/footer.php'; ?>

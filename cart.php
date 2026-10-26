<?php require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? ''; $pid = (int)($_POST['product_id'] ?? 0);
    if ($action === 'add') {
        $st = $pdo->prepare('SELECT stock FROM products WHERE id=? AND status=1'); $st->execute([$pid]);
        $stock = $st->fetchColumn();
        if ($stock === false) flash('error', 'Product not available.');
        else {
            $qty = max(1, (int)($_POST['qty'] ?? 1));
            $new = min(($_SESSION['cart'][$pid] ?? 0) + $qty, (int)$stock);
            if ($new < 1) flash('error', 'Out of stock.');
            else { $_SESSION['cart'][$pid] = $new; flash('success', 'Added to cart.'); }
            if (!empty($_POST['buy_now'])) redirect('checkout.php');
        }
        redirect($_SERVER['HTTP_REFERER'] ?? 'cart.php');
    }
    if ($action === 'update') {
        foreach ($_POST['qty'] ?? [] as $id => $q) {
            $st = $pdo->prepare('SELECT stock FROM products WHERE id=?'); $st->execute([(int)$id]);
            $stock = (int)$st->fetchColumn(); $q = (int)$q;
            if ($q <= 0) unset($_SESSION['cart'][$id]); else $_SESSION['cart'][$id] = min($q, max(1, $stock));
        }
        flash('success', 'Cart updated.');
    }
    if ($action === 'remove') { unset($_SESSION['cart'][$pid]); flash('success', 'Item removed.'); }
    redirect('cart.php');
}
$pageTitle = 'Shopping Cart'; require 'includes/header.php';
[$items, $total] = cart_items($pdo); ?>
<section class="block"><h2 class="title">Shopping Cart</h2>
<?php if (!$items): ?><div class="panel" style="text-align:center"><p>Your cart is empty.</p><br><a class="btn" href="products.php">Continue Shopping</a></div>
<?php else: ?>
<form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="update">
<div class="table-wrap"><table><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr>
<?php foreach ($items as $i): ?>
<tr><td><img class="cart-img" src="<?= img_url($i['image']) ?>" alt=""> <?= e($i['name']) ?></td>
<td><?= money($i['unit']) ?></td>
<td><input class="qty" type="number" min="0" max="<?= (int)$i['stock'] ?>" name="qty[<?= $i['id'] ?>]" value="<?= $i['qty'] ?>"></td>
<td><?= money($i['line']) ?></td>
<td><button form="rm<?= $i['id'] ?>" class="btn sm gray" data-confirm="Remove this item?">Remove</button></td></tr>
<?php endforeach; ?></table></div>
<div class="panel"><div class="summary"><span>Subtotal</span><span><?= money($total) ?></span></div>
<div class="summary"><span>Shipping</span><span>FREE</span></div>
<div class="summary total"><span>Total</span><span><?= money($total) ?></span></div>
<div class="actions"><button class="btn dark">Update Cart</button><a class="btn" href="checkout.php">Proceed to Checkout</a></div></div>
</form>
<?php foreach ($items as $i): ?>
<form id="rm<?= $i['id'] ?>" method="post"><?= csrf_field() ?><input type="hidden" name="action" value="remove"><input type="hidden" name="product_id" value="<?= $i['id'] ?>"></form>
<?php endforeach; endif; ?></section>
<?php require 'includes/footer.php'; ?>

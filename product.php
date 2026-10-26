<?php $pageTitle = 'Product Details'; require 'includes/header.php';
$id = (int)($_GET['id'] ?? 0);
$st = $pdo->prepare('SELECT p.*, c.name AS cat FROM products p JOIN categories c ON c.id=p.category_id WHERE p.id=? AND p.status=1');
$st->execute([$id]); $p = $st->fetch();
if (!$p) { echo '<div class="alert error">Product not found.</div>'; require 'includes/footer.php'; exit; }
$specs = array_filter(array_map('trim', explode("\n", (string)$p['specifications']))); ?>
<section class="block detail">
  <div><img src="<?= img_url($p['image']) ?>" alt="<?= e($p['name']) ?>"></div>
  <div>
    <h1><?= e($p['name']) ?></h1>
    <p class="stars"><?= stars($p['rating']) ?> <span class="muted">(<?= $p['rating'] ?>)</span></p>
    <p class="muted">Category: <a href="products.php?category=<?= $p['category_id'] ?>"><?= e($p['cat']) ?></a></p>
    <p class="price" style="font-size:1.6rem;margin:10px 0"><?= money(final_price($p)) ?>
      <?php if ($p['discount'] > 0): ?><span class="old"><?= money($p['price']) ?></span><span class="off"><?= (float)$p['discount'] ?>% discount</span><?php endif; ?></p>
    <p><?= nl2br(e($p['description'])) ?></p>
    <p style="margin:10px 0"><strong>Availability:</strong>
      <?= $p['stock'] > 0 ? '<span style="color:#16a34a">In stock (' . (int)$p['stock'] . ' available)</span>' : '<span style="color:#b91c1c">Out of stock</span>' ?></p>
    <?php if ($specs): ?><h3>Specifications</h3><table><?php foreach ($specs as $s): $kv = explode(':', $s, 2); ?>
      <tr><th style="width:40%"><?= e($kv[0]) ?></th><td><?= e($kv[1] ?? '') ?></td></tr><?php endforeach; ?></table><?php endif; ?>
    <?php if ($p['stock'] > 0): ?>
    <form action="cart.php" method="post" style="margin-top:16px"><?= csrf_field() ?>
      <input type="hidden" name="action" value="add"><input type="hidden" name="product_id" value="<?= $p['id'] ?>">
      <label>Quantity</label><input class="qty" type="number" name="qty" value="1" min="1" max="<?= (int)$p['stock'] ?>">
      <div class="actions"><button class="btn">Add to Cart</button>
      <button class="btn dark" name="buy_now" value="1">Buy Now</button></div>
    </form><?php endif; ?>
  </div>
</section>
<?php require 'includes/footer.php'; ?>

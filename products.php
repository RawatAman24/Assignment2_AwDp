<?php $pageTitle = 'Products'; require 'includes/header.php';
$cid = (int)($_GET['category'] ?? 0); $q = trim($_GET['q'] ?? '');
$sql = 'SELECT * FROM products WHERE status=1'; $args = [];
if ($cid) { $sql .= ' AND category_id=?'; $args[] = $cid; }
if ($q !== '') { $sql .= ' AND (name LIKE ? OR short_desc LIKE ?)'; $args[] = "%$q%"; $args[] = "%$q%"; }
$sql .= ' ORDER BY id DESC';
$st = $pdo->prepare($sql); $st->execute($args); $products = $st->fetchAll();
$catName = 'All Products';
if ($cid) { $s = $pdo->prepare('SELECT name FROM categories WHERE id=?'); $s->execute([$cid]); $catName = $s->fetchColumn() ?: 'Category'; }
$allCats = $pdo->query('SELECT id,name FROM categories WHERE status=1')->fetchAll(); ?>
<section class="block"><h2 class="title"><?= e($catName) ?></h2>
<p style="text-align:center;margin-bottom:20px">
  <a class="btn sm <?= $cid ? 'light' : '' ?>" href="products.php">All</a>
  <?php foreach ($allCats as $c): ?><a class="btn sm <?= $cid == $c['id'] ? '' : 'light' ?>" href="products.php?category=<?= $c['id'] ?>"><?= e($c['name']) ?></a> <?php endforeach; ?></p>
<?php if (!$products): ?><p style="text-align:center" class="muted">No products found.</p><?php endif; ?>
<div class="grid"><?php foreach ($products as $p) include 'includes/product_card.php'; ?></div></section>
<?php require 'includes/footer.php'; ?>

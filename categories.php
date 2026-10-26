<?php $pageTitle = 'Categories'; require 'includes/header.php';
$cats = $pdo->query('SELECT * FROM categories WHERE status=1 ORDER BY name')->fetchAll(); ?>
<section class="block"><h2 class="title">Shop by Category</h2><div class="grid">
<?php foreach ($cats as $c): ?>
  <div class="card"><img src="<?= img_url($c['image']) ?>" alt="">
    <div class="body"><h3><?= e($c['name']) ?></h3><p class="muted"><?= e($c['description']) ?></p>
    <div class="actions"><a class="btn sm" href="products.php?category=<?= $c['id'] ?>">View Products</a></div></div></div>
<?php endforeach; ?></div></section>
<?php require 'includes/footer.php'; ?>

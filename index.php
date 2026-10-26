<?php $pageTitle = 'Home'; require 'includes/header.php';
$cats = $pdo->query('SELECT * FROM categories WHERE status=1 ORDER BY id LIMIT 4')->fetchAll();
$featured = $pdo->query('SELECT * FROM products WHERE status=1 AND is_featured=1 ORDER BY id DESC LIMIT 4')->fetchAll();
$latest = $pdo->query('SELECT * FROM products WHERE status=1 ORDER BY created_at DESC, id DESC LIMIT 8')->fetchAll();
$offers = $pdo->query('SELECT * FROM products WHERE status=1 AND discount>0 ORDER BY discount DESC LIMIT 4')->fetchAll();
?>
</main>
<div class="hero">
  <div class="container">
    <div class="slide active"><h1>New Season Collection</h1><p>Fresh styles for everyone, delivered to your door.</p><a class="btn light" href="products.php">Shop Now</a></div>
    <div class="slide"><h1>Flat 30% Off on Dresses</h1><p>Limited time summer sale on women's wear.</p><a class="btn light" href="products.php">Grab the Deal</a></div>
    <div class="slide"><h1>Step Up Your Footwear</h1><p>Sneakers &amp; formals at unbeatable prices.</p><a class="btn light" href="categories.php">Explore</a></div>
  </div>
</div>
<main class="container">
<section class="block"><h2 class="title">Featured Categories</h2>
  <div class="grid">
  <?php foreach ($cats as $c): ?>
    <div class="card"><a href="products.php?category=<?= $c['id'] ?>"><img src="<?= img_url($c['image']) ?>" alt=""></a>
      <div class="body"><h3><?= e($c['name']) ?></h3><p class="muted"><?= e($c['description']) ?></p>
      <div class="actions"><a class="btn sm" href="products.php?category=<?= $c['id'] ?>">View Products</a></div></div></div>
  <?php endforeach; ?></div></section>

<section class="block"><h2 class="title">Featured Products</h2>
  <div class="grid"><?php foreach ($featured as $p) include 'includes/product_card.php'; ?></div></section>

<section class="block"><div class="promo"><h2>Mega Offer: Up to <?= $offers ? (int)$offers[0]['discount'] : 30 ?>% OFF</h2>
  <p>Use our best deals before they're gone!</p></div>
  <div class="grid" style="margin-top:22px"><?php foreach ($offers as $p) include 'includes/product_card.php'; ?></div></section>

<section class="block"><h2 class="title">New Arrivals</h2>
  <div class="grid"><?php foreach ($latest as $p) include 'includes/product_card.php'; ?></div></section>
<?php require 'includes/footer.php'; ?>

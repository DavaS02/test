<?php
$cartsResponse = file_get_contents('https://fakestoreapi.com/carts');
$carts = json_decode($cartsResponse, true);


function fetchProduct($productId) {
    $response = file_get_contents("https://fakestoreapi.com/products/$productId");
    return json_decode($response, true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>API Cart List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
<div class="container my-5">
  <h1 class="text-center mb-4">🛒 Carts from API</h1>

  <?php foreach ($carts as $cart): ?>
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-primary text-white">
        <strong>Cart #<?= $cart['id'] ?></strong> – User ID: <?= $cart['userId'] ?> | Date: <?= htmlspecialchars($cart['date']) ?>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <?php foreach ($cart['products'] as $item): 
              $product = fetchProduct($item['productId']);
          ?>
            <div class="col-md-4">
              <div class="card h-100">
                <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top p-3" alt="<?= htmlspecialchars($product['title']) ?>" style="height:200px; object-fit:contain;">
                <div class="card-body">
                  <h5 class="card-title"><?= htmlspecialchars($product['title']) ?></h5>
                  <p class="text-muted small mb-1">Qty: <?= $item['quantity'] ?></p>
                  <p class="price-tag mb-0">$<?= number_format($product['price'], 2) ?> × <?= $item['quantity'] ?> = $<?= number_format($product['price'] * $item['quantity'], 2) ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
</body>
</html>

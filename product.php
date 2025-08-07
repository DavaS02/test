<?php 
session_start ();
if (!isset ($_GET['id'])){
    die('Product ID not specified.');
}

$id = intval($_GET['id']);
$response = @file_get_contents("https://fakestoreapi.com/products/$id");

if (!$response){
    die('Product not found.');
}

$product = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['title'])?> - Product Details</title>
    <link href= "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <div class= "container my-5">
        <div class="row g-4">
            <div class="col-md-5">
                 <img src="<?=htmlspecialchars($product['image'])?>" alt="<?= htmlspecialchars ($product['title'])?>" class="img-fluid p-3 border bg-white" style="max-height: 400px; object-fit: contain;">
</div>
<div class="col-md-7">
        <h2><?=htmlspecialchars($product['title'])?> </h2>
        <p class="text-muted mb-2"> Category: <?= htmlspecialchars($product['category'])?> </p>
        <h4 class="price-tag mb-3">$<?=number_format($product['price'], 2)?> </h4>
        <p><?=htmlspecialchars($product['description'])?> </p>
        <form method="POST" action="add_to_cart.php">
            <input type ="hidden" name="id" value="<?= $product['id']?>">
        <a href="#" class="btn btn-sucess btn-lg mt-3 buy-btn" role="button">🛒 Add to Cart </a>
        </div>
    </div>
</div>
        
</body>
</html>

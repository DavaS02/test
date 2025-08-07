<?php include 'helpers/functions.php';

$products= fetchProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Api Product List</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <div class ="container my -5">
    <h1 class="mb-4 text-center fw-bold"> Product Catalog</h1>


    <div class="row g-4">
        <?php if (!empty($products)): ?>
        <?php foreach ($products as $product):?>
            <div class= "col-sm-6 col-md-4 col-lg-3">
                 <div class="card h-100 shadow-sm">
                        <img
                         src="<?= htmlspecialchars($product['image']) ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($product['title']) ?>"
                             >
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($product['title']) ?></h5>
                            <p class="card-text small text-muted"><?= htmlspecialchars($product['description']) ?></p>
                            <p class="pric-tag mb-1">$<?=number_format($product['price'],2) ?></p>
                            <p class="text-muted small">Category: <?= htmlspecialchars($product['category']) ?></p>
                            <a href= "product.php?id=<?=$product['id']?>"target= "_blank" rel= "noopener noreferrer" class="btn_primary mt-auto">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center text-danger">Failed to load products.Please try again later. </php>        
                </div>
                <?php endif; ?>
    </div>
            </div>
   
            
        

</body>
</html>

<?php 
session_start();

if (!isset($POST['id'])){
    die("Product ID missing");
}

$id= inval($_POST['id']);

$response = @file_get_contents("https://fakestoreapi.com/products/$id");

if (!$response){
    die("Invalid product ID");
}

$product = json_decode($response, true);

if (isset($_SESSION['cart'])) {
    $_SESSION['cart']=[];
}

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['quantity'] +=1;
} else {
    $_SESSION['cart'][$id]=[
        'id'=> $product['id'],
        'title'=> $product['title'],
        'price'=> $product['price'],
        'image'=> $product['image'],
        'quantity'=>1
    ];
    
}

header("Location: cart.php");
exit;
?>

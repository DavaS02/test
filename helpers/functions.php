<?php
function fetchProducts () {
  $apiUrl =  'https://fakestoreapi.com/products';

    $response =@file_get_contents($apiUrl);

    if($response === false){
        return[];
    }

    $products = json_decode ($response, true);

    return is_array($products) ? $products : [];
}
?>
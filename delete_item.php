<?php
session_start();

if (isset($_GET['id']) && isset($_SESSION['cart'])) {
    $book_id = $_GET['id'];
    
    // Remove item from cart
    if (isset($_SESSION['cart'][$book_id])) {
        unset($_SESSION['cart'][$book_id]);
    }
}

// Redirect back to cart
header("Location: cart.php");
exit();
?>
<?php
include('include/_authen.php');
include('connections/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Create order
        $user_id = $_SESSION['user_id'];
        $total_amount = 0;
        
        // Calculate total and check stock
        foreach ($_SESSION['cart'] as $book_id => $quantity) {
            $stmt = $conn->prepare("SELECT price, stock_quantity FROM books WHERE book_id = ?");
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $book = $stmt->get_result()->fetch_assoc();
            
            if ($book['stock_quantity'] < $quantity) {
                throw new Exception("Insufficient stock for some items");
            }
            
            $total_amount += $book['price'] * $quantity;
        }
        
        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("id", $user_id, $total_amount);
        $stmt->execute();
        $order_id = $conn->insert_id;
        
        // Insert order items and update stock
        foreach ($_SESSION['cart'] as $book_id => $quantity) {
            $stmt = $conn->prepare("SELECT price FROM books WHERE book_id = ?");
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $book = $stmt->get_result()->fetch_assoc();
            
            // Insert order item
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $order_id, $book_id, $quantity, $book['price']);
            $stmt->execute();
            
            // Update stock
            $stmt = $conn->prepare("UPDATE books SET stock_quantity = stock_quantity - ? WHERE book_id = ?");
            $stmt->bind_param("ii", $quantity, $book_id);
            $stmt->execute();
        }
        
        // Commit transaction
        $conn->commit();
        
        // Clear cart
        unset($_SESSION['cart']);
        
        // Redirect to order confirmation
        header("Location: order_detail.php?id=$order_id");
        exit();
        
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        $error = $e->getMessage();
    }
}

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - BookNook</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('include/header_nav.php'); ?>
    
    <main>
        <div class="checkout-container">
            <h1>Checkout</h1>
            
            <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
            
            <div class="order-summary">
                <h2>Order Summary</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($_SESSION['cart'] as $book_id => $quantity):
                            $stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
                            $stmt->bind_param("i", $book_id);
                            $stmt->execute();
                            $book = $stmt->get_result()->fetch_assoc();
                            
                            if ($book):
                                $subtotal = $book['price'] * $quantity;
                                $total += $subtotal;
                        ?>
                            <tr>
                                <td><?php echo $book['title']; ?></td>
                                <td><?php echo $quantity; ?></td>
                                <td>$<?php echo number_format($book['price'], 2); ?></td>
                                <td>$<?php echo number_format($subtotal, 2); ?></td>
                            </tr>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><strong>Total:</strong></td>
                            <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <form method="post" action="" class="checkout-form">
                <button type="submit" class="place-order">Place Order</button>
                <a href="cart.php" class="back-to-cart">Back to Cart</a>
            </form>
        </div>
    </main>
    
    <?php include('include/footer.php'); ?>
</body>
</html>

<?php $conn->close(); ?>
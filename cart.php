<?php
session_start();
include('include/header_nav.php');
include('connections/conn.php');

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $book_id = (int)$_POST['book_id'];
    $quantity = (int)($_POST['quantity'] ?? 1);
    
    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id] += $quantity;
    } else {
        $_SESSION['cart'][$book_id] = $quantity;
    }
    
    header('Location: cart.php');
    exit();
}

// Remove from cart
if (isset($_GET['remove'])) {
    $book_id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$book_id]);
    header('Location: cart.php');
    exit();
}

// Update quantities
if (isset($_POST['update'])) {
    foreach ($_POST['quantities'] as $book_id => $quantity) {
        $book_id = (int)$book_id;
        $quantity = (int)$quantity;
        if ($quantity > 0) {
            $_SESSION['cart'][$book_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$book_id]);
        }
    }
    header('Location: cart.php');
    exit();
}
?>

<main>
    <div class="cart-container">
        <h1>Shopping Cart</h1>
        
        <?php if (empty($_SESSION['cart'])): ?>
            <div class="empty-cart">
                <p>Your cart is empty.</p>
                <a href="books.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php else: ?>
            <form method="post" action="">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
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
                                <td class="book-cell">
                                    <img src="<?php echo htmlspecialchars($book['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($book['title']); ?>" 
                                         class="cart-thumb">
                                    <div class="book-info">
                                        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                                        <p>by <?php echo htmlspecialchars($book['author']); ?></p>
                                    </div>
                                </td>
                                <td class="price-cell">$<?php echo number_format($book['price'], 2); ?></td>
                                <td class="quantity-cell">
                                    <input type="number" 
                                           name="quantities[<?php echo $book_id; ?>]" 
                                           value="<?php echo $quantity; ?>" 
                                           min="0" 
                                           max="<?php echo $book['stock_quantity']; ?>"
                                           class="quantity-input">
                                </td>
                                <td class="total-cell">$<?php echo number_format($subtotal, 2); ?></td>
                                <td class="action-cell">
                                    <a href="?remove=<?php echo $book_id; ?>" 
                                       class="btn btn-danger"
                                       onclick="return confirm('Remove this item from cart?');">
                                        Remove
                                    </a>
                                </td>
                            </tr>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Total:</strong></td>
                            <td colspan="2" class="cart-total"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="cart-actions">
                    <button type="submit" name="update" class="btn btn-secondary">Update Cart</button>
                    <a href="finalize_order.php" class="btn btn-primary">Proceed to Checkout</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php 
include('include/footer.php');
$conn->close();
?>
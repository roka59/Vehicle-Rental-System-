<?php 
include('include/header_nav.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: backend/_user_login.php");
    exit();
}

// Get book ID from URL
$book_id = $_GET['id'] ?? 0;

include('connections/conn.php');
// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();
$conn->close();
?>

<main>
    <?php if($book): ?>
    <div class="book-detail">
        <div class="book-images">
            <img src="<?php echo htmlspecialchars($book['image_url']); ?>" 
                 alt="<?php echo htmlspecialchars($book['title']); ?>">
        </div>
        <div class="book-info">
            <h1><?php echo htmlspecialchars($book['title']); ?></h1>
            <h2>by <?php echo htmlspecialchars($book['author']); ?></h2>
            <p class="price">$<?php echo number_format($book['price'], 2); ?></p>
            <p class="description"><?php echo htmlspecialchars($book['description']); ?></p>
            
            <form action="cart.php" method="post">
                <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" 
                           value="1" min="1" max="<?php echo $book['stock_quantity']; ?>"
                           class="quantity-input">
                </div>
                <button type="submit" class="add-to-cart">Add to Cart</button>
            </form>
        </div>
    </div>
    <?php else: ?>
    <p class="error">Book not found</p>
    <?php endif; ?>
</main>

<?php include('include/footer.php'); ?>
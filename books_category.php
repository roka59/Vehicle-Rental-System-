<?php
include('include/header_nav.php');
include('connections/conn.php');

// Get category_id from URL parameter with validation
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($category_id <= 0) {
    header('Location: categories.php');
    exit();
}

// Get category info
$stmt = $conn->prepare("SELECT * FROM categories WHERE category_id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: categories.php');
    exit();
}

$category = $result->fetch_assoc();

// Get books in this category
$stmt = $conn->prepare("SELECT * FROM books WHERE category_id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$books = $stmt->get_result();
?>

<main>
    <div class="category-container">
        <h1><?php echo htmlspecialchars($category['category_name']); ?></h1>
        <p class="category-description"><?php echo htmlspecialchars($category['description']); ?></p>
        
        <div class="book-grid">
            <?php while($book = $books->fetch_assoc()): ?>
                <div class="book-card">
                    <a href="books_detail.php?id=<?php echo (int)$book['book_id']; ?>">
                        <img src="<?php echo htmlspecialchars($book['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($book['title']); ?>">
                        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p>by <?php echo htmlspecialchars($book['author']); ?></p>
                        <p class="price">$<?php echo number_format($book['price'], 2); ?></p>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>

<?php 
include('include/footer.php');
$conn->close();
?>
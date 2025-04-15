<?php include('include/header_nav.php'); ?>

<main>
    <div class="books-container">
        <h1>All Books</h1>
        
        <div class="category-filter">
            <?php
            include('connections/conn.php');
            $stmt = $conn->prepare("SELECT * FROM categories");
            $stmt->execute();
            $categories = $stmt->get_result();
            
            while($cat = $categories->fetch_assoc()) {
                echo '<a href="books_category.php?id='.htmlspecialchars($cat['category_id']).'" class="btn btn-secondary category-btn">';
                echo htmlspecialchars($cat['category_name']);
                echo '</a>';
            }
            ?>
        </div>

        <div class="book-grid">
            <?php
            $stmt = $conn->prepare("SELECT * FROM books ORDER BY title");
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while($book = $result->fetch_assoc()) {
                    ?>
                    <div class="book-card">
                        <a href="books_detail.php?id=<?php echo (int)$book['book_id']; ?>">
                            <img src="<?php echo htmlspecialchars($book['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($book['title']); ?>">
                            <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                            <p>by <?php echo htmlspecialchars($book['author']); ?></p>
                            <p class="price">$<?php echo number_format($book['price'], 2); ?></p>
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="no-books">No books available at the moment.</p>';
            }
            $conn->close();
            ?>
        </div>
    </div>
</main>

<?php include('include/footer.php'); ?>
<?php include('include/header_nav.php'); ?>

<main>
    <section class="hero">
        <img src="images/welcome/frontcar.jpg" alt="Bookstore Hero Image">
        <div class="hero-text">
            <h1>Car just for you</h1>
            <p>Get the car you want right now!!</p>
        </div>
    </section>

    <section class="featured-books">
        <h2>New Arrivals</h2>
        <div class="book-grid">
            <?php
            include('connections/conn.php');
            $stmt = $conn->prepare("SELECT * FROM books ORDER BY book_id DESC LIMIT 4");
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
            }
            $conn->close();
            ?>
        </div>
    </section>

    <section class="categories">
        <h2>Browse Categories</h2>
        <div class="category-grid">
            <?php
            include('connections/conn.php');
            $stmt = $conn->prepare("SELECT * FROM categories");
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while($category = $result->fetch_assoc()) {
                    ?>
                    <div class="category-card">
                        <a href="books_category.php?id=<?php echo (int)$category['category_id']; ?>">
                            <img src="images/categories/<?php echo htmlspecialchars(strtolower(str_replace(' ', '', $category['category_name']))); ?>.jpg" 
                                 alt="<?php echo htmlspecialchars($category['category_name']); ?>">
                            <h3><?php echo htmlspecialchars($category['category_name']); ?></h3>
                            <p><?php echo htmlspecialchars($category['description']); ?></p>
                        </a>
                    </div>
                    <?php
                }
            }
            $conn->close();
            ?>
        </div>
    </section>
</main>

<?php include('include/footer.php'); ?>
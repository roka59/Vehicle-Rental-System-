<?php 
include('include/header_nav.php');
include('connections/conn.php');

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$where_clauses = [];
$params = [];
$types = '';

if (!empty($search)) {
    $where_clauses[] = "(title LIKE ? OR author LIKE ? OR description LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'sss';
}

if (!empty($category)) {
    $where_clauses[] = "category_id = ?";
    $params[] = $category;
    $types .= 'i';
}

$where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

$sql = "SELECT b.*, c.category_name 
        FROM books b 
        LEFT JOIN categories c ON b.category_id = c.category_id 
        $where_sql";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<main>
    <div class="search-container">
        <h1>Search Books</h1>
        
        <form method="get" action="books_search.php" class="search-form">
            <div class="form-group">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search by title, author, or description">
                
                <select name="category">
                    <option value="">All Categories</option>
                    <?php
                    $categories = $conn->query("SELECT * FROM categories");
                    while($cat = $categories->fetch_assoc()) {
                        $selected = ($category == $cat['category_id']) ? 'selected' : '';
                        echo "<option value='{$cat['category_id']}' $selected>{$cat['category_name']}</option>";
                    }
                    ?>
                </select>
                
                <button type="submit">Search</button>
            </div>
        </form>
        
        <?php if ($result->num_rows > 0): ?>
            <p class="results-count"><?php echo $result->num_rows; ?> results found</p>
            
            <div class="book-grid">
                <?php while($book = $result->fetch_assoc()): ?>
                    <div class="book-card">
                        <a href="books_detail.php?id=<?php echo $book['book_id']; ?>">
                            <img src="<?php echo $book['image_url']; ?>" alt="<?php echo $book['title']; ?>">
                            <h3><?php echo $book['title']; ?></h3>
                            <p>by <?php echo $book['author']; ?></p>
                            <p class="category"><?php echo $book['category_name']; ?></p>
                            <p class="price">$<?php echo number_format($book['price'], 2); ?></p>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="no-results">No books found matching your search criteria.</p>
        <?php endif; ?>
    </div>
</main>

<?php 
include('include/footer.php');
$conn->close();
?>
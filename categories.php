<?php include('include/header_nav.php'); ?>

<main>
    <h1>Book Categories</h1>
    
    <div class="category-grid">
        <?php
        include('connections/conn.php');
        $sql = "SELECT * FROM categories";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while($category = $result->fetch_assoc()) {
                echo '<div class="category-card">';
                echo '<a href="books_category.php?id='.$category['category_id'].'">';
                echo '<img src="images/categories/'.strtolower(str_replace(' ', '', $category['category_name'])).'.jpg" 
                      alt="'.$category['category_name'].'">';
                echo '<h2>'.$category['category_name'].'</h2>';
                echo '<p>'.$category['description'].'</p>';
                echo '</a>';
                echo '</div>';
            }
        }
        $conn->close();
        ?>
    </div>
</main>

<?php include('include/footer.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hero Car Rental</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <img src="images/logo.png" alt="Logo" class="logo-image"> HeroRental
            </div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="books.php">Cars</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="cart.php">Books</a></li>
                <li><a href="contact_us.php">Contact</a></li>
            </ul>
            <div class="user-status">
                <?php
                if (!isset($_SESSION)) {
                    session_start();
                }
                if (isset($_SESSION['username'])) {
                    echo '<span>Welcome, ' . htmlspecialchars($_SESSION['username']) . '</span> | ';
                    echo '<a href="backend/_logoff.php">Log Out</a>';
                } else {
                    echo '<a href="backend/_user_login.php">Log In</a>';
                }
                ?>
            </div>
        </nav>
    </header>
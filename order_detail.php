<?php
include('include/_authen.php');
include('connections/conn.php');

$order_id = $_GET['id'] ?? 0;

// Get order details
$stmt = $conn->prepare("
    SELECT o.*, u.username 
    FROM orders o 
    JOIN users u ON o.user_id = u.user_id 
    WHERE o.order_id = ? AND o.user_id = ?
");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header("Location: index.php");
    exit();
}

// Get order items
$stmt = $conn->prepare("
    SELECT oi.*, b.title, b.author, b.image_url 
    FROM order_items oi 
    JOIN books b ON oi.book_id = b.book_id 
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details - BookNook</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('include/header_nav.php'); ?>
    
    <main>
        <div class="order-detail-container">
            <h1>Order Details</h1>
            
            <div class="order-info">
                <p><strong>Order ID:</strong> #<?php echo $order['order_id']; ?></p>
                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($order['order_date'])); ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></p>
                <p><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
            </div>
            
            <div class="order-items">
                <h2>Items Ordered</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Author</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['title']; ?>" class="order-thumb">
                                    <?php echo $item['title']; ?>
                                </td>
                                <td><?php echo $item['author']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"><strong>Total:</strong></td>
                            <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="order-actions">
                <a href="books.php" class="continue-shopping">Continue Shopping</a>
            </div>
        </div>
    </main>
    
    <?php include('include/footer.php'); ?>
</body>
</html>

<?php $conn->close(); ?>
<?php
session_start();
require_once "../includes/auth.php";
require_once "../includes/db.php";

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

// Delete review
if (isset($_GET['delete'])) {
  $review_id = (int)$_GET['delete'];
  $conn->query("DELETE FROM reviews WHERE id = $review_id");
  header("Location: manage-reviews.php");
  exit();
}

// Fetch all reviews
$reviews = $conn->query("
  SELECT r.id, r.rating, r.comment, r.created_at,
         u.name AS user_name, v.name AS vehicle_name
  FROM reviews r
  JOIN users u ON r.user_id = u.id
  JOIN vehicles v ON r.vehicle_id = v.id
  ORDER BY r.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Reviews</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include "../includes/header.php"; ?>

<div class="container">
  <h2>Manage Reviews</h2>

  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>User</th>
        <th>Vehicle</th>
        <th>Rating</th>
        <th>Comment</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; while ($row = $reviews->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($row['user_name']) ?></td>
          <td><?= htmlspecialchars($row['vehicle_name']) ?></td>
          <td><?= $row['rating'] ?>/5</td>
          <td><?= htmlspecialchars($row['comment']) ?></td>
          <td><?= $row['created_at'] ?></td>
          <td>
            <a href="?delete=<?= $row['id'] ?>" class="btn-sm danger" onclick="return confirm('Delete this review?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>

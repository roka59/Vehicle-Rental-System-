<?php
session_start();
require_once "../includes/auth.php";
require_once "../includes/db.php";

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

// Handle approval or cancellation
if (isset($_GET['action']) && isset($_GET['id'])) {
  $action = $_GET['action'];
  $booking_id = (int)$_GET['id'];

  if ($action === 'approve') {
    $conn->query("UPDATE bookings SET status = 'approved' WHERE id = $booking_id");
  } elseif ($action === 'cancel') {
    // Set vehicle back to available
    $vid = $conn->query("SELECT vehicle_id FROM bookings WHERE id = $booking_id")->fetch_assoc()['vehicle_id'];
    $conn->query("UPDATE vehicles SET status = 'available' WHERE id = $vid");

    $conn->query("UPDATE bookings SET status = 'cancelled' WHERE id = $booking_id");
  }

  header("Location: manage-bookings.php");
  exit();
}

// Fetch all bookings
$bookings = $conn->query("
  SELECT b.id, b.start_date, b.end_date, b.status, u.name AS user_name, v.name AS vehicle_name
  FROM bookings b
  JOIN users u ON b.user_id = u.id
  JOIN vehicles v ON b.vehicle_id = v.id
  ORDER BY b.start_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Bookings</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include "../includes/header.php"; ?>

<div class="container">
  <h2>Manage Bookings</h2>

  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>User</th>
        <th>Vehicle</th>
        <th>Start</th>
        <th>End</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; while ($b = $bookings->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($b['user_name']) ?></td>
          <td><?= htmlspecialchars($b['vehicle_name']) ?></td>
          <td><?= $b['start_date'] ?></td>
          <td><?= $b['end_date'] ?></td>
          <td><?= ucfirst($b['status']) ?></td>
          <td>
            <?php if ($b['status'] === 'pending'): ?>
              <a href="?action=approve&id=<?= $b['id'] ?>" class="btn-sm success">Approve</a>
              <a href="?action=cancel&id=<?= $b['id'] ?>" class="btn-sm danger" onclick="return confirm('Cancel this booking?')">Cancel</a>
            <?php else: ?>
              <span class="text-muted">No actions</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>

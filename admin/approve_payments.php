<?php
session_start();
require_once "../includes/auth.php";
require_once "../includes/db.php";

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

// Approve payment
if (isset($_GET['approve'])) {
  $payment_id = (int)$_GET['approve'];
  $conn->query("UPDATE payments SET status = 'approved', approved_date = NOW() WHERE id = $payment_id");
  header("Location: approve-payments.php");
  exit();
}

// Fetch pending payment requests
$result = $conn->query("
  SELECT p.id, p.booking_id, p.amount, p.method, p.status, p.request_date,
         u.name AS user_name, v.name AS vehicle_name
  FROM payments p
  JOIN bookings b ON p.booking_id = b.id
  JOIN users u ON b.user_id = u.id
  JOIN vehicles v ON b.vehicle_id = v.id
  WHERE p.status = 'pending'
  ORDER BY p.request_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Approve Payments</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include "../includes/header.php"; ?>

<div class="container">
  <h2>Approve Payment Requests</h2>

  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>User</th>
        <th>Vehicle</th>
        <th>Amount</th>
        <th>Method</th>
        <th>Requested</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($row['user_name']) ?></td>
          <td><?= htmlspecialchars($row['vehicle_name']) ?></td>
          <td>$<?= number_format($row['amount'], 2) ?></td>
          <td><?= ucfirst($row['method']) ?></td>
          <td><?= $row['request_date'] ?></td>
          <td>
            <a href="?approve=<?= $row['id'] ?>" class="btn-sm success">Approve</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>

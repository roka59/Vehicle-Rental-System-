<?php
session_start();
require_once "../includes/auth.php";
require_once "../includes/db.php";

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

// Revenue & Booking reports
$total_revenue = $conn->query("SELECT SUM(amount) as total FROM payments WHERE status = 'approved'")
                      ->fetch_assoc()['total'] ?? 0;

$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'] ?? 0;

$monthly_data = $conn->query("
  SELECT MONTHNAME(approved_date) AS month, SUM(amount) AS revenue
  FROM payments
  WHERE status = 'approved'
  GROUP BY MONTH(approved_date)
  ORDER BY MONTH(approved_date)
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reports & Analytics</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include "../includes/header.php"; ?>

<div class="container">
  <h2>Reports & Analytics</h2>

  <div class="report-boxes">
    <div class="box">
      <h3>Total Revenue</h3>
      <p>$<?= number_format($total_revenue, 2) ?></p>
    </div>
    <div class="box">
      <h3>Total Bookings</h3>
      <p><?= $total_bookings ?></p>
    </div>
  </div>

  <h3>Monthly Revenue</h3>
  <table class="table">
    <thead>
      <tr>
        <th>Month</th>
        <th>Revenue</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $monthly_data->fetch_assoc()): ?>
        <tr>
          <td><?= $row['month'] ?></td>
          <td>$<?= number_format($row['revenue'], 2) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>

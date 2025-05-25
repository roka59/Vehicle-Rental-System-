<?php
session_start();
require_once '../config/db.php';
require_once "../config/constants.php";
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location:". BASE_URL . "/login.php");
  exit();
}

// Fetch stats
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalVehicles = $pdo->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
$totalBookings = $pdo->query("SELECT COUNT(*) FROM rentals")->fetchColumn();
$approvedBookings = $pdo->query("SELECT COUNT(*) FROM rentals WHERE status = 'Approved'")->fetchColumn();
$cancelledBookings = $pdo->query("SELECT COUNT(*) FROM rentals WHERE status = 'Cancelled'")->fetchColumn();
$totalRevenue = $pdo->query("SELECT SUM(amount) FROM payments WHERE status = 'Approved'")->fetchColumn();
$monthlyRevenue = $pdo->query("
  SELECT SUM(amount) FROM payments 
  WHERE status = 'Approved' 
    AND MONTH(created_at) = MONTH(CURRENT_DATE()) 
    AND YEAR(created_at) = YEAR(CURRENT_DATE())
")->fetchColumn();

$popularVehicles = $pdo->query("
  SELECT v.model, COUNT(*) as total
  FROM rentals r
  JOIN vehicles v ON r.vehicle_id = v.vehicle_id
  GROUP BY r.vehicle_id
  ORDER BY total DESC
  LIMIT 5
")->fetchAll();
?>

<main class="container admin-reports">
  <h2 class="section-title">Reports & Analytics</h2>

  <div class="dashboard-cards">
    <div class="card stat">
      <h4>Total Users</h4>
      <p><?= $totalUsers ?></p>
    </div>
    <div class="card stat">
      <h4>Total Vehicles</h4>
      <p><?= $totalVehicles ?></p>
    </div>
    <div class="card stat">
      <h4>Total Bookings</h4>
      <p><?= $totalBookings ?></p>
    </div>
    <div class="card stat">
      <h4>Approved Bookings</h4>
      <p><?= $approvedBookings ?></p>
    </div>
    <div class="card stat">
      <h4>Cancelled Bookings</h4>
      <p><?= $cancelledBookings ?></p>
    </div>
    <div class="card stat highlight">
      <h4>Total Revenue</h4>
      <p>$<?= number_format($totalRevenue, 2) ?></p>
    </div>
    <div class="card stat highlight">
      <h4>Revenue This Month</h4>
      <p>$<?= number_format($monthlyRevenue, 2) ?></p>
    </div>
  </div>

  <h3 class="section-subtitle">Top 5 Most Booked Vehicles</h3>
  <div class="table-wrapper">
    <table class="table">
      <thead>
        <tr>
          <th>Model</th>
          <th>Bookings</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($popularVehicles): ?>
          <?php foreach ($popularVehicles as $vehicle): ?>
            <tr>
              <td><?= htmlspecialchars($vehicle['model']) ?></td>
              <td><?= $vehicle['total'] ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="2">No booking data found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

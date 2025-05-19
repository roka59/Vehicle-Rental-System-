<?php
session_start();
require_once '../config/db.php';
require_once "../config/constants.php";
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location:". BASE_URL . "/login.php");
  exit();
}

$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalVehicles = $pdo->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
$totalBookings = $pdo->query("SELECT COUNT(*) FROM rentals")->fetchColumn();
$pendingBookings = $pdo->query("SELECT COUNT(*) FROM rentals WHERE status = 'Pending'")->fetchColumn();
$approvedBookings = $pdo->query("SELECT COUNT(*) FROM rentals WHERE status = 'Approved'")->fetchColumn();
$cancelledBookings = $pdo->query("SELECT COUNT(*) FROM rentals WHERE status = 'Cancelled'")->fetchColumn();
$pendingPayments = $pdo->query("SELECT COUNT(*) FROM payments WHERE status = 'Pending'")->fetchColumn();
?>

<main class="admin-dashboard container">
  <h2 class="section-title">Admin Dashboard</h2>

  <div class="dashboard-shortcuts">
    <a href="manage_users.php" class="shortcut-btn">👤 Manage Users</a>
    <a href="manage_vehicles.php" class="shortcut-btn">🚗 Manage Vehicles</a>
    <a href="manage_bookings.php" class="shortcut-btn">📋 Manage Bookings</a>
    <a href="approve_payments.php" class="shortcut-btn">💳 Approve Payments</a>
    <a href="reports.php" class="shortcut-btn">📊 Reports</a>
  </div>

  <div class="dashboard-cards">
    <div class="card-box blue">
      <div>
        <h3>Total Users</h3>
        <p><?= $totalUsers ?></p>
      </div>
      <span class="icon">👤</span>
    </div>
    <div class="card-box green">
      <div>
        <h3>Total Vehicles</h3>
        <p><?= $totalVehicles ?></p>
      </div>
      <span class="icon">🚗</span>
    </div>
    <div class="card-box orange">
      <div>
        <h3>Total Bookings</h3>
        <p><?= $totalBookings ?></p>
      </div>
      <span class="icon">📋</span>
    </div>
    <div class="card-box yellow">
      <div>
        <h3>Pending Bookings</h3>
        <p><?= $pendingBookings ?></p>
      </div>
      <span class="icon">⏳</span>
    </div>
    <div class="card-box teal">
      <div>
        <h3>Approved Bookings</h3>
        <p><?= $approvedBookings ?></p>
      </div>
      <span class="icon">✅</span>
    </div>
    <div class="card-box red">
      <div>
        <h3>Cancelled Bookings</h3>
        <p><?= $cancelledBookings ?></p>
      </div>
      <span class="icon">❌</span>
    </div>
    <div class="card-box purple">
      <div>
        <h3>Pending Payments</h3>
        <p><?= $pendingPayments ?></p>
      </div>
      <span class="icon">💰</span>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

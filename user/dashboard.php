<?php
session_start();
require_once '../config/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user name
$stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Stats
$totalBookings = $pdo->query("SELECT COUNT(*) FROM rentals WHERE user_id = $user_id")->fetchColumn();
$totalPayments = $pdo->query("SELECT COUNT(*) FROM payments JOIN rentals ON payments.rental_id = rentals.rental_id WHERE rentals.user_id = $user_id")->fetchColumn();
$totalReviews = $pdo->query("SELECT COUNT(*) FROM reviews WHERE user_id = $user_id")->fetchColumn();

// Recent bookings
$stmt = $pdo->prepare("
  SELECT r.*, v.model FROM rentals r
  JOIN vehicles v ON r.vehicle_id = v.vehicle_id
  WHERE r.user_id = ?
  ORDER BY r.start_date DESC
  LIMIT 3
");
$stmt->execute([$user_id]);
$recent = $stmt->fetchAll();
?>

<main class="container dashboard-page">
  <h2 class="section-title">Welcome back, <?= htmlspecialchars($user['name']) ?> 👋</h2>

  <div class="dashboard-stats">
    <div class="stat-card">📅 <strong><?= $totalBookings ?></strong><span>Total Bookings</span></div>
    <div class="stat-card">💵 <strong><?= $totalPayments ?></strong><span>Payments Made</span></div>
    <div class="stat-card">⭐ <strong><?= $totalReviews ?></strong><span>Reviews Given</span></div>
  </div>

  <h3 class="section-subtitle">Recent Bookings</h3>
  <?php if ($recent): ?>
    <div class="recent-bookings">
      <?php foreach ($recent as $r): ?>
        <div class="booking-item">
          <p><strong><?= $r['model'] ?></strong></p>
          <p><?= $r['start_date'] ?> → <?= $r['end_date'] ?></p>
          <p>Status: <span class="status <?= strtolower($r['status']) ?>"><?= $r['status'] ?></span></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="no-results">You have no bookings yet.</p>
  <?php endif; ?>

  <div class="action-buttons">
    <a href="../vehicles/list.php" class="btn-primary">Browse Vehicles</a>
    <a href="my_booking.php" class="btn-secondary">View All Bookings</a>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

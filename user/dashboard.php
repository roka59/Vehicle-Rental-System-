<?php
session_start();
require_once '../config/db.php';
require_once '../config/constants.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's name
$stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Dashboard Stats
$stmt1 = $pdo->prepare("SELECT COUNT(*) FROM rentals WHERE user_id = ?");
$stmt1->execute([$user_id]);
$totalBookings = $stmt1->fetchColumn();

$stmt2 = $pdo->prepare("
  SELECT COUNT(*) FROM payments 
  JOIN rentals ON payments.rental_id = rentals.rental_id 
  WHERE rentals.user_id = ?
");
$stmt2->execute([$user_id]);
$totalPayments = $stmt2->fetchColumn();

$stmt3 = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE user_id = ?");
$stmt3->execute([$user_id]);
$totalReviews = $stmt3->fetchColumn();

// Recent Bookings
$stmt = $pdo->prepare("
  SELECT r.*, v.model 
  FROM rentals r
  JOIN vehicles v ON r.vehicle_id = v.vehicle_id
  WHERE r.user_id = ?
  ORDER BY r.start_date DESC
  LIMIT 3
");
$stmt->execute([$user_id]);
$recentBookings = $stmt->fetchAll();
?>

<main class="container dashboard-page">
  <h2 class="section-title">Welcome back, <?= htmlspecialchars($user['name']) ?> 👋</h2>

  <div class="dashboard-stats">
    <div class="stat-card">
      📅 <strong><?= $totalBookings ?></strong>
      <span>Total Bookings</span>
    </div>
    <div class="stat-card">
      💵 <strong><?= $totalPayments ?></strong>
      <span>Payments Made</span>
    </div>
    <div class="stat-card">
      ⭐ <strong><?= $totalReviews ?></strong>
      <span>Reviews Given</span>
    </div>
  </div>

  <h3 class="section-subtitle">Recent Bookings</h3>

  <?php if ($recentBookings): ?>
    <div class="recent-bookings">
      <?php foreach ($recentBookings as $booking): ?>
        <div class="booking-item">
          <p><strong><?= htmlspecialchars($booking['model']) ?></strong></p>
          <p><?= htmlspecialchars($booking['start_date']) ?> → <?= htmlspecialchars($booking['end_date']) ?></p>
          <p>Status: 
            <span class="status <?= strtolower(htmlspecialchars($booking['status'])) ?>">
              <?= htmlspecialchars($booking['status']) ?>
            </span>
          </p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="no-results">You have no bookings yet.</p>
  <?php endif; ?>

  <div class="action-buttons">
    <a href="<?= BASE_URL ?>/vehicles/list.php" class="btn-primary">Browse Vehicles</a>
    <a href="<?= BASE_URL ?>/user/my_booking.php" class="btn-secondary">View All Bookings</a>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

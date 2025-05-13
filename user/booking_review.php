<?php
session_start();
require_once '../config/db.php';
require_once '../config/constants.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['rental_id'])) {
  header("Location: dashboard.php");
  exit();
}

$rental_id = (int)$_GET['rental_id'];

// Fetch rental and vehicle details
$stmt = $pdo->prepare("
  SELECT r.*, v.model, v.image, v.rental_price
  FROM rentals r
  JOIN vehicles v ON r.vehicle_id = v.vehicle_id
  WHERE r.rental_id = ? AND r.user_id = ?
");
$stmt->execute([$rental_id, $_SESSION['user_id']]);
$data = $stmt->fetch();

if (!$data) {
  echo "<p class='no-results'>Booking not found.</p>";
  include 'includes/footer.php';
  exit();
}

$start = strtotime($data['start_date']);
$end = strtotime($data['end_date']);
$days = max(1, ceil(($end - $start) / 86400));
$total = $data['rental_price'] * $days;
?>

<main class="container booking-review-page">
  <h2 class="section-title">Review Your Booking</h2>

  <div class="booking-review-card">
    <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($data['image']) ?>" alt="<?= htmlspecialchars($data['model']) ?>" />

    <div class="booking-details">
      <h3><?= htmlspecialchars($data['model']) ?></h3>
      <p><strong>From:</strong> <?= htmlspecialchars($data['start_date']) ?></p>
      <p><strong>To:</strong> <?= htmlspecialchars($data['end_date']) ?></p>
      <p><strong>Days:</strong> <?= $days ?></p>
      <p><strong>Total Price:</strong> $<?= number_format($total, 2) ?></p>

      <a href="<?= BASE_URL ?>/user/payment_request.php?rental_id=<?= $rental_id ?>" class="btn-primary">Pay Now</a>

      <div class="back-link">
        <button onclick="window.history.back();" class="btn-secondary">← Go Back</button>
      </div>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

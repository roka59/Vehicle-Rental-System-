<?php
session_start();
require_once '../config/db.php';
include 'includes/header.php';

// User must be logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

// Vehicle ID is required
if (!isset($_GET['id'])) {
  echo "<p class='no-results'>Vehicle not found.</p>";
  include 'includes/footer.php';
  exit();
}

$vehicle_id = (int)$_GET['id'];

// Get vehicle details
$stmt = $pdo->prepare("SELECT * FROM vehicles WHERE vehicle_id = ?");
$stmt->execute([$vehicle_id]);
$vehicle = $stmt->fetch();

if (!$vehicle) {
  echo "<p class='no-results'>Vehicle not found.</p>";
  include 'includes/footer.php';
  exit();
}
?>
<?php include '../includes/flash.php'; ?>

<main class="container booking-page">
  <h2 class="section-title">Book <?= htmlspecialchars($vehicle['model']) ?></h2>

  <form method="POST" action="handle_booking.php" class="booking-form">
    <input type="hidden" name="vehicle_id" value="<?= $vehicle['vehicle_id'] ?>" />

    <div class="form-group">
      <label for="start_date">Start Date</label>
      <input type="date" name="start_date" id="start_date" required />
    </div>

    <div class="form-group">
      <label for="end_date">End Date</label>
      <input type="date" name="end_date" id="end_date" required />
    </div>

    <button type="submit" class="btn-primary full-width">Confirm Booking</button>
  </form>
  <div class="back-link">
<button onclick="window.history.back();" class="btn-secondary">← Go Back</button>
</div>
</main>

<?php include 'includes/footer.php'; ?>

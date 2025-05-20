<?php
session_start();
require_once '../config/db.php';
require_once '../config/constants.php';
include BASE_PATH . '/user/includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
  header("Location:". BASE_URL . "/login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch unpaid bookings
$stmt = $pdo->prepare("
  SELECT r.rental_id, v.model, r.start_date, r.end_date
  FROM rentals r
  JOIN vehicles v ON r.vehicle_id = v.vehicle_id
  WHERE r.user_id = ? AND r.status = 'Pending'
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>

<main class="container payment-request-page">
  <h2 class="section-title">Submit Payment Request</h2>

  <?php if ($bookings): ?>
    <form action="process_payment_request.php" method="POST" class="payment-form">
      <?php
      $selectedRental = isset($_GET['rental_id']) ? (int)$_GET['rental_id'] : null;
      ?>
      <div class="form-group">
        <label for="rental_id">Select Booking:</label>
        <select name="rental_id" required>
          <option value="">-- Select a booking --</option>
          <?php foreach ($bookings as $b): ?>
            <option value="<?= $b['rental_id'] ?>" <?= ($selectedRental === $b['rental_id']) ? 'selected' : '' ?>>
              <?= $b['model'] ?> (<?= $b['start_date'] ?> to <?= $b['end_date'] ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="method">Payment Method:</label>
        <select name="method" required>
          <option value="Cash">Cash</option>
          <option value="Bank Transfer">Bank Transfer</option>
        </select>
      </div>

      <div class="form-group">
        <label for="reference">Reference / Note (optional):</label>
        <textarea name="reference" placeholder="Transaction ID, or notes..."></textarea>
      </div>

      <button type="submit" class="btn-primary">Submit Request</button>
      <div class="back-link">
        <button onclick="window.history.back();" class="btn-secondary">← Go Back</button>
      </div>
    </form>
  <?php else: ?>
    <p class="no-results">You have no pending bookings for payment.</p>
  <?php endif; ?>
</main>

<?php include BASE_PATH . '/user/includes/footer.php'; ?>

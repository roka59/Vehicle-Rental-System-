<?php
session_start();
require_once '../config/db.php';

// Use user header if logged in, otherwise public header
if (isset($_SESSION['user_id'])) {
  include '../user/includes/header.php';
} else {
  include '../includes/header.php';
}

// Vehicle must be provided
if (!isset($_GET['id'])) {
  echo "<p class='no-results'>Vehicle not found.</p>";
  include '../includes/footer.php';
  exit();
}

$vehicle_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM vehicles WHERE vehicle_id = ?");
$stmt->execute([$vehicle_id]);
$vehicle = $stmt->fetch();

if (!$vehicle) {
  echo "<p class='no-results'>Vehicle not found.</p>";
  include '../includes/footer.php';
  exit();
}
?>

<main class="container vehicle-details-page">
  <div class="vehicle-details-card">
    <div class="vehicle-image">
      <img src="../assets/images/<?= htmlspecialchars($vehicle['image']) ?>" alt="<?= htmlspecialchars($vehicle['model']) ?>">
    </div>

    <div class="vehicle-content">
      <h2><?= htmlspecialchars($vehicle['model']) ?></h2>
      <p><strong>Type:</strong> <?= $vehicle['type'] ?></p>
      <p><strong>Price/Day:</strong> $<?= $vehicle['rental_price'] ?></p>
      <p><strong>Status:</strong> <?= $vehicle['availability'] ?></p>
      <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($vehicle['description'] ?? 'No description available.')) ?></p>

      <?php if ($vehicle['availability'] === 'Available'): ?>
        <a href="../user/booking.php?id=<?= $vehicle['vehicle_id'] ?>" class="btn-primary">Book Now</a>
      <?php else: ?>
        <button class="btn-primary" disabled>Not Available</button>
      <?php endif; ?>
    
      <div class="back-link">
  <button onclick="window.history.back();" class="btn-secondary">← Go Back</button>
</div>

      <div class="review-link">
        <a href="reviews.php?vehicle_id=<?= $vehicle['vehicle_id'] ?>">See Reviews</a>
      </div>
    </div>
  </div>
</main>

<?php include '../includes/footer.php'; ?>

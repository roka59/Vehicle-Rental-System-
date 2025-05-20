<?php
session_start();
require_once '../config/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's payment history
$stmt = $pdo->prepare("
  SELECT p.*, r.start_date, r.end_date, v.model
  FROM payments p
  JOIN rentals r ON p.rental_id = r.rental_id
  JOIN vehicles v ON r.vehicle_id = v.vehicle_id
  WHERE r.user_id = ?
  ORDER BY p.created_at DESC
");
$stmt->execute([$user_id]);
$payments = $stmt->fetchAll();
?>

<main class="container payment-history-page">
  <h2 class="section-title">My Payment History</h2>

  <?php if ($payments): ?>
    <div class="payment-history">
      <?php foreach ($payments as $p): ?>
        <div class="payment-record">
          <p><strong>Vehicle:</strong> <?= htmlspecialchars($p['model']) ?></p>
          <p><strong>Period:</strong> <?= $p['start_date'] ?> to <?= $p['end_date'] ?></p>
          <p><strong>Method:</strong> <?= $p['method'] ?></p>
          <p><strong>Reference:</strong> <?= $p['reference_note'] ?: 'N/A' ?></p>
          <p class="payment-status <?= strtolower($p['status']) ?>">Payment Status: <?= $p['status'] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="no-results">No payments submitted yet.</p>
  <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>

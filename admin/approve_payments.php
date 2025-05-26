<?php
session_start();
require_once '../config/db.php';
require_once "../config/constants.php";
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location:". BASE_URL . "/login.php");
  exit();
}

// Handle approval
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['payment_id'])) {
  $payment_id = (int)$_POST['payment_id'];

  // Approve the payment
  $pdo->prepare("UPDATE payments SET status = 'Approved' WHERE payment_id = ?")->execute([$payment_id]);

  // Also mark the rental as Approved
  $pdo->prepare("UPDATE rentals SET status = 'Approved' WHERE rental_id = (
    SELECT rental_id FROM payments WHERE payment_id = ?
  )")->execute([$payment_id]);

  $_SESSION['flash_success'] = "Payment approved successfully.";
  header("Location: approve_payments.php");
  exit();
}

// Status filter
$statusFilter = $_GET['status'] ?? 'Pending';
$allowed = ['Pending', 'Approved'];
$statusFilter = in_array($statusFilter, $allowed) ? $statusFilter : 'Pending';

$stmt = $pdo->prepare("
  SELECT p.*, u.name AS user_name, v.model AS vehicle_model
  FROM payments p
  JOIN rentals r ON p.rental_id = r.rental_id
  JOIN users u ON r.user_id = u.id
  JOIN vehicles v ON r.vehicle_id = v.vehicle_id
  WHERE p.status = ?
  ORDER BY p.created_at DESC
");
$stmt->execute([$statusFilter]);
$payments = $stmt->fetchAll();
?>

<main class="container admin-payments-page">
  <h2 class="section-title">Approve Payments</h2>

  <?php include '../includes/flash.php'; ?>

  <div class="filter-tabs">
    <a href="?status=Pending" class="filter-btn <?= $statusFilter === 'Pending' ? 'active' : '' ?>">Pending</a>
    <a href="?status=Approved" class="filter-btn <?= $statusFilter === 'Approved' ? 'active' : '' ?>">Approved</a>
  </div>

  <div class="table-wrapper">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Vehicle</th>
          <th>Amount</th>
          <th>Method</th>
          <th>Reference</th>
          <th>Status</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($payments): ?>
          <?php foreach ($payments as $index => $payment): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td><?= htmlspecialchars($payment['user_name']) ?></td>
              <td><?= htmlspecialchars($payment['vehicle_model']) ?></td>
              <td>$<?= number_format($payment['amount'], 2) ?></td>
              <td><?= htmlspecialchars($payment['method']) ?></td>
              <td><?= htmlspecialchars($payment['reference_note'] ?? '-') ?></td>
              <td><span class="badge <?= strtolower($payment['status']) ?>"><?= $payment['status'] ?></span></td>
              <td><?= date("M d, Y", strtotime($payment['created_at'])) ?></td>
              <td>
                <?php if ($payment['status'] === 'Pending'): ?>
                  <form method="POST">
                    <input type="hidden" name="payment_id" value="<?= $payment['payment_id'] ?>">
                    <button type="submit" class="btn-success small-btn">Approve</button>
                  </form>
                <?php else: ?>
                  <span class="text-muted">Approved</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="9">No records found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

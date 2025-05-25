<?php
session_start();
require_once '../config/db.php';
require_once "../config/constants.php";
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location:". BASE_URL . "/login.php");
  exit();
}

// Handle approve or cancel actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rental_id'], $_POST['action'])) {
  $action = $_POST['action'];
  $rentalId = (int)$_POST['rental_id'];

  if ($action === 'approve') {
    $pdo->prepare("UPDATE rentals SET status = 'Approved' WHERE rental_id = ?")->execute([$rentalId]);
    $_SESSION['flash_success'] = "Booking approved.";
  } elseif ($action === 'cancel') {
    $pdo->prepare("UPDATE rentals SET status = 'Cancelled' WHERE rental_id = ?")->execute([$rentalId]);
    $_SESSION['flash_success'] = "Booking cancelled.";
  }

  header("Location: manage_bookings.php");
  exit();
}

// Status filter
$allowedStatuses = ['All', 'Pending', 'Approved', 'Cancelled'];
$statusFilter = isset($_GET['status']) && in_array($_GET['status'], $allowedStatuses) ? $_GET['status'] : 'All';

$query = "SELECT r.*, u.name AS user_name, v.model AS vehicle_model FROM rentals r
          JOIN users u ON r.user_id = u.id
          JOIN vehicles v ON r.vehicle_id = v.vehicle_id";

if ($statusFilter !== 'All') {
  $query .= " WHERE r.status = :status";
}

$query .= " ORDER BY r.start_date DESC";

$stmt = $pdo->prepare($query);
if ($statusFilter !== 'All') {
  $stmt->execute(['status' => $statusFilter]);
} else {
  $stmt->execute();
}

$bookings = $stmt->fetchAll();
?>

<main class="container admin-bookings-page">
  <h2 class="section-title">Manage Bookings</h2>

  <?php include '../includes/flash.php'; ?>

  <div class="filter-tabs">
    <?php foreach ($allowedStatuses as $status): ?>
      <a href="?status=<?= $status ?>" class="filter-btn <?= $statusFilter === $status ? 'active' : '' ?>">
        <?= $status ?>
      </a>
    <?php endforeach; ?>
  </div>

  <div class="table-wrapper">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Vehicle</th>
          <th>From</th>
          <th>To</th>
          <th>Status</th>
          <th>Requested</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($bookings): ?>
          <?php foreach ($bookings as $i => $booking): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($booking['user_name']) ?></td>
              <td><?= htmlspecialchars($booking['vehicle_model']) ?></td>
              <td><?= $booking['start_date'] ?></td>
              <td><?= $booking['end_date'] ?></td>
              <td><span class="badge <?= strtolower($booking['status']) ?>"><?= $booking['status'] ?></span></td>
              <td><?= date("M d, Y", strtotime($booking['created_at'])) ?></td>
              <td>
                <?php if ($booking['status'] === 'Pending'): ?>
                  <form method="POST" style="display:inline;">
                    <input type="hidden" name="rental_id" value="<?= $booking['rental_id'] ?>">
                    <button name="action" value="approve" class="btn-success small-btn">Approve</button>
                  </form>
                  <form method="POST" style="display:inline;">
                    <input type="hidden" name="rental_id" value="<?= $booking['rental_id'] ?>">
                    <button name="action" value="cancel" class="btn-danger small-btn">Cancel</button>
                  </form>
                <?php else: ?>
                  <span class="text-muted">No Actions</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="8">No bookings found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

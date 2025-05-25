<?php
session_start();
require_once '../config/db.php';
require_once "../config/constants.php";
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location:". BASE_URL . "/login.php");
  exit();
}

// Handle Delete Request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
  $deleteId = $_GET['delete'];
  $stmt = $pdo->prepare("DELETE FROM vehicles WHERE vehicle_id = ?");
  $stmt->execute([$deleteId]);

  $_SESSION['flash_success'] = "Vehicle deleted successfully.";
  header("Location: manage_vehicles.php");
  exit();
}

// Fetch Vehicles
$vehicles = $pdo->query("SELECT * FROM vehicles ORDER BY created_at DESC")->fetchAll();
?>

<main class="container admin-vehicles-page">
  <h2 class="section-title">Manage Vehicles</h2>

  <?php include '../includes/flash.php'; ?>

  <div class="top-actions">
    <a href="add_vehicle.php" class="btn-primary">+ Add New Vehicle</a>
  </div>

  <div class="table-wrapper">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Image</th>
          <th>Model</th>
          <th>Type</th>
          <th>Price/Day</th>
          <th>Status</th>
          <th>Added</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($vehicles): ?>
          <?php foreach ($vehicles as $index => $vehicle): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td><img src="../assets/images/<?= htmlspecialchars($vehicle['image']) ?>" width="60" height="40" style="object-fit:cover;"></td>
              <td><?= htmlspecialchars($vehicle['model']) ?></td>
              <td><?= $vehicle['type'] ?></td>
              <td>$<?= $vehicle['rental_price'] ?></td>
              <td><span class="badge <?= strtolower($vehicle['availability']) ?>"><?= $vehicle['availability'] ?></span></td>
              <td><?= date("M d, Y", strtotime($vehicle['created_at'])) ?></td>
              <td>
                <a href="edit_vehicle.php?id=<?= $vehicle['vehicle_id'] ?>" class="btn-secondary small-btn">Edit</a>
                <a href="?delete=<?= $vehicle['vehicle_id'] ?>" class="btn-danger small-btn" onclick="return confirm('Are you sure to delete this vehicle?');">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="8">No vehicles found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

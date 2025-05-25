<?php
session_start();
require_once '../config/db.php';
require_once '../config/constants.php';
include 'includes/header.php';

// Access control
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location:" . BASE_URL . "/login.php");
  exit();
}

// Handle status toggle
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'], $_POST['action'])) {
  $userId = (int)$_POST['user_id'];
  $action = $_POST['action'];

  $newStatus = $action === 'inactive' ? 'inactive' : 'active';

  $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
  $stmt->execute([$newStatus, $userId]);

  $_SESSION['flash_success'] = "User status updated successfully.";
  header("Location: manage_users.php");
  exit();
}

// Fetch all users
$users = $pdo->query("SELECT id, name, email, contact, license_number, status FROM users ORDER BY created_at DESC")->fetchAll();
?>

<main class="container admin-users-page">
  <h2 class="section-title">Manage Users</h2>

  <?php include '../includes/flash.php'; ?>

  <div class="table-wrapper">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Contact</th>
          <th>License</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($users): ?>
          <?php foreach ($users as $index => $user): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td><?= htmlspecialchars($user['name']) ?></td>
              <td><?= htmlspecialchars($user['email']) ?></td>
              <td><?= htmlspecialchars($user['contact']) ?></td>
              <td><?= htmlspecialchars($user['license_number']) ?></td>
              <td><span class="badge <?= strtolower($user['status']) ?>"><?= ucfirst($user['status']) ?></span></td>
              <td>
                <form method="POST" style="display:inline;">
                  <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                  <?php if ($user['status'] === 'active'): ?>
                    <button name="action" value="inactive" class="btn-danger small-btn">Deactivate</button>
                  <?php else: ?>
                    <button name="action" value="active" class="btn-primary small-btn">Activate</button>
                  <?php endif; ?>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7">No users found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

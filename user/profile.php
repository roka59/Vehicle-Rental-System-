<?php
session_start();
require_once '../config/constants.php';
require_once BASE_PATH . 'config/db.php';
include BASE_PATH . 'user/includes/header.php';
include BASE_PATH . 'includes/flash.php'; 

if (!isset($_SESSION['user_id'])) {
  header("Location: " . BASE_URL . "login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user details
$stmt = $pdo->prepare("SELECT name, contact, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<main class="container profile-page">
  <h2 class="section-title">My Profile</h2>

  <?php displayFlash(); ?>

  <form action="<?= BASE_URL ?>user/update_profile.php" method="POST" class="profile-form">
    <div class="form-group">
      <label for="name">Full Name:</label>
      <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required />
    </div>

    <div class="form-group">
      <label for="contact">Contact Number:</label>
      <input type="text" name="contact" id="contact" value="<?= htmlspecialchars($user['contact']) ?>" required />
    </div>

    <div class="form-group">
      <label>Email Address:</label>
      <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled />
    </div>

    <div class="form-group">
      <label for="password">New Password:</label>
      <input type="password" name="password" id="password" placeholder="Leave blank to keep current" autocomplete="off" />
    </div>

    <div class="form-group">
      <label for="confirm_password">Confirm New Password:</label>
      <input type="password" name="confirm_password" id="confirm_password" placeholder="Leave blank to keep current" autocomplete="off" />
    </div>

    <button type="submit" class="btn-primary">Update Profile</button>
  </form>
</main>

<?php include BASE_PATH . 'user/includes/footer.php'; ?>

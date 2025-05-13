<?php
session_start();
require_once '../config/db.php';
include 'includes/header.php';
include '../includes/flash.php'; 

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
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

  <form action="update_profile.php" method="POST" class="profile-form">
    <div class="form-group">
      <label for="name">Full Name:</label>
      <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required />
    </div>

    <div class="form-group">
      <label for="contact">Contact Number:</label>
      <input type="text" name="contact" id="contact" value="<?= htmlspecialchars($user['contact']) ?>" required />
    </div>

    <div class="form-group">
      <label for="password">New Password:</label>
      <input type="password" name="password" id="password" placeholder="Leave blank to keep current" />
    </div>

    <div class="form-group">
      <label for="confirm_password">Confirm New Password:</label>
      <input type="password" name="confirm_password" id="confirm_password" placeholder="Leave blank to keep current" />
    </div>

    <button type="submit" class="btn-primary">Update Profile</button>
  </form>
</main>

<?php include 'includes/footer.php'; ?>

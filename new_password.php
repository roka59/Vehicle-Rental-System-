<?php
session_start();
require_once "./config/constants.php";

// Check if reset_user_id is set in session
if (!isset($_SESSION['reset_user_id'])) {
  $_SESSION["flash_error"] = "Unauthorized access to password reset.";
  header("Location: " . BASE_URL . "/reset_password_request.php");
  exit();
}

$user_id = $_SESSION['reset_user_id'];
?>

<?php include './includes/header.php'; ?>
<?php include 'includes/flash.php'; ?>

<main class="container auth-page">
  <section class="auth-form-box">
    <h2 class="form-title">Set New Password</h2>

    <form method="POST" action="<?= BASE_URL ?>/auth/process_reset.php" class="form">
      <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>" />

      <div class="form-group">
        <label for="new_password">New Password</label>
        <input type="password" name="new_password" id="new_password" class="form-input" required />
      </div>

      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-input" required />
      </div>

      <button type="submit" class="btn-primary full-width">Reset Password</button>
    </form>
  </section>
</main>

<?php include './includes/footer.php'; ?>

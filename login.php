<?php
session_start();
if (isset($_SESSION['user_id'])) {
  header("Location: user/dashboard.php");
  exit();
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/flash.php'; ?> <!-- ✅ Global flash system -->

<main>
  <div class="login-wrapper">
    <div class="login-card">
      <h2 class="login-title">Login to Your Account</h2>

      <form method="POST" action="auth/handle_login.php" class="login-form">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" class="form-input" required placeholder="you@example.com">
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="password-input-group">
            <input type="password" name="password" id="password" class="form-input" required placeholder="Enter your password">
            <span class="eye-icon" onclick="togglePasswordVisibility('password')">
              <span id="visibility-icon-password" class="material-symbols-outlined">visibility_off</span>
            </span>
          </div>
        </div>

        <button type="submit" class="btn-primary full-width">Login</button>

        <div class="forgot-password">
          <a href="reset_password_request.php">Forgot Password?</a>
        </div>

        <div class="switch-link">
          Don't have an account? <a href="register.php">Register here</a>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

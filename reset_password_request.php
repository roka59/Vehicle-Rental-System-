<?php
session_start();
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/flash.php'; ?> <!-- ✅ Flash messages added -->

<main class="container auth-page">
  <section class="auth-form-box">
    <h2 class="form-title">Reset Password</h2>

    <form method="POST" action="auth/handle_reset_password.php" class="form">
      <div class="form-group">
        <label for="email">Registered Email</label>
        <input type="email" name="email" id="email" class="form-input" required placeholder="you@example.com">
      </div>

      <div class="form-group">
        <label for="license_number">License Number</label>
        <input type="text" name="license_number" id="license_number" class="form-input" required placeholder="Your License Number">
      </div>

      <button type="submit" class="btn-primary full-width">Next</button>

      <div class="switch-link">
        Return to login? <a href="login.php">Login</a>
      </div>
    </form>
  </section>
</main>

<?php include 'includes/footer.php'; ?>

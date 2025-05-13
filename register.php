<?php
session_start();
if (isset($_SESSION['user_id'])) {
  header("Location: user/dashboard.php");
  exit();
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/flash.php'; ?> <!-- ✅ Flash Message Include -->

<main class="container auth-page">
  <section class="auth-form-box">
    <h2 class="form-title">Create Your Account</h2>

    <!-- ✅ Flash messages are already included above -->

    <form method="POST" action="auth/handle_register.php" class="form">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" required placeholder="John Doe" class="form-input" />
      </div>

      <div class="form-group">
        <label for="contact">Contact Number</label>
        <input type="tel" name="contact" id="contact" required placeholder="123-456-7890" class="form-input" />
      </div>

      <div class="form-group">
        <label for="license">License Number</label>
        <input type="text" name="license_number" id="license" required placeholder="D12345678" class="form-input" />
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" required placeholder="you@example.com" class="form-input" />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required placeholder="Create a password" class="form-input" />
      </div>

      <div class="form-group">
        <label for="confirm">Confirm Password</label>
        <input type="password" name="confirm" id="confirm" required placeholder="Repeat your password" class="form-input" />
      </div>

      <button type="submit" class="btn-primary full-width">Register</button>
    </form>

    <p class="switch-link">Already have an account? <a href="login.php">Login here</a></p>
  </section>
</main>

<?php include 'includes/footer.php'; ?>

<?php
require_once "../config/db.php";
require_once "../config/constants.php"; // Include constants.php for BASE_URL
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Sanitize and validate inputs
  $name     = htmlspecialchars(trim($_POST["name"] ?? ''));
  $email    = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
  $contact  = htmlspecialchars(trim($_POST["contact"] ?? ''));
  $license  = htmlspecialchars(trim($_POST["license_number"] ?? ''));
  $password = $_POST["password"] ?? '';
  $confirm  = $_POST["confirm"] ?? '';

  // Validate required fields
  if (empty($name) || empty($email) || empty($contact) || empty($license) || empty($password) || empty($confirm)) {
    $_SESSION["flash_warning"] = "All fields are required.";
    header("Location: " . BASE_URL . "register.php");
    exit();
  }

  // Email format validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["flash_error"] = "Invalid email address.";
    header("Location: " . BASE_URL . "register.php");
    exit();
  }

  // Password match check
  if ($password !== $confirm) {
    $_SESSION["flash_error"] = "Passwords do not match.";
    header("Location: " . BASE_URL . "register.php");
    exit();
  }

  // Password strength check (optional, you can customize this further)
  if (strlen($password) < 8) {
    $_SESSION["flash_error"] = "Password must be at least 8 characters long.";
    header("Location: " . BASE_URL . "register.php");
    exit();
  }

  // Hash the password
  $hashed = password_hash($password, PASSWORD_DEFAULT);

  try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
      $_SESSION["flash_warning"] = "Email is already registered.";
      header("Location: " . BASE_URL . "register.php");
      exit();
    }

    // Insert user into database
    $stmt = $pdo->prepare("
      INSERT INTO users (name, email, contact, license_number, password, status, role, verified)
      VALUES (?, ?, ?, ?, ?, 'active', 'user', 1)
    ");
    $stmt->execute([$name, $email, $contact, $license, $hashed]);

    $_SESSION["flash_success"] = "Registration successful! You can now log in.";
    header("Location: " . BASE_URL . "login.php");
    exit();

  } catch (PDOException $e) {
    // Log detailed error for developers (database error)
    error_log("Database Error [Register]: " . $e->getMessage());

    $_SESSION["flash_error"] = "Something went wrong. Please try again.";
    header("Location: " . BASE_URL . "register.php");
    exit();
  }
} else {
  // Redirect if not a POST request
  header("Location: " . BASE_URL . "register.php");
  exit();
}

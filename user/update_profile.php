<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$name = trim($_POST['name']);
$contact = trim($_POST['contact']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Update query
if (!empty($password)) {
  if ($password !== $confirm_password) {
    $_SESSION['flash_error'] = "Passwords do not match.";
    header("Location: profile.php");
    exit();
  }
  $hashed = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare("UPDATE users SET name = ?, contact = ?, password = ? WHERE id = ?");
  $stmt->execute([$name, $contact, $hashed, $user_id]);
} else {
  $stmt = $pdo->prepare("UPDATE users SET name = ?, contact = ? WHERE id = ?");
  $stmt->execute([$name, $contact, $user_id]);
}

$_SESSION['flash_success'] = "Profile updated successfully.";
header("Location: profile.php");
exit();

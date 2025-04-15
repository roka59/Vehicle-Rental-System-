<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../backend/_user_login.php");
    exit();
}

// Get admin information
$admin_id = $_SESSION['user_id'];
$admin_username = $_SESSION['username'];

// Additional security check - verify admin status from database
require_once(__DIR__ . '/../connections/conn.php');
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE user_id = ? AND is_admin = 1");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    // If not found in database as admin, destroy session and redirect
    session_destroy();
    header("Location: ../backend/_user_login.php");
    exit();
}
$conn->close();
?>
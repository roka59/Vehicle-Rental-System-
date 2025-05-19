<?php
session_start();
require_once "../config/db.php";
require_once "../config/constants.php"; // Include constants.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate input
    $user_id = isset($_POST["user_id"]) ? (int)$_POST["user_id"] : 0;
    $new_password = trim($_POST["new_password"] ?? '');
    $confirm_password = trim($_POST["confirm_password"] ?? '');

    if ($user_id <= 0) {
        $_SESSION["flash_error"] = "Invalid user ID.";
        header("Location: " . BASE_URL . "/new_password.php");
        exit();
    }

    if (empty($new_password) || empty($confirm_password)) {
        $_SESSION["flash_error"] = "⚠️ Password fields cannot be empty.";
        header("Location: " . BASE_URL . "/new_password.php?user_id=$user_id");
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION["flash_error"] = "⚠️ Passwords do not match.";
        header("Location: " . BASE_URL . "/new_password.php?user_id=$user_id");
        exit();
    }

    // Optional: Add a password strength check
    if (strlen($new_password) < 8) {
        $_SESSION["flash_error"] = "⚠️ Password must be at least 8 characters long.";
        header("Location: " . BASE_URL . "/new_password.php?user_id=$user_id");
        exit();
    }

    try {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->execute([
            ":password" => $hashed,
            ":id" => $user_id
        ]);

        $_SESSION["flash_success"] = "✅ Password updated successfully. Please login.";
        header("Location: " . BASE_URL . "/login.php");
        exit();
    } catch (PDOException $e) {
        // Optionally log $e->getMessage() to a file for debugging instead of exposing to the user
        $_SESSION["flash_error"] = "❌ Failed to update password. Please try again later.";
        header("Location: " . BASE_URL . "/new_password.php?user_id=$user_id");
        exit();
    }
} else {
    header("Location: " . BASE_URL . "/reset_password_request.php");
    exit();
}

<?php
session_start();
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = (int)$_POST["user_id"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($new_password) || empty($confirm_password)) {
        $_SESSION["flash_error"] = "Password fields cannot be empty.";
        header("Location: ../new_password.php");
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION["flash_error"] = "Passwords do not match.";
        header("Location: ../new_password.php");
        exit();
    }

    try {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed, $user_id]);

        $_SESSION["flash_success"] = "✅ Password updated successfully. Please login.";
        header("Location: ../login.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION["flash_error"] = "Database error: " . $e->getMessage();
        header("Location: ../new_password.php");
        exit();
    }
} else {
    header("Location: ../reset_password_request.php");
    exit();
}

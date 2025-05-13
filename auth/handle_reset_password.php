<?php
session_start();
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $license_number = isset($_POST["license_number"]) ? trim($_POST["license_number"]) : '';

    if (empty($email) || empty($license_number)) {
        $_SESSION["flash_error"] = "Both email and license number are required.";
        header("Location: ../reset_password_request.php");
        exit();
    }

    try {
        // Prepare and execute the SQL query
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND license_number = ?");
        $stmt->execute([$email, $license_number]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION["reset_user_id"] = $user["id"];
            $_SESSION["flash_info"] = "Identity verified. You can now reset your password.";
            header("Location: ../new_password.php");
            exit();
        } else {
            $_SESSION["flash_error"] = "No user found with that email and license number.";
            header("Location: ../reset_password_request.php");
            exit();
        }
    } catch (PDOException $e) {
        // Log actual error to file or console for debugging in production
        error_log("Reset password error: " . $e->getMessage());
        $_SESSION["flash_error"] = "Database error occurred. Please try again later.";
        header("Location: ../reset_password_request.php");
        exit();
    }
} else {
    header("Location: ../reset_password_request.php");
    exit();
}

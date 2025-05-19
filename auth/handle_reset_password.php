<?php
session_start();
require_once "../config/db.php";
require_once "../config/constants.php"; // Include constants.php for BASE_URL

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and trim inputs
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $license_number = trim($_POST["license_number"] ?? '');

    // Validate inputs
    if (empty($email) || empty($license_number)) {
        $_SESSION["flash_error"] = "Both email and license number are required.";
        header("Location: " . BASE_URL . "/reset_password_request.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["flash_error"] = "Invalid email format.";
        header("Location: " . BASE_URL . "/reset_password_request.php");
        exit();
    }

    try {
        // Prepare and execute the SQL query
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND license_number = :license_number");
        $stmt->execute([':email' => $email, ':license_number' => $license_number]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Set session variables
            $_SESSION["reset_user_id"] = $user["id"];
            $_SESSION["flash_info"] = "Identity verified. You can now reset your password.";
            header("Location: " . BASE_URL . "/new_password.php");
            exit();
        } else {
            $_SESSION["flash_error"] = "No user found with that email and license number.";
            header("Location: " . BASE_URL . "/reset_password_request.php");
            exit();
        }
    } catch (PDOException $e) {
        // Log the exception and show a generic error message
        error_log("Reset password error: " . $e->getMessage());
        $_SESSION["flash_error"] = "Database error occurred. Please try again later.";
        header("Location: " . BASE_URL . "/reset_password_request.php");
        exit();
    }
} else {
    // Redirect if the request is not POST
    header("Location: " . BASE_URL . "/reset_password_request.php");
    exit();
}

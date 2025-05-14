<?php
session_start();
require_once "../config/db.php";
require_once "../config/constants.php"; // Include constants.php for BASE_URL

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and trim inputs
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"] ?? '';

    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION["flash_error"] = "Both email and password are required.";
        header("Location: " . BASE_URL . "login.php");
        exit();
    }

    try {
        // Prepare and execute the query
        $stmt = $pdo->prepare("SELECT id, name, password, role, status FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Check account status
            if ($user['status'] !== 'active') {
                $_SESSION['flash_error'] = "Your account is inactive. Please contact support.";
                header("Location: " . BASE_URL . "login.php");
                exit();
            }

            // Verify password
            if (password_verify($password, $user["password"])) {
                // Set session variables
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = htmlspecialchars($user["name"]);
                $_SESSION["user_role"] = $user["role"];

                $_SESSION['flash_success'] = "Welcome back, " . $_SESSION["user_name"] . "!";

                // Redirect based on user role
                if ($user["role"] === "admin") {
                    header("Location: " . BASE_URL . "admin/dashboard.php");
                } else {
                    header("Location: " . BASE_URL . "user/dashboard.php");
                }
                exit();
            } else {
                $_SESSION["flash_error"] = "Incorrect email or password.";
                header("Location: " . BASE_URL . "login.php");
                exit();
            }
        } else {
            $_SESSION["flash_error"] = "No account found with that email.";
            header("Location: " . BASE_URL . "login.php");
            exit();
        }
    } catch (PDOException $e) {
        // Log detailed error for developers (avoid showing it to users)
        error_log("Login Error: " . $e->getMessage());

        $_SESSION["flash_error"] = "An unexpected error occurred. Please try again.";
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
} else {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

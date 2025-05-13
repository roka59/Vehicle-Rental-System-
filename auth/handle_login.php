<?php
session_start();
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    try {
        $stmt = $pdo->prepare("SELECT id, name, password, role, status FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            if ($user['status'] !== 'active') {
                $_SESSION['flash_error'] = "Your account is inactive. Please contact support.";
                header("Location: ../login.php");
                exit();
            }

            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["user_role"] = $user["role"];

                $_SESSION['flash_success'] = "Welcome back, " . htmlspecialchars($user["name"]) . "!";

                if ($user["role"] === "admin") {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: ../user/dashboard.php");
                }
                exit();
            } else {
                $_SESSION["flash_error"] = "Incorrect email or password.";
                header("Location: ../login.php");
                exit();
            }
        } else {
            $_SESSION["flash_error"] = "No account found with that email.";
            header("Location: ../login.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION["flash_error"] = "An unexpected error occurred. Please try again.";
        header("Location: ../login.php");
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}

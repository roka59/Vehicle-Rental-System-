<?php
session_start();
include('../connections/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    $errors = [];
    
    // Check if username exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if($stmt->get_result()->num_rows > 0) {
        $errors[] = "Username already exists";
    }
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if($stmt->get_result()->num_rows > 0) {
        $errors[] = "Email already registered";
    }
    
    // Password validation
    if($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    if(empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("sss", $username, $email, $password);
        
        if($stmt->execute()) {
            $_SESSION['registration_success'] = true;
            header("Location: _user_login.php");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - BookNook</title>
    <style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #3498db;
        --success-color: #2ecc71;
        --danger-color: #e74c3c;
        --text-color: #333;
        --text-light: #666;
        --text-white: #fff;
        --bg-color: #f4f4f4;
        --bg-light: #fff;
        --border-color: #ddd;
        --shadow-color: rgba(0,0,0,0.1);
    }

    .auth-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 400px);
    }

    .auth-box {
        background: var(--bg-light);
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 5px var(--shadow-color);
        width: 100%;
        max-width: 400px;
    }

    .auth-box h2 {
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .auth-form .form-group {
        margin-bottom: 1.5rem;
    }

    .auth-form label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-color);
        font-weight: bold;
    }

    .auth-form input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-size: 1rem;
    }

    .auth-button {
        width: 100%;
        padding: 0.75rem;
        background: var(--primary-color);
        color: var(--text-white);
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
    }

    .auth-button:hover {
        background: var(--secondary-color);
    }

    .auth-links {
        text-align: center;
        margin-top: 1.5rem;
        color: var(--text-light);
    }

    .auth-links a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: bold;
    }

    .error {
        background: #fee;
        color: var(--danger-color);
        padding: 0.75rem;
        border-radius: 4px;
        margin-bottom: 1rem;
        text-align: center;
    }
    </style>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include('../include/header_nav.php'); ?>
    
    <main>
        <div class="auth-container">
            <div class="auth-box">
                <h2>Create an Account</h2>
                
                <?php
                if(isset($errors) && !empty($errors)) {
                    foreach($errors as $error) {
                        echo "<p class='error'>$error</p>";
                    }
                }
                ?>
                
                <form method="post" action="_user_registration.php" class="auth-form">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               required 
                               autocomplete="username">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required 
                               autocomplete="email">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="new-password">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               required 
                               autocomplete="new-password">
                    </div>
                    
                    <button type="submit" class="auth-button">Register</button>
                </form>
                
                <p class="auth-links">
                    Already have an account? <a href="_user_login.php">Login here</a>
                </p>
            </div>
        </div>
    </main>
    
    <?php include('../include/footer.php'); ?>
</body>
</html>
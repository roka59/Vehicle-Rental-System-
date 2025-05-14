<?php
session_start();
require_once dirname(__DIR__) . '/config/constants.php'; // Adjusted to use BASE_PATH
require_once BASE_PATH . 'config/db.php'; // Use defined BASE_PATH for db connection

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $vehicle_id = (int)$_POST['vehicle_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validation
    if (!$start_date || !$end_date || $start_date >= $end_date) {
        $_SESSION['flash_error'] = "Please enter a valid rental date range.";
        header("Location: " . BASE_URL . "booking.php?id=$vehicle_id");
        exit();
    }

    // Check for booking conflicts
    $conflictCheck = $pdo->prepare("
        SELECT * FROM rentals
        WHERE vehicle_id = ?
          AND status IN ('Pending', 'Approved')
          AND (
            (start_date <= ? AND end_date >= ?) OR
            (start_date <= ? AND end_date >= ?) OR
            (start_date >= ? AND end_date <= ?)
          )
    ");
    $conflictCheck->execute([
        $vehicle_id,
        $start_date, $start_date,
        $end_date, $end_date,
        $start_date, $end_date
    ]);

    if ($conflictCheck->rowCount() > 0) {
        $_SESSION['flash_error'] = "This vehicle is already booked for the selected dates.";
        header("Location: " . BASE_URL . "booking.php?id=$vehicle_id");
        exit();
    }

    // Save booking
    $stmt = $pdo->prepare("INSERT INTO rentals (user_id, vehicle_id, start_date, end_date, status) VALUES (?, ?, ?, ?, 'Pending')");
    $stmt->execute([$user_id, $vehicle_id, $start_date, $end_date]);

    $_SESSION['flash_success'] = "Booking confirmed! Please proceed to My Booking for further process.";
    $rental_id = $pdo->lastInsertId();
    header("Location: " . BASE_URL . "booking_review.php?rental_id=$rental_id");
    exit();
} else {
    header("Location: " . BASE_URL . "vehicles/list.php");
    exit();
}

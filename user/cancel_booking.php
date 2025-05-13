<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $user_id = $_SESSION['user_id'];
  $rental_id = (int)$_POST['rental_id'];

  // Only cancel if it's their booking and still pending
  $stmt = $pdo->prepare("SELECT * FROM rentals WHERE rental_id = ? AND user_id = ? AND status = 'Pending'");
  $stmt->execute([$rental_id, $user_id]);
  $booking = $stmt->fetch();

  if ($booking) {
    // Step 1: Cancel the booking
    $update = $pdo->prepare("UPDATE rentals SET status = 'Cancelled' WHERE rental_id = ?");
    $update->execute([$rental_id]);
  
    // Step 2: Check for other active bookings for the same vehicle
    $vehicle_id = $booking['vehicle_id'];
  
    $checkStmt = $pdo->prepare("
      SELECT COUNT(*) FROM rentals
      WHERE vehicle_id = ? AND status IN ('Pending', 'Approved')
    ");
    $checkStmt->execute([$vehicle_id]);
    $activeBookings = $checkStmt->fetchColumn();
  
    // Step 3: If no other active bookings, mark vehicle available
    if ($activeBookings == 0) {
      $updateVehicle = $pdo->prepare("UPDATE vehicles SET availability = 'Available' WHERE vehicle_id = ?");
      $updateVehicle->execute([$vehicle_id]);
    }
  
    $_SESSION['flash_success'] = "Booking cancelled successfully.";
  } else {
    $_SESSION['flash_error'] = "Cannot cancel this booking.";
  }
  
}

header("Location: my_booking.php");
exit();

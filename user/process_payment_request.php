<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $rental_id = (int)$_POST['rental_id'];
  $method = $_POST['method'];
  $reference = trim($_POST['reference']);

  $allowedMethods = ['Bank Transfer', 'Cash'];
  if (!in_array($method, $allowedMethods)) {
    header("Location: payment_request.php?error=invalid_method");
    exit();
  }

  // Check if this rental already has a payment
  $check = $pdo->prepare("SELECT 1 FROM payments WHERE rental_id = ?");
  $check->execute([$rental_id]);
  if ($check->fetch()) {
    header("Location: payment_request.php?error=already_requested");
    exit();
  }

  // Fetch rental details
  $stmt = $pdo->prepare("
    SELECT v.rental_price, r.start_date, r.end_date
    FROM rentals r
    JOIN vehicles v ON r.vehicle_id = v.vehicle_id
    WHERE r.rental_id = ? AND r.user_id = ?
  ");
  $stmt->execute([$rental_id, $user_id]);
  $rental = $stmt->fetch();

  if (!$rental) {
    header("Location: payment_request.php?error=notfound");
    exit();
  }

  // Calculate amount
  $days = (strtotime($rental['end_date']) - strtotime($rental['start_date'])) / 86400;
  $amount = $rental['rental_price'] * max(1, $days);

  // Insert payment
  $stmt = $pdo->prepare("
    INSERT INTO payments (rental_id, amount, method, reference_note, status)
    VALUES (?, ?, ?, ?, 'Pending')
  ");
  $stmt->execute([$rental_id, $amount, $method, $reference]);

  // Update rental status
  $stmt = $pdo->prepare("UPDATE rentals SET status = 'Pending' WHERE rental_id = ?");
  $stmt->execute([$rental_id]);

  header("Location: my_booking.php?payment=success");
  exit();
} else {
  header("Location: payment_request.php");
  exit();
}

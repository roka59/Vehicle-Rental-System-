<?php
require_once '../../config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SwiftRide - User Panel</title>

  <!-- Global Styles -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css" />

  <!-- Optional Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<header class="main-header">
  <div class="container">
    <h1 class="logo">SwiftRide</h1>
    <nav class="nav-links">
      <a href="<?= BASE_URL ?>/user/dashboard.php">Dashboard</a>
      <a href="<?= BASE_URL ?>/vehicles/list.php">Vehicles</a>
      <a href="<?= BASE_URL ?>/user/my_booking.php">My Bookings</a>
      <a href="<?= BASE_URL ?>/user/payment_history.php">Payments</a>
      <a href="<?= BASE_URL ?>/user/profile.php">Profile</a>
      <a href="<?= BASE_URL ?>/logout.php" class="btn-link">Logout</a>
    </nav>
  </div>
</header>

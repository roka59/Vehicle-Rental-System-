<?php
require_once dirname(__DIR__, 2) . '/config/constants.php'; // Corrected relative path
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
   <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css" />
</head>
<body>
  <header class="main-header">
    <div class="container">
      <h1 class="logo"><a href="<?= BASE_URL ?>/user/dashboard.php" class="logo_url">SwiftRide</a></h1>
      <nav class="nav-links">
        <a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a>
        <a href="<?= BASE_URL ?>/admin/manage-users.php">Users</a>
        <a href="<?= BASE_URL ?>/admin/manage-vehicles.php">Vehicles</a>
        <a href="<?= BASE_URL ?>/admin/manage-bookings.php">Bookings</a>
        <a href="<?= BASE_URL ?>/logout.php" class="btn-link">Logout</a>
      </nav>
    </div>
  </header>

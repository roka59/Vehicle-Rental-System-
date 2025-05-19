<?php
  require_once dirname(__DIR__, 1) . '/config/constants.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Rent reliable and well-maintained vehicles from our platform. Convenient, affordable, and flexible transportation solutions for your needs.">
  <meta name="keywords" content="vehicle rental, car rental, vehicle booking, transportation, rental platform">
  <meta name="robots" content="index, follow">

  <title>SwiftRide - Vehicle Rental System</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&display=swap">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css" />
</head>
<body>
  <header class="main-header">
    <div class="container">
    <h1 class="logo"><a href="<?= BASE_URL ?>/index.php" class="logo_url">SwiftRide</a></h1>
      <nav class="nav-links">
        <a href="<?= BASE_URL ?>/index.php">Home</a>
        <a href="<?= BASE_URL ?>/vehicles/list.php">Vehicles</a>
        <a href="<?= BASE_URL ?>/about_us.php">About</a>
        <a href="<?= BASE_URL ?>/contact_us.php">Contact</a>
        <a href="<?= BASE_URL ?>/login.php" class="btn-link">Login</a>
        <a href="<?= BASE_URL ?>/register.php" class="btn-link">Register</a>
      </nav>
    </div>
  </header>

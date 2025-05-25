<?php
session_start();
require_once '../config/db.php';
require_once "../config/constants.php";
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location:". BASE_URL . "/login.php");
  exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $model = trim($_POST['model']);
  $type = $_POST['type'];
  $rental_price = $_POST['rental_price'];
  $availability = $_POST['availability'];
  $description = trim($_POST['description']);

  // Image Upload
  $imageName = 'default.jpg';
  if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageName = uniqid() . "." . $ext;
    move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/" . $imageName);
  }

  // Insert vehicle
  $stmt = $pdo->prepare("INSERT INTO vehicles (model, type, rental_price, availability, image, description)
                         VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([$model, $type, $rental_price, $availability, $imageName, $description]);

  $_SESSION['flash_success'] = "Vehicle added successfully.";
  header("Location: manage_vehicles.php");
  exit();
}
?>

<main class="container admin-add-vehicle">
  <h2 class="section-title">Add New Vehicle</h2>

  <form method="POST" enctype="multipart/form-data" class="form-box">
    <div class="form-group">
      <label for="model">Model</label>
      <input type="text" name="model" id="model" required>
    </div>

    <div class="form-group">
      <label for="type">Type</label>
      <select name="type" id="type" required>
        <option value="">-- Select Type --</option>
        <option value="Car">Car</option>
        <option value="Bike">Bike</option>
        <option value="Van">Van</option>
        <option value="Truck">Truck</option>
      </select>
    </div>

    <div class="form-group">
      <label for="rental_price">Rental Price (per day)</label>
      <input type="number" name="rental_price" id="rental_price" step="0.01" required>
    </div>

    <div class="form-group">
      <label for="availability">Availability</label>
      <select name="availability" id="availability" required>
        <option value="Available">Available</option>
        <option value="Unavailable">Unavailable</option>
      </select>
    </div>

    <div class="form-group">
      <label for="image">Vehicle Image</label>
      <input type="file" name="image" id="image" accept="image/*">
    </div>

    <div class="form-group">
      <label for="description">Description</label>
      <textarea name="description" id="description" rows="4"></textarea>
    </div>

    <button type="submit" class="btn-primary">Add Vehicle</button>
  </form>
</main>

<?php include 'includes/footer.php'; ?>

<?php
session_start();
require_once '../config/db.php';
require_once "../config/constants.php";
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location:". BASE_URL . "/login.php");
  exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  $_SESSION['flash_error'] = "Invalid vehicle ID.";
  header("Location: manage_vehicles.php");
  exit();
}

$vehicle_id = (int)$_GET['id'];

// Fetch vehicle
$stmt = $pdo->prepare("SELECT * FROM vehicles WHERE vehicle_id = ?");
$stmt->execute([$vehicle_id]);
$vehicle = $stmt->fetch();

if (!$vehicle) {
  $_SESSION['flash_error'] = "Vehicle not found.";
  header("Location: manage_vehicles.php");
  exit();
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $model = trim($_POST['model']);
  $type = $_POST['type'];
  $rental_price = $_POST['rental_price'];
  $availability = $_POST['availability'];
  $description = trim($_POST['description']);
  $imageName = $vehicle['image']; // Default to existing image

  // If new image uploaded
  if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageName = uniqid() . "." . $ext;
    move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/" . $imageName);
  }

  $stmt = $pdo->prepare("UPDATE vehicles SET model=?, type=?, rental_price=?, availability=?, image=?, description=? WHERE vehicle_id=?");
  $stmt->execute([$model, $type, $rental_price, $availability, $imageName, $description, $vehicle_id]);

  $_SESSION['flash_success'] = "Vehicle updated successfully.";
  header("Location: manage_vehicles.php");
  exit();
}
?>

<main class="container admin-edit-vehicle">
  <h2 class="section-title">Edit Vehicle</h2>

  <form method="POST" enctype="multipart/form-data" class="form-box">
    <div class="form-group">
      <label for="model">Model</label>
      <input type="text" name="model" id="model" value="<?= htmlspecialchars($vehicle['model']) ?>" required>
    </div>

    <div class="form-group">
      <label for="type">Type</label>
      <select name="type" id="type" required>
        <?php foreach (['Car', 'Bike', 'Van', 'Truck'] as $typeOption): ?>
          <option value="<?= $typeOption ?>" <?= $vehicle['type'] === $typeOption ? 'selected' : '' ?>><?= $typeOption ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="rental_price">Rental Price (per day)</label>
      <input type="number" name="rental_price" id="rental_price" step="0.01" value="<?= $vehicle['rental_price'] ?>" required>
    </div>

    <div class="form-group">
      <label for="availability">Availability</label>
      <select name="availability" id="availability" required>
        <option value="Available" <?= $vehicle['availability'] === 'Available' ? 'selected' : '' ?>>Available</option>
        <option value="Unavailable" <?= $vehicle['availability'] === 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
      </select>
    </div>

    <div class="form-group">
      <label>Current Image</label><br>
      <img src="../assets/images/<?= $vehicle['image'] ?>" width="150" style="object-fit:cover; border-radius:6px;">
    </div>

    <div class="form-group">
      <label for="image">Change Image (optional)</label>
      <input type="file" name="image" id="image" accept="image/*">
    </div>

    <div class="form-group">
      <label for="description">Description</label>
      <textarea name="description" id="description" rows="4"><?= htmlspecialchars($vehicle['description']) ?></textarea>
    </div>

    <button type="submit" class="btn-primary">Update Vehicle</button>
  </form>
</main>

<?php include 'includes/footer.php'; ?>

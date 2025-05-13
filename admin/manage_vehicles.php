<?php
session_start();
require_once "../includes/auth.php";
require_once "../includes/db.php";

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

$message = "";

// Handle Add Vehicle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
  $name = trim($_POST['name']);
  $type = trim($_POST['type']);
  $price = floatval($_POST['price']);

  if ($name && $type && $price > 0) {
    $stmt = $conn->prepare("INSERT INTO vehicles (name, type, price_per_day, status) VALUES (?, ?, ?, 'available')");
    $stmt->bind_param("ssd", $name, $type, $price);
    if ($stmt->execute()) {
      $message = "<div class='alert success'>Vehicle added successfully.</div>";
    }
  }
}

// Handle Delete
if (isset($_GET['delete'])) {
  $vid = (int)$_GET['delete'];
  $conn->query("DELETE FROM vehicles WHERE id = $vid");
  header("Location: manage-vehicles.php");
  exit();
}

// Fetch All Vehicles
$vehicles = $conn->query("SELECT * FROM vehicles ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Vehicles</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include "../includes/header.php"; ?>

<div class="container">
  <h2>Manage Vehicles</h2>

  <?= $message ?>

  <form method="POST" class="form-inline">
    <input type="text" name="name" placeholder="Vehicle Name" required>
    <input type="text" name="type" placeholder="Type (e.g. Sedan)" required>
    <input type="number" name="price" placeholder="Price/Day ($)" min="1" step="0.01" required>
    <button type="submit" name="add" class="btn">Add Vehicle</button>
  </form>

  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Vehicle</th>
        <th>Type</th>
        <th>Price/Day</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; while ($v = $vehicles->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($v['name']) ?></td>
          <td><?= htmlspecialchars($v['type']) ?></td>
          <td>$<?= number_format($v['price_per_day'], 2) ?></td>
          <td><?= ucfirst($v['status']) ?></td>
          <td>
            <a href="?delete=<?= $v['id'] ?>" class="btn-sm danger" onclick="return confirm('Delete this vehicle?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>

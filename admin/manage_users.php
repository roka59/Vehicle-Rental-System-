<?php
session_start();
require_once "../includes/auth.php";
require_once "../includes/db.php";

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

// Handle deactivation/reactivation
if (isset($_GET['toggle'])) {
  $uid = (int)$_GET['toggle'];
  $query = $conn->query("SELECT status FROM users WHERE id = $uid AND role = 'user'");
  if ($query && $user = $query->fetch_assoc()) {
    $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
    $conn->query("UPDATE users SET status = '$newStatus' WHERE id = $uid");
    header("Location: manage-users.php");
    exit();
  }
}

// Get all users
$result = $conn->query("SELECT id, name, email, contact, status FROM users WHERE role = 'user'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include "../includes/header.php"; ?>

<div class="container">
  <h2>Manage Users</h2>

  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['contact']) ?></td>
            <td><?= ucfirst($row['status']) ?></td>
            <td>
              <a href="?toggle=<?= $row['id'] ?>" class="btn-sm">
                <?= $row['status'] === 'active' ? 'Deactivate' : 'Reactivate' ?>
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6">No users found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>

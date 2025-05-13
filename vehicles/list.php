<?php
session_start();
require_once '../config/db.php';
require_once '../config/constants.php';

// Use user header if logged in, otherwise public header
if (isset($_SESSION['user_id'])) {
  include BASE_PATH . '/user/includes/header.php';
} else {
  include BASE_PATH . '/includes/header.php';
}
?>
<main class="container vehicle-page">
  <h1 class="section-title">Browse Vehicles</h1>

  <!-- Filters -->
  <form method="GET" class="filter-bar">
    <select name="type">
      <option value="">All Types</option>
      <option value="Car">Car</option>
      <option value="Bike">Bike</option>
      <option value="Van">Van</option>
    </select>

    <select name="price">
      <option value="">All Prices</option>
      <option value="low">Below $50/day</option>
      <option value="medium">$50 - $100/day</option>
      <option value="high">Above $100/day</option>
    </select>

    <select name="availability">
      <option value="">All Availability</option>
      <option value="Available">Available</option>
      <option value="Unavailable">Unavailable</option>
    </select>

    <button type="submit" class="btn-primary">Apply Filters</button>
  </form>

  <!-- Vehicle Listings -->
  <div class="vehicle-listing">
    <section class="vehicle-grid">
      <?php
      $query = "SELECT * FROM vehicles WHERE 1";
      $params = [];

      if (!empty($_GET['type'])) {
        $query .= " AND type = ?";
        $params[] = $_GET['type'];
      }

      if (!empty($_GET['price'])) {
        switch ($_GET['price']) {
          case 'low':
            $query .= " AND rental_price < 50";
            break;
          case 'medium':
            $query .= " AND rental_price BETWEEN 50 AND 100";
            break;
          case 'high':
            $query .= " AND rental_price > 100";
            break;
        }
      }

      if (!empty($_GET['availability'])) {
        $query .= " AND availability = ?";
        $params[] = $_GET['availability'];
      }

      $stmt = $pdo->prepare($query);
      $stmt->execute($params);
      $vehicles = $stmt->fetchAll();

      if ($vehicles) {
        foreach ($vehicles as $vehicle): ?>
          <div class="vehicle-card">
            <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($vehicle['image']) ?>" alt="<?= htmlspecialchars($vehicle['model']) ?>">
            <div class="vehicle-info">
              <h3><?= htmlspecialchars($vehicle['model']) ?></h3>
              <p>Type: <?= htmlspecialchars($vehicle['type']) ?></p>
              <p>Price: $<?= $vehicle['rental_price'] ?>/day</p>
              <p>Status: <?= $vehicle['availability'] ?></p>
              <a href="<?= BASE_URL ?>/vehicles/details.php?id=<?= $vehicle['vehicle_id'] ?>" class="btn-small">View Details</a>
            </div>
          </div>
        <?php endforeach;
      } else {
        echo "<p class='no-results'>No vehicles found matching your criteria.</p>";
      }
      ?>
    </section>
  </div>
</main>

<?php include BASE_PATH . '/includes/footer.php'; ?>

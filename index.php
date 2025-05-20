<?php 
include './includes/header.php'; 
require_once './config/db.php';
require_once './config/constants.php';
?>

<main class="home-container">
  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-overlay"></div>
    <img class="hero-image" src="<?= BASE_URL ?>/assets/images/1.jpg" alt="Modern car available for rental">
    <div class="hero-text">
      <h1>Car just for you</h1>
      <p>Get the car you want right now!!</p>
      <a href="<?= BASE_URL ?>/register.php" class="btn-primary">Get Started</a>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features">
    <div class="feature-card">
      <h3>Wide Vehicle Selection</h3>
      <p>From budget-friendly to luxury—choose what fits your style.</p>
      <a href="<?= BASE_URL ?>/about_us.php" class="btn-secondary">Learn More</a>
    </div>
    <div class="feature-card">
      <h3>Easy Booking</h3>
      <p>Book vehicles with just a few clicks. Hassle-free process!</p>
      <a href="<?= BASE_URL ?>/vehicles/list.php" class="btn-secondary">Browse</a>
    </div>
    <div class="feature-card">
      <h3>24/7 Support</h3>
      <p>We’re here whenever you need us. Chat, call, or email us.</p>
      <a href="<?= BASE_URL ?>/contact_us.php" class="btn-secondary">Contact</a>
    </div>
  </section>

  <!-- Browse Categories Section -->
  <section class="browse-categories">
    <h2 class="section-title">Browse Categories</h2>
    <div class="category-grid">
      <?php
      $types = ['Car', 'Bike', 'Van'];

      foreach ($types as $type) {
          $stmt = $pdo->prepare("SELECT image FROM vehicles WHERE type = :type");
          $stmt->execute(['type' => $type]);
          $vehicles = $stmt->fetchAll();

          if ($vehicles) {
              echo "<a href='vehicles/list.php?type=" . urlencode($type) . "' class='category-card'>";
              echo "<div class='category-slider'>";

              foreach ($vehicles as $vehicle) {
                  $img = htmlspecialchars($vehicle['image'] ?? 'default.jpg');
                  $alt = htmlspecialchars($type . " image");
                  $imgPath = "assets/images/" . $img;

                  echo "<img src='{$imgPath}' alt='{$alt}' class='category-image' loading='lazy' />";
              }

              echo "</div>";
              echo "<div class='category-info'>
                      <h3>" . htmlspecialchars($type) . "s</h3>
                      <p>Explore a variety of " . htmlspecialchars($type) . "s for your next trip.</p>
                    </div>";
              echo "</a>";
          }
      }
      ?>
    </div>
  </section>
</main>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".category-slider").forEach(slider => {
      const images = slider.querySelectorAll("img");
      let index = 0;

      if (images.length > 0) {
        images[0].classList.add("active");

        setInterval(() => {
          images[index].classList.remove("active");
          index = (index + 1) % images.length;
          images[index].classList.add("active");
        }, 4000);
      }
    });
  });
</script>

<?php include 'includes/footer.php'; ?>

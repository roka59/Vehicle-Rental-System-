<?php include './includes/header.php'; ?>

<main class="home-container">
  <section class="hero">
    <h1>Welcome to SwiftRide Rentals</h1>
    <p>Rent cars and bikes quickly, safely, and affordably.</p>
    <a href="register.php" class="btn-primary">Get Started</a>
  </section>

  <section class="features">
    <div class="feature-card">
      <h3>Wide Vehicle Selection</h3>
      <p>From budget-friendly to luxury—choose what fits your style.</p>
    </div>
    <div class="feature-card">
      <h3>Easy Booking</h3>
      <p>Book vehicles with just a few clicks. Hassle-free process!</p>
    </div>
    <div class="feature-card">
      <h3>24/7 Support</h3>
      <p>We’re here whenever you need us. Chat, call, or email us.</p>
    </div>
  </section>

  <section class="browse-categories">
  <h2 class="section-title">Browse Categories</h2>
  <div class="category-grid">
    
    <!-- Car Category -->
    <a href="vehicles/list.php?type=Car" class="category-card">
      <img src="assets/images/category_car.jpg" alt="Cars">
      <div class="category-info">
        <h3>Cars</h3>
        <p>Comfortable and fuel-efficient options perfect for daily commutes and long trips.</p>
      </div>
    </a>

    <!-- Bike Category -->
    <a href="vehicles/list.php?type=Bike" class="category-card">
      <img src="assets/images/category_bike.jpg" alt="Bikes">
      <div class="category-info">
        <h3>Bikes</h3>
        <p>Fast, agile, and ideal for solo travel or city cruising.</p>
      </div>
    </a>

    <!-- Van Category -->
    <a href="vehicles/list.php?type=Van" class="category-card">
      <img src="assets/images/category_van.jpg" alt="Vans">
      <div class="category-info">
        <h3>Vans</h3>
        <p>Spacious and reliable transport for families, groups, or cargo delivery.</p>
      </div>
    </a>

  </div>
</section>

</main>

<?php include 'includes/footer.php'; ?>

<?php
session_start();
require_once '../config/db.php';

// Use user header if logged in, otherwise public header
if (isset($_SESSION['user_id'])) {
  include '../user/includes/header.php';
} else {
  include '../includes/header.php';
}

// Ensure vehicle_id is set
if (!isset($_GET['vehicle_id'])) {
  echo "<p class='no-results'>Vehicle not found.</p>";
  include '../includes/footer.php';
  exit();
}

$vehicle_id = (int)$_GET['vehicle_id'];

// Fetch vehicle model
$stmt = $pdo->prepare("SELECT model FROM vehicles WHERE vehicle_id = ?");
$stmt->execute([$vehicle_id]);
$vehicle = $stmt->fetch();
$model_name = $vehicle ? $vehicle['model'] : 'Unknown Vehicle';

// Handle review submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['user_id'])) {
  $rating = (int)$_POST['rating'];
  $comment = trim($_POST['comment']);
  $user_id = $_SESSION['user_id'];

  $stmt = $pdo->prepare("INSERT INTO reviews (user_id, vehicle_id, rating, comment) VALUES (?, ?, ?, ?)");
  $stmt->execute([$user_id, $vehicle_id, $rating, $comment]);
  header("Location: reviews.php?vehicle_id=$vehicle_id");
  exit();
}

// Fetch all reviews for this vehicle
$stmt = $pdo->prepare("SELECT r.*, u.name FROM reviews r JOIN users u ON r.user_id = u.id WHERE vehicle_id = ? ORDER BY r.review_id DESC");
$stmt->execute([$vehicle_id]);
$reviews = $stmt->fetchAll();
?>

<main class="container review-page">
<div class="back-link">
  <button onclick="window.history.back();" class="btn-secondary">← Go Back</button>
</div>
  <h2 class="section-title">Reviews for <?= htmlspecialchars($model_name) ?></h2>

  <!-- Submit Review -->
<?php if (isset($_SESSION['user_id'])): ?>
  <?php
    // Check if the user already reviewed this vehicle
    $user_id = $_SESSION['user_id'];
    $checkStmt = $pdo->prepare("SELECT * FROM reviews WHERE user_id = ? AND vehicle_id = ?");
    $checkStmt->execute([$user_id, $vehicle_id]);
    $alreadyReviewed = $checkStmt->fetch();
  ?>

  <?php if (!$alreadyReviewed): ?>
    <form action="" method="POST" class="review-form">
      <label for="rating">Your Rating:</label>
      <select name="rating" required>
        <option value="">Select</option>
        <option value="5">⭐⭐⭐⭐⭐</option>
        <option value="4">⭐⭐⭐⭐</option>
        <option value="3">⭐⭐⭐</option>
        <option value="2">⭐⭐</option>
        <option value="1">⭐</option>
      </select>

      <label for="comment">Your Review:</label>
      <textarea name="comment" rows="3" required placeholder="Share your experience..."></textarea>

      <button type="submit" class="btn-primary">Submit Review</button>
      
    </form>
  <?php else: ?>
    <p class="notice">✅ You have already submitted a review for this vehicle.</p>
  <?php endif; ?>
<?php else: ?>
  <p class="notice">You must <a href="../login.php">login</a> to post a review.</p>
<?php endif; ?>


  <!-- Display Reviews -->
  <div class="review-list">
    <?php if ($reviews): ?>
      <?php foreach ($reviews as $review): ?>
        <div class="review-item">
          <p class="review-author"><?= htmlspecialchars($review['name']) ?> - 
            <?= str_repeat("⭐", (int)$review['rating']) ?>
          </p>
          <p class="review-comment"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="no-reviews">No reviews yet for this vehicle.</p>
    <?php endif; ?>
  </div>
</main>

<?php include '../includes/footer.php'; ?>

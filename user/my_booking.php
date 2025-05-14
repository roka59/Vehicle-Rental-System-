<?php
// Adjust according to your folder structure
define("BASE_URL", "/Vehicle-Rental-System-"); // for use in URLs
define("BASE_PATH", $_SERVER['DOCUMENT_ROOT'] . "/Vehicle-Rental-System-"); // for includes

session_start();
require_once BASE_PATH . 'config/db.php';  // Updated to use BASE_PATH
include BASE_PATH . 'includes/header.php';  // Updated to use BASE_PATH

if (!isset($_SESSION['user_id'])) {
  header("Location: " . BASE_URL . "login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Determine filter
$filter = $_GET['filter'] ?? 'all';
$today = strtotime(date('Y-m-d'));

// Fetch bookings
$stmt = $pdo->prepare("
  SELECT r.*, v.model, v.image, p.status AS payment_status, p.created_at AS payment_date
  FROM rentals r
  JOIN vehicles v ON r.vehicle_id = v.vehicle_id
  LEFT JOIN payments p ON p.rental_id = r.rental_id
  WHERE r.user_id = ?
  ORDER BY r.start_date DESC
");
$stmt->execute([$user_id]);
$allBookings = $stmt->fetchAll();

// Filter bookings
$filteredBookings = [];
$countAll = count($allBookings);
$countPending = 0;
$countApproved = 0;
$countCompleted = 0;
$countCancelled = 0;

foreach ($allBookings as $booking) {
  $status = strtolower($booking['status']);
  $paymentStatus = strtolower($booking['payment_status'] ?? '');
  $start = strtotime($booking['start_date']);
  $end = strtotime($booking['end_date']);
  $isBookingApproved = $status === 'approved';
  $isBookingCompleted = $isBookingApproved && ($end < $today);
  $isBookingCancelled = $status === 'cancelled';
  if ($status === 'cancelled') $countCancelled++;
  elseif ($isBookingCompleted) $countCompleted++;
  elseif ($isBookingApproved) $countApproved++;
  elseif ($status === 'pending') $countPending++;
  // Apply filters
  if (
    $filter === 'all' ||
    ($filter === 'pending' && $status === 'pending') ||
    ($filter === 'approved' && $status === 'approved' && !$isBookingCompleted) ||
    ($filter === 'completed' && $isBookingCompleted) ||
    ($filter === 'cancelled' && $isBookingCancelled)
  ) {
    $filteredBookings[] = $booking;
  }
}

// Render card function (same as before)
function renderBookingCard($booking) {
  $status = strtolower($booking['status']);
  $paymentStatus = $booking['payment_status'] ?? null;
  $isPaymentApproved = isset($paymentStatus) && strtolower($paymentStatus) === 'approved';
  $hasPayment = !is_null($paymentStatus);
  $isBookingApproved = $status === 'approved';
  $isBookingCancelled = $status === 'cancelled';
  $today = strtotime(date('Y-m-d'));
  $start = strtotime($booking['start_date']);
  $end = strtotime($booking['end_date']);
  $daysLeft = ceil(($start - $today) / 86400);
  $isBookingCompleted = $isBookingApproved && ($end < $today);

  $cardClass = $isBookingCompleted ? 'booking-card completed' : ($isBookingApproved ? 'booking-card approved' : ($isBookingCancelled ? 'booking-card cancelled' : 'booking-card pending'));

  ob_start(); ?>

  <div class="<?= $cardClass ?>">
    <div class="vehicle-img">
      <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($booking['image']) ?>" alt="<?= $booking['model'] ?>"> <!-- Updated to use BASE_URL -->
    </div>
    <div class="booking-info">
      <h3><?= htmlspecialchars($booking['model']) ?></h3>
      <p><strong>From:</strong> <?= $booking['start_date'] ?></p>
      <p><strong>To:</strong> <?= $booking['end_date'] ?></p>
      <p class="status <?= $status ?>">Booking Status: <?= $booking['status'] ?></p>

      <?php
      $paymentText = $paymentStatus === 'Approved'
        ? "Paid on " . date('M d, Y', strtotime($booking['payment_date']))
        : ucfirst($paymentStatus ?? 'Not Paid');

      $paymentClass = strtolower(str_replace(' ', '-', $paymentStatus ?? 'not-paid'));
      ?>
      <p class="status <?= $paymentClass ?>">Payment Status: <?= $paymentText ?></p>

      <?php if ($isBookingCompleted): ?>
        <p class="badge completed">✔ Completed</p>
      <?php elseif ($isBookingCancelled): ?>
        <p class="badge cancelled">❌ Cancelled</p>
      <?php elseif ($booking['status'] === 'Approved' && $daysLeft > 0): ?>
        <p class="countdown">⏳ Starts in <?= $daysLeft ?> day<?= $daysLeft > 1 ? 's' : '' ?></p>
      <?php elseif ($booking['status'] === 'Approved' && $daysLeft === 0): ?>
        <p class="countdown">📅 Starts Today!</p>
      <?php endif; ?>

      <?php if ($booking['status'] === 'Pending' && !$isBookingApproved): ?>
        <button type="button" class="btn-primary small-btn" onclick="confirmCancel(<?= $booking['rental_id'] ?>)">
          Cancel Booking
        </button>

        <?php if (!$hasPayment): ?>
          <form action="<?= BASE_URL ?>payment_request.php" method="GET" style="display:inline;"> <!-- Updated to use BASE_URL -->
            <input type="hidden" name="rental_id" value="<?= $booking['rental_id'] ?>">
            <button type="submit" class="btn-secondary small-btn">Pay Now</button>
          </form>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>

  <?php return ob_get_clean();
}
?>

<main class="container booking-history">
  <h2 class="section-title">My Bookings</h2>

  <!-- Filter Tabs -->
  <div class="booking-filters">
  <a href="?filter=all" class="filter-btn <?= $filter === 'all' ? 'active' : '' ?>">
    All <span class="badge"><?= $countAll ?></span>
  </a>
  <a href="?filter=pending" class="filter-btn <?= $filter === 'pending' ? 'active' : '' ?>">
    Pending <span class="badge"><?= $countPending ?></span>
  </a>
  <a href="?filter=approved" class="filter-btn <?= $filter === 'approved' ? 'active' : '' ?>">
    Approved <span class="badge"><?= $countApproved ?></span>
  </a>
  <a href="?filter=completed" class="filter-btn <?= $filter === 'completed' ? 'active' : '' ?>">
    Completed <span class="badge"><?= $countCompleted ?></span>
  </a>
  <a href="?filter=cancelled" class="filter-btn <?= $filter === 'cancelled' ? 'active' : '' ?>">
    Cancelled <span class="badge"><?= $countCancelled ?></span>
  </a>
</div>

  <?php if ($filteredBookings): ?>
    <div class="booking-list">
      <?php foreach ($filteredBookings as $booking) echo renderBookingCard($booking); ?>
    </div>
  <?php else: ?>
    <p class="no-results">No bookings found for this filter.</p>
  <?php endif; ?>
</main>

<?php include BASE_PATH . 'includes/footer.php';  // Updated to use BASE_PATH ?>

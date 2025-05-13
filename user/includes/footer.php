<footer class="main-footer">
  <div class="container">
    <p>&copy; <?= date('Y') ?> SwiftRide. All rights reserved.</p>
  </div>
</footer>
<script>
function confirmCancel(rentalId) {
  Swal.fire({
    title: 'Cancel Booking?',
    text: 'This action cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, cancel it!',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = 'cancel_booking.php';

      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'rental_id';
      input.value = rentalId;

      form.appendChild(input);
      document.body.appendChild(form);
      form.submit();
    }
  });
}
</script>

</body>
</html>

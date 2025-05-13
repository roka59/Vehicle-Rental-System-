document.addEventListener("DOMContentLoaded", function () {
    // Define flash message types
    const flashTypes = ['success', 'error', 'info', 'warning'];
  
    // Auto-dismiss each message after 5 seconds
    setTimeout(function () {
      flashTypes.forEach(type => {
        const flash = document.getElementById(`flash-${type}`);
        if (flash) {
          flash.classList.add('fade-out');
          setTimeout(() => flash.remove(), 400); // Wait for fade-out before removing
        }
      });
    }, 5000);
  
    // Allow manual dismissal using close button
    const closeButtons = document.querySelectorAll('.close-btn');
    closeButtons.forEach(button => {
      button.addEventListener('click', function (e) {
        const flashMessage = e.target.closest('.flash-message');
        if (flashMessage) {
          flashMessage.classList.add('fade-out');
          setTimeout(() => flashMessage.remove(), 300);
        }
      });
    });
  });
  
  // Function to manually dismiss a flash message by ID
  function dismissFlash(flashId) {
    const flash = document.getElementById(flashId);
    if (flash) {
      flash.classList.add('fade-out');
      setTimeout(() => flash.remove(), 300);
    }
  }
// Password toggle script 
  function togglePasswordVisibility(id) {
    const passwordField = document.getElementById(id);
    const visibilityIcon = document.getElementById(`visibility-icon-${id}`);
    
    if (passwordField.type === "password") {
        passwordField.type = "text";  // Show the password
        visibilityIcon.textContent = "visibility";  // Show visibility icon
    } else {
        passwordField.type = "password";  // Hide the password
        visibilityIcon.textContent = "visibility_off";  // Show visibility_off icon
    }
}
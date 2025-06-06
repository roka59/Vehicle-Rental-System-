<?php include 'includes/header.php'; ?>

<main class="container contact-page">
  <section class="contact-hero">
    <h1 class="page-title">Contact Us</h1>
    <p class="intro-text">
      Have questions, feedback, or need help? We’d love to hear from you.
      Fill out the form below and we’ll get back to you shortly.
    </p>
  </section>

  <section class="contact-form-section">
    <form action="#" method="POST" class="contact-form" aria-labelledby="contact-form-title">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" placeholder="Your name" required aria-label="Full Name" />
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" placeholder="you@example.com" required aria-label="Email Address" />
      </div>

      <div class="form-group">
        <label for="subject">Subject</label>
        <input type="text" name="subject" id="subject" placeholder="Message subject" required aria-label="Subject" />
      </div>

      <div class="form-group">
        <label for="message">Message</label>
        <textarea name="message" id="message" rows="5" placeholder="Write your message..." required aria-label="Message"></textarea>
      </div>

      <button type="submit" class="btn-primary" aria-label="Send Message">Send Message</button>
    </form>
  </section>
</main>

<?php include 'includes/footer.php'; ?>

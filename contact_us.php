<?php include('include/header_nav.php'); ?>

<main>
    <div class="contact-container">
        <h1>Contact Us</h1>
        
        <div class="contact-content">
            <div class="contact-info">
                <h2>Get in Touch</h2>
                <p>Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                
                <div class="contact-details">
                    <div class="contact-item">
                        <h3>📍 Address</h3>
                        <p>123 Book Street</p>
                        <p>Sydney, NSW 2000</p>
                        <p>Australia</p>
                    </div>
                    
                    <div class="contact-item">
                        <h3>📞 Phone</h3>
                        <p>(02) 1234 5678</p>
                    </div>
                    
                    <div class="contact-item">
                        <h3>📧 Email</h3>
                        <p>info@booknook.com</p>
                    </div>
                    
                    <div class="contact-item">
                        <h3>⏰ Hours</h3>
                        <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
                        <p>Saturday: 10:00 AM - 4:00 PM</p>
                        <p>Sunday: Closed</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form">
                <h2>Send Us a Message</h2>
                <form action="contact_us.php" method="post">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                    
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject:</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    
                    <div class="form-group textarea-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include('include/footer.php'); ?>
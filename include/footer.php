<footer>
    <div class="footer-content">
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="about_us.php">About Us</a></li>
                <li><a href="contact_us.php">Contact</a></li>
                <li><a href="policy.php">Privacy Policy</a></li>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <li><a href="backend/_be_interface.php">Admin Backend</a></li>
                <?php else: ?>
                    <li><a href="backend/_be_interface.php">Staff Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Connect</h3>
            <p>📧 info@booknook.com</p>
            <p>📞 (02) 1234 5678</p>
        </div>
    </div>
    <div class="copyright">
        <p>© 2025 BookNook. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
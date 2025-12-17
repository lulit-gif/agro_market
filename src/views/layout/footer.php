    </main>
    
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>About</h4>
                <p style="font-size: 13px; margin: 0;">Connecting local farmers with quality-conscious consumers.</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <a href="/">Home</a>
                <a href="/products">Products</a>
                <a href="/about">About Us</a>
                <a href="/contact">Contact</a>
            </div>
            <div class="footer-section">
                <h4>Support</h4>
                <a href="/contact">Contact Us</a>
            </div>
            <div class="footer-section">
                <h4>Account</h4>
                <?php if (empty($_SESSION['user_id'])): ?>
                    <a href="/login">Sign In</a>
                    <a href="/register">Create Account</a>
                <?php else: ?>
                    <a href="/buyer/profile">My Profile</a>
                    <a href="/buyer/orders">My Orders</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Agro Market. Fresh from Farm to Your Table.</p>
        </div>
    </footer>
    
    <script src="/js/main.js"></script>
</body>
</html>


<?php require __DIR__ . '/../layout/header.php'; ?>

    <form method="POST" action="forgot-password.php" id="forgotPasswordForm" class="auth-form">
        <fieldset>
            <legend>Forgot Password</legend>
            <label>
                Email:
                <input type="email" name="email" placeholder="Enter your email" required />
            </label>
            <button type="submit">Submit</button>
        </fieldset>
    </form>

    <p class="switch">
        Remembered your password?
        <a href="login.php"> Log in →</a>
    </p>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<?php
// server-side handling for forgot-password may remain below
?>

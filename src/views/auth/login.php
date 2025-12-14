<?php require __DIR__ . '/../layout/header.php'; ?>

        <form method="POST" action="login.php" id="authForm" class="auth-form">
            <fieldset>
                <legend>Log in</legend>

                <label>
                    Email:
                    <input type="email" name="email" placeholder="Enter your email" required />
                </label>
                <label>
                    Password:
                    <input type="password" name="password" placeholder="Enter your password here" id="password" required />
                </label>

                <button type="submit">Log in</button>
            </fieldset>
        </form>

        <p class="switch">
            Forgot your Password?
            <a href="forgot-password.php">Forgot password? →</a>
        </p>

        <p class="switch">
            Don't have an account?
            <a href="register.php"> Sign-up →</a>
        </p>

<?php require __DIR__ . '/../layout/footer.php'; ?>


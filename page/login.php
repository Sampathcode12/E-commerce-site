<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | User Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <header class="login-header">
                <h1>Welcome back</h1>
                <p class="login-subtitle">Sign in to your account to continue</p>
            </header>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error" role="alert">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['admin'])): ?>
                <div class="alert alert-info" role="status">
                    Logging in as admin. Please proceed.
                </div>
            <?php endif; ?>

            <form action="process_login.php" method="POST" class="login-form">
                <?php if (isset($_GET['admin'])): ?>
                    <input type="hidden" name="user_type" value="admin">
                <?php endif; ?>

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-input-wrap">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                        <button type="button" class="password-toggle" aria-label="Show password" title="Show password">
                            <svg class="icon-eye" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="icon-eye-off" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <?php echo isset($_GET['admin']) ? 'Login as Admin' : 'Sign in'; ?>
                </button>
            </form>

            <p class="login-footer">
                Don't have an account? <a href="signup.php">Create account</a>
            </p>
        </div>
    </div>
</body>
</html>

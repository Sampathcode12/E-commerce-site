// Add to Cart Button

document.addEventListener('DOMContentLoaded', function() {
    const notification = document.getElementById('notification');
    const closeNotificationBtn = document.getElementById('close-notification');
    const loginBtn = document.getElementById('login-btn');
    const signupBtn = document.getElementById('signup-btn');
    
    // Show the notification when the page loads
    notification.style.display = 'block';

    // Close the notification
    closeNotificationBtn.addEventListener('click', function() {
        notification.style.display = 'none';
    });

    // Redirect to login page when Login button is clicked
    loginBtn.addEventListener('click', function() {
        window.location.href = 'login.php'; // Replace with your actual login page URL
    });

    // Redirect to sign up page when Sign Up button is clicked
    signupBtn.addEventListener('click', function() {
        window.location.href = 'signup.php'; // Replace with your actual sign-up page URL
    });
});

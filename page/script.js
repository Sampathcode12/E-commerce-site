// Wait for the DOM to load
document.addEventListener("DOMContentLoaded", function () {
    const loginBtn = document.getElementById("login-btn");
    const signupBtn = document.getElementById("signup-btn");
    const closeBtn = document.getElementById("close-notification");

    // Redirect to login page when the "Login" button is clicked
    loginBtn.addEventListener("click", function () {
        window.location.href = "php/login.php";
    });

    // Redirect to sign-up page when the "Sign Up" button is clicked
    signupBtn.addEventListener("click", function () {
        window.location.href = "php/signup.php";
    });

    // Redirect to home page when the "Close" button is clicked
    closeBtn.addEventListener("click", function () {
        window.location.href = "php/home.php";
    });
});

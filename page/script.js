// Wait for the DOM to load
document.addEventListener("DOMContentLoaded", function () {
    const loginBtn = document.getElementById("login-btn");
    const signupBtn = document.getElementById("signup-btn");
    const closeBtn = document.getElementById("close-notification");

    // Redirect to login page when the "Login" button is clicked
    loginBtn.addEventListener("click", function () {
        window.location.href = "login.php";
    });

    // Redirect to sign-up page when the "Sign Up" button is clicked
    signupBtn.addEventListener("click", function () {
        window.location.href = "signup.php";
    });

    // Redirect to home page when the "Close" button is clicked
    closeBtn.addEventListener("click", function () {
        window.location.href = "home.php";
    });
});




document.getElementById('product_ID').addEventListener('blur', function() {
    const productID = this.value;

    fetch(`check_product_id.php?product_ID=${productID}`)
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                alert('Product ID already exists. Please use a different ID.');
                this.value = ''; // Clear the input field
            }
        });
});

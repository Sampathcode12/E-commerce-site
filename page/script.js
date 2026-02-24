// Wait for the DOM to load
document.addEventListener("DOMContentLoaded", function () {
    const loginBtn = document.getElementById("login-btn");
    const signupBtn = document.getElementById("signup-btn");
    const closeBtn = document.getElementById("close-notification");

    if (loginBtn) {
        loginBtn.addEventListener("click", function () {
            window.location.href = "login.php";
        });
    }
    if (signupBtn) {
        signupBtn.addEventListener("click", function () {
            window.location.href = "signup.php";
        });
    }
    if (closeBtn) {
        closeBtn.addEventListener("click", function () {
            window.location.href = "home.php";
        });
    }

    // Password show/hide toggle (login, signup, etc.)
    document.querySelectorAll(".password-toggle").forEach(function (btn) {
        var input = btn.closest(".password-input-wrap") && btn.closest(".password-input-wrap").querySelector("input");
        if (!input) return;
        btn.setAttribute("aria-pressed", "false");
        btn.addEventListener("click", function () {
            var isPassword = input.type === "password";
            input.type = isPassword ? "text" : "password";
            btn.setAttribute("aria-pressed", isPassword ? "true" : "false");
            btn.setAttribute("aria-label", isPassword ? "Hide password" : "Show password");
            btn.setAttribute("title", isPassword ? "Hide password" : "Show password");
        });
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

function validateForm(event) {
    // Get form fields
    var firstName = document.getElementsByName("first_name")[0].value;
    var lastName = document.getElementsByName("last_name")[0].value;
    var email = document.getElementsByName("email")[0].value;

    // Get the error message element
    var errorMessage = document.getElementById("error-message");

    // Clear any previous error message
    errorMessage.textContent = "";

    // Initialize a variable to track validation status
    var valid = true;

    if (!/^[a-zA-Z]+$/.test(firstName) || !/^[a-zA-Z]+$/.test(lastName)) {
        errorMessage.textContent = "Names contain only alphabetical characters.";
        valid = false;
    }
    if (!/^\S+@\S+\.\S+$/.test(email)) {
        errorMessage.textContent = "Invalid email format. Please enter a valid email.";
        valid = false;
    }

    // Prevent form submission if there are errors
    if (!valid) {
        event.preventDefault();
    }

    return valid;
}

// JavaScript to toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    var passwordInput = document.getElementById('passwordInput');
    var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
});
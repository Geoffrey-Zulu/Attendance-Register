<?php
session_start();
if (
    $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) &&
    isset($_POST["password"])
) {
    // Get the input values from the form
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "113234adess", "attendance_register");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query to fetch user data
    $stmt = $conn->prepare("SELECT id, email, password, status, verification_token FROM registration WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($userId, $dbEmail, $dbPassword, $status, $verificationToken);


    if ($stmt->fetch()) {
        $stmt->close();
        // Verify password and check user status
        if (password_verify($password, $dbPassword)) {
            if ($status == 'verified' || $status == 'active') {
                // Authentication successful
                $_SESSION["user_id"] = $userId;
                header("Location: Front/auth/register/home.php"); // Redirect to the desired page
            } else {
                // Account not active
                $_SESSION["error_message"] = "Your account is not active.";
            }
        } else {
            // Authentication failed
            $_SESSION["error_message"] = "Incorrect password. Please try again.";
        }
    } else {
        // Email does not exist in the database
        $_SESSION["error_message"] = "The email you entered does not exist.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZCAS BIOMETRIC ATTENDANCE REGISTER - Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .login-form {
            text-align: center;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .login-form h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input {
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="login-form">
        <h1>WELCOME TO ZCAS REGISTER</h1>
        <img src="Assets/images/zcas-u-logo.png" alt="Logo" width="100">
        <p class="text-muted">Login</p>
        <form action="index.php" method="POST">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-envelope"></i>
                        </span>
                    </div>
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-lock"></i>
                        </span>
                    </div>
                    <input type="password" class="form-control" name="password" placeholder="Password" id="passwordInput" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <p class="text-muted mt-3">Don't have an account? <a href="Front/registration.php">Register</a></p>
        <p class="text-muted mt-3">Forgot Password? <a href="Front/reset.php">Reset</a></p>
        <?php
        // Check for error message and display it
        if (isset($_SESSION["error_message"])) {
            echo '<div class="alert alert-danger">' . $_SESSION["error_message"] . '</div>';
            unset($_SESSION["error_message"]); // Clear the session variable after displaying it
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script>
        // JavaScript to toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            var passwordInput = document.getElementById('passwordInput');
            var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        });
    </script>
</body>

</html>
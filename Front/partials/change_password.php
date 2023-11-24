<?php
ob_start();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["password"]) && isset($_POST["code"])) {
    $newPassword = $_POST["password"];
    $verificationCode = $_POST["code"];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "113234adess", "attendance_register");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query to fetch user data
    $stmt = $conn->prepare("SELECT id, email, password, status, verification_token FROM registration WHERE verification_token = ?");
    $stmt->bind_param("s", $verificationCode);
    $stmt->execute();
    $stmt->bind_result($userId, $dbEmail, $dbPassword, $status, $verificationToken);

    if ($stmt->fetch()) {
        $stmt->close();

        // Verification code exists in the database
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE registration SET password = ? WHERE verification_token = ?");
        $stmt->bind_param("ss", $hashedPassword, $verificationCode);
        $stmt->execute();
        $stmt->close();

        // Redirect the user to the login page
        header("Location: http://localhost/Attendance-Register/");
    } else {
        // Verification code does not exist in the database
        $_SESSION["error_message"] = "Invalid verification code";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .reset-box {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .reset-box h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .reset-box button {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="reset-box">
        <h2>Chanage Password</h2>

        <form action="change_password.php" method="POST">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-lock"></i>
                        </span>
                    </div>
                    <input type="password" class="form-control" name="password" placeholder="New Password" id="passwordInput" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-envelope"></i>
                        </span>
                    </div>
                    <input type="number" class="form-control" name="code" placeholder="Verification code" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Change</button>
            <?php
            // Check for error message and display it
            if (isset($_SESSION["error_message"])) {
                echo '<div class="alert alert-danger">' . $_SESSION["error_message"] . '</div>';
                unset($_SESSION["error_message"]); // Clear the session variable after displaying it
            }
            ?>
        </form>

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
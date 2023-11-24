<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    $email = $_POST["email"];

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

        // Email exists in the database
        // Generate a random reset code
        $reset_code = rand(100000, 999999);

        // Update the reset_code in the database
        $stmt = $conn->prepare("UPDATE registration SET verification_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $reset_code, $email);
        $stmt->execute();
        $stmt->close();

        // Send the reset code to the user's email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP(); // Send using SMTP
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'zulugeoffrey034@gmail.com'; // SMTP username
            $mail->Password = 'zmod ncyf bfjc vqlc'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
            $mail->Port = 465; //

            // Recipients
            $mail->setFrom('zulugeoffrey034@gmail.com', 'Geoffrey CEO');
            $mail->addAddress($email); // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Password Reset Code';
            $mail->Body    = 'Your password reset code is: ' . $reset_code;

            $mail->send();
            echo 'Reset code has been sent to your email.';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        // Redirect the user to the reset password page
        header("Location: partials/change_password.php");
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
    <title>Password Reset</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <h2>Password Reset</h2>
        <form action="reset.php" method="post">
            <div class="form-group">
                <input type="email" name="email"="form-control" placeholder="Enter your email" required style="width: 250px;">
            </div>
            <button type="submit" class="btn btn-primary" style="margin-bottom: 10px;">Reset</button>
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
</body>

</html>
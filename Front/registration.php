<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the MySQL database
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '113234adess';
    $dbName = 'attendance_register';

    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $employeeNo = $_POST['employee_no'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $verificationToken = bin2hex(random_bytes(32)); // Generate a verification token

    // Check if the email is already registered
    $checkEmailQuery = "SELECT email, status FROM registration WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($existingEmail, $status);
        $stmt->fetch();

        if ($status === 'pending') {
            // User with the same email exists and is unverified, send a verification link again
            $verificationLink = 'You previously started the registration process, follow the link we sent you, or email  <a href="mailto:zulugeoffrey034@gmail.com">admin</a> to start a new registration.';
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP(); // Send using SMTP
                $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'zulugeoffrey034@gmail.com'; // SMTP username
                $mail->Password = 'zmod ncyf bfjc vqlc'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
                $mail->Port = 465; // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                // Recipients
                $mail->setFrom('zulugeoffrey034@gmail.com', 'Geoffrey CEO');
                $mail->addAddress($email); // Add a recipient

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Zcas Biometric register';
                $mail->Body = "Thank you for your interest in the Zcas Biometric register: <br> $verificationLink <br>";
                $mail->send();
                header('Location: partials/registration_success.php');
            } catch (Exception $e) {
                header('Location: registration_failure.php');
            }
        } else if ($status === 'verified') {
            // User with the same email exists and is already verified
            header('Location: partials/email_exists.php');
        }
    } else {
        // Insert data into the database with 'pending' status and verification token
        $sql = "INSERT INTO registration (first_name, last_name, employee_no, email, password, status, verification_token)
                VALUES (?, ?, ?, ?, ?, 'pending', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $firstName, $lastName, $employeeNo, $email, $hashedPassword, $verificationToken);

        if ($stmt->execute()) {
            // Registration successful
            $verificationLink = "<a href='http://localhost/Attendance-Register/Front/partials/verify.php?token=$verificationToken'>Click here to finish your registration</a>";
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP(); // Send using SMTP
                $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'zulugeoffrey034@gmail.com'; // SMTP username
                $mail->Password = 'zmod ncyf bfjc vqlc'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
                $mail->Port = 465; // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                // Recipients
                $mail->setFrom('zulugeoffrey034@gmail.com', 'Geoffrey CEO');
                $mail->addAddress($email); // Add a recipient

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Zcas Biometric register';
                $mail->Body = "Thank you for your interest in the Zcas Biometric register: <br> $verificationLink <br>";
                $mail->send();
                header('Location: partials/registration_success.php');
            } catch (Exception $e) {
                header('Location: partials/registration_failure.php');
            }
        } else {
            // Registration failed
            header('Location: partials/registration_failure.php');
        }
    }

    // Close the database connection
    $conn->close();
}

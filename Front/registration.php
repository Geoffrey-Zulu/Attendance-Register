<?php
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

    // Insert data into the database
    $sql = "INSERT INTO registration (first_name, last_name, employee_no, email, password)
            VALUES ('$firstName', '$lastName', '$employeeNo', '$email', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        header('Location: ../index.php');
        exit;
    } else {
       
    }
    // Close the database connection
    $conn->close();
}
?>


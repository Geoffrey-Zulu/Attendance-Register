<?php
$servername = "localhost";
$username = "root";
$password = "113234adess";
$dbname = "attendance_register";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
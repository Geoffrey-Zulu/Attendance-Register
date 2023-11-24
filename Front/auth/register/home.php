<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    // User is not logged in. Redirect them back to the login page
    header("Location: http://localhost/Attendance-Register/");
    exit;
}
// Rest of your code goes here
echo "Hello world";
?>

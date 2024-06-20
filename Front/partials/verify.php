<?php
if (isset($_GET['token'])) {
    $verificationToken = $_GET['token'];

    // Connect to the database
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '113234adess';
    $dbName = 'attendance_register';

    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute a query to check if the token exists
    $checkTokenQuery = "SELECT email FROM registration WHERE verification_token = ?";

    if ($stmt = $conn->prepare($checkTokenQuery)) {
        $stmt->bind_param("s", $verificationToken);
        $stmt->execute();
        $stmt->store_result(); 

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($userEmail);
            $stmt->fetch();

            // Token matches - update user status
            $updateStatusQuery = "UPDATE registration SET status = 'verified' WHERE email = ?";
            if ($stmt2 = $conn->prepare($updateStatusQuery)) {
                $stmt2->bind_param("s", $userEmail);
                if ($stmt2->execute()) {
                    echo '<div style="font-family: Arial, sans-serif; font-size: 15px; text-align: center; color: green;">
                    <p>Verification successful. You can now log in.</p>
                    <a href="http://localhost/Attendance-Register" target="_blank" class="btn btn-primary mt-3" style="color: blue">Go to Login</a>
                </div>';
                } else {
                    echo "Error updating status: " . $stmt2->error;
                }
                $stmt2->close();
            } else {
                echo "Error preparing update statement: " . $conn->error;
            }
        } else {
            echo '<div style="font-family: Arial, sans-serif; font-size: 15px; text-align: center; color:red;">
            <p>Invalid or expired verification link.</p>
        </div>';
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
} else {
    echo '<div style="font-family: Arial, sans-serif; font-size: 15px; text-align: center; color:red;">
    <p>Invalid verification link.</p>
</div>';
}

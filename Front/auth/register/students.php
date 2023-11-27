<?php
include 'partials/connection.php';
if (isset($_SESSION["user_id"]) && !empty($_POST)) { // Check if session variable and POST data are set
    $lecturer_id = $_SESSION["user_id"]; // Get the logged in lecturer's ID from the session
    $first_name = $_POST["firstName"]; // Get the first name from the form data
    $last_name = $_POST["lastName"]; // Get the last name from the form data
    $student_number = $_POST["studentNumber"]; // Get the student number from the form data
    $fingerprint_data = $_POST["fingerprint"]; // Get the fingerprint data from the form data

    // Prepare an SQL statement
    $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, student_number, fingerprint_data, lecturer_id) VALUES (?, ?, ?, ?, ?)");

    // Bind the form data and the lecturer ID to the SQL statement
    $stmt->bind_param("ssssi", $first_name, $last_name, $student_number, $fingerprint_data, $lecturer_id);

    $studentNumber = $_POST["studentNumber"];
    $sql = "SELECT * FROM students WHERE student_number = ?";
    $select_stmt = $conn->prepare($sql);

    // prevent duplicate entry
    $select_stmt->bind_param("s", $studentNumber);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    if ($result->num_rows > 0) {
        // Student number already exists
        echo "<div class='alert alert-danger'>Student number already exists. Please use a different number.</div>";
    } else {
        // Execute the SQL statement
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Students
    </title>
    <style>
        .dashboard-item:hover {
            transform: none !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php include 'partials/header.php'; ?>

    <div class="card dashboard-item">
        <div class="card-body">
            <h5 class="card-title">Add Student</h5>
            <form action="students.php" method="POST">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="firstName">First Name</label>
                        <input type="text" class="form-control" name="firstName" placeholder="Enter first name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="lastName">Last Name</label>
                        <input type="text" class="form-control" name="lastName" placeholder="Enter last name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="studentNumber">Student Number</label>
                        <input type="text" class="form-control" name="studentNumber" placeholder="Enter student number" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="fingerprint">Fingerprint</label>
                        <input type="text" class="form-control" name="fingerprint" placeholder="Enter fingerprint data">
                        <!-- icon for fingerprint here -->
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Student</button>
            </form>
        </div>
    </div>

    <div class="card dashboard-item mt-4">
        <div class="card-body">
            <h5 class="card-title">Student List</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Student Number</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn2 = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn2->connect_error) {
                        die("Connection failed: " . $conn2->connect_error);
                    }

                    // Fetching student data
                    $lecturer_id = $_SESSION["user_id"];
                    $sql = "SELECT first_name, last_name, student_number FROM students WHERE lecturer_id = ?";
                    $select_stmt = $conn2->prepare($sql);

                    if (!$select_stmt) {
                        die('Error preparing selection statement: ' . $conn2->error);
                    }

                    $select_stmt->bind_param("i", $lecturer_id);
                    $select_success = $select_stmt->execute();

                    // Check for successful execution
                    if (!$select_success) {
                        die('Error executing selection statement: ' . $select_stmt->error);
                    }

                    $result = $select_stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['first_name'] . "</td>";
                        echo "<td>" . $row['last_name'] . "</td>";
                        echo "<td>" . $row['student_number'] . "</td>";
                        echo "<td>";
                        echo "<button type='button' class='btn btn-link' data-toggle='modal' data-target='#editModal'>";
                        echo "<i class='fas fa-pencil-alt'></i>";
                        echo "</button>";
                        echo "</td>";
                        echo "</tr>";
                    }

                    $select_stmt->close();
                    $conn2->close(); // Close the second connection

                    ?>
                </tbody>
            </table>
        </div>
    </div>



    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        // JavaScript logic here
    </script>
</body>

</html>
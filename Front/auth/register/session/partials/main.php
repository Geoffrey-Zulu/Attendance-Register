<?php
session_start();
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';
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

        /* Add this to your custom CSS or in the head of your HTML file */
        h4.page-header {
            text-align: center;
            margin-top: 50px;
            color: #A0594A;
            text-transform: uppercase;
            /* Adjust the margin as needed */
        }
    </style>
</head>

<body>
    <?php
    include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\header.php';
    ?>
<h4 class="page-header">Register</h4>

    <div class="card dashboard-item">
        <div class="card-body">
            <!-- <h5 class="card-title">Student Attendance for Course: <?php echo $_GET['courseName']; ?></h5> -->
            <!-- Student Table -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Student ID</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Present</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Implement logic to fetch and display student data based on course and date -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer and Scripts -->
    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\footer.php'; ?>

    <!-- Additional scripts go here -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>
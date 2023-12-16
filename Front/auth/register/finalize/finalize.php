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
    <!-- <style>
        .dashboard-item:hover {
            transform: none !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style> -->
</head>

<body>
    <?php
    include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\header.php';
    ?>

    <div class="container mt-5">
        <!-- Confirmation Box -->
        <div class="container mt-5">
    <!-- Confirmation Box -->
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h5 class="card-title">Confirm Password</h5>
            <form action="confirm-password.php" method="post">
                <div class="form-group">
                    <label for="password">Enter Your Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <a href="http://localhost/Attendance-Register/Front/auth/register/finalize/partials/main.php" class="btn btn-primary">
                    Confirm
                </a>
            </form>
        </div>
    </div>
</div>


        <!-- Footer and Scripts -->
        <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\footer.php'; ?>

        <!-- Additional scripts go here -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>
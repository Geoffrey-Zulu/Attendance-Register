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
    </style>
</head>

<body>
<?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\header.php'; ?>
<div class="card dashboard-item">
    <div class="card-body">
        <h5 class="card-title">Start Session</h5>
        <a href="http://localhost/Attendance-Register/Front/auth/register/session/partials/main.php" class="btn btn-primary">
            Start Session
        </a>
    </div>
</div>


<?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\footer.php'; ?>
<!-- Additional scripts go here -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
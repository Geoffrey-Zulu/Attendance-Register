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
        /* Add this to your custom CSS or in the head of your HTML file */
        h4.page-header {
            text-align: center;
            margin-top: 50px;
            color: #A0594A;
            text-transform: uppercase;
            /* Adjust the margin as needed */
        }

    .content-box {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
    }

    .scrollable-content {
        height: 300px; /* Set the desired height for the scrollable content */
        overflow-y: auto;
    }
</style>
</head>

<body>
    <?php
    include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\header.php';
    ?>
    <h4 class="page-header">End Semester</h4>
    <div class="container mt-5">
    <!-- Main Content Box with Scrollable Text -->
    <div class="card mt-4 content-box">
        <div class="card-body">
            <h5 class="card-title" style="text-align: center;">What will happen</h5>
            <div class="row">
                <!-- Scrollable Text Content -->
                <div class="col-md-12 scrollable-content">
                    <!-- Lorem Ipsum text for demonstration purposes -->
                    <?php
                    echo '<p>';
                    echo implode('</p><p>', array_fill(0, 10, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam gravida mi vitae nunc efficitur, eget dignissim nisi ullamcorper.'));
                    echo '</p>';
                    ?>
                           <div class="mt-4 text-center">
            <button type="button" class="btn btn-success">Finalize</button>
        </div>
                </div>
            </div>
        </div>
        <!-- Finalize Button -->
 
    </div>
</div>

    <!-- Footer and Scripts -->
    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\footer.php'; ?>

    <!-- Additional scripts go here -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>

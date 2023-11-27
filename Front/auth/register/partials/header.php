<?php
if (!isset($_SESSION["user_id"])) {
    // User is not logged in. Redirect them back to the login page
    header("Location: http://localhost/Attendance-Register/");
    exit;
}
// Fetch user name from the database based on the session user_id
$user_id = $_SESSION["user_id"];
$sql = "SELECT first_name, last_name, employee_no FROM registration WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $first_name = $row["first_name"];
    $last_name = $row["last_name"];
    $employee_no = $row["employee_no"];
    $fullname = $first_name . " " . $last_name;
} else {
    $first_name = "Unknown"; // Default name if not found
    $last_name = "Unknown";
    $employee_no = "Unkown";
    $fullname = "Unkown";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Custom Styles */

        body {
            padding-top: 56px;
            /* Adjusted for the fixed navbar */
        }

        .navbar-brand img {
            max-height: 40px;
            /* Adjust logo height */
        }

        .navbar-toggler-icon {
            border: 1px solid #000;
            /* Customizing the toggle icon */
        }

        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .profile-dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .profile-dropdown:hover .profile-dropdown-content {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Dashboard Styling */
        .dashboard-item {
            margin-bottom: 20px;
            transition: transform 0.3s ease-in-out;
        }

        .dashboard-item:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Footer Styling */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
        }

        /* Add more styles as needed */

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand">
            <img src="http://localhost/Attendance-Register/Assets/images/zcas-u-logo.png" alt="Logo"> Zcas Register
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="./home.php"><i class="fas fa-home"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-chart-bar"></i> Stats</a></li>
                <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-book"></i> Exam</a></li>
                <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>

            <ul class="navbar-nav ml-auto d-flex align-items-center"> <!-- 'ml-auto', 'd-flex', and 'align-items-center' here -->
                <li class="nav-item profile-dropdown">
                    <a class="nav-link position-relative" href="#">
                        <img src="http://localhost/Attendance-Register/Assets/images/user.png" alt="Profile" class="rounded-circle" style="max-width: 30px; margin-right: 10px;"> Profile
                        <div class="profile-dropdown-content border rounded p-3 position-absolute text-center">
                            <!-- dynamic content here (e.g., name, employee number) using JavaScript -->
                            <p class="mb-2">Name: <?php echo $fullname; ?></p>
                            <p class="mb-0">Employee Number: <?php echo $employee_no; ?></p>
                        </div>
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="partials/logout.php"><i class="fas fa-sign-out"></i> Log Out</a></li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
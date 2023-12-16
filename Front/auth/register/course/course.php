<?php
session_start();
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course</title>
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
<?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\header.php'; ?>
<h4 class="page-header">Course Management</h4>
<div class="card dashboard-item">
    <div class="card-body">
        <h5 class="card-title">Manage Courses</h5>
        <!-- Add Course Form -->
        <form>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="courseName">Course Name</label>
                    <input type="text" class="form-control" id="courseName" placeholder="Enter course name" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="courseCode">Course Code</label>
                    <input type="text" class="form-control" id="courseCode" placeholder="Enter course code" required>
                </div>
            </div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addStudentModal">
                Add Course
            </button>
        </form>
    </div>
</div>

<div class="card dashboard-item mt-4">
    <div class="card-body">
        <h5 class="card-title">Available Courses</h5>
        <!-- Course Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Course Name</th>
                    <th scope="col">Course Code</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample Course Row -->
                <tr>
                    <td>Introduction to Programming</td>
                    <td>CSCI101</td>
                    <td>
                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#addStudentModal">
                            <i class="fas fa-user-plus"></i> Add Student
                        </button>
                    </td>
                </tr>
                <!-- Repeat the structure for more courses -->
            </tbody>
        </table>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Add Student to Course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add your form or table content for adding students here -->
                <p>This is the modal content for adding students to the course.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\footer.php'; ?>

<!-- Additional scripts go here -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
 
</body>
</html>





















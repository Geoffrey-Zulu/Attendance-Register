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
    <link rel="stylesheet" href="http://localhost/Attendance-Register/Front/styles/styles.css">
    <style>
        .dashboard-item:hover {
            transform: none !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Add this to your custom CSS or in the head of your HTML file */
        h4 {
            text-align: center;
            margin-top: 50px;
            color: #A0594A;
            text-transform: uppercase;
            /* Adjust the margin as needed */
        }

        h5 {
            margin-top: 50px;
            color: #65B741;
            text-transform: uppercase;
            /* Adjust the margin as needed */
        }

        /* Add this to your existing style */
        .table-container {
            max-height: 400px;
            /* Set the desired height */
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\header.php'; ?>
    <h4 class="page-header">Course Management</h4>
    <div id="overlay" class="overlay">
        <div class="lds-facebook">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div class="card dashboard-item">
        <div class="card-body">
            <h5 class="card-title">Manage Courses</h5>
            <!-- Add Course Form -->
            <form method="POST" action="partials/addCourse.php">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="courseName">Course Name</label>
                        <input type="text" class="form-control" name="courseName" placeholder="Enter course name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="courseCode">Course Code</label>
                        <input type="text" class="form-control" name="courseCode" placeholder="Enter course code" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" onclick="spinner()">
                    Add Course
                </button>
            </form>
        </div>
    </div>


    <div class="card dashboard-item mt-4">
        <div class="card-body">
            <h5 class="card-title">Your Course List <?php echo $first_name; ?></h5>
            <!-- Course Table -->
            <div class="table-container">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Course Name</th>
                            <th scope="col">Course Code</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';
        
                        // Fetching available course data
                        $sql = "SELECT course_id, course_name, course_code FROM courses";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr id='courseRow_" . $row['course_code'] . "'>";
                                echo "<td>" . $row['course_name'] . "</td>";
                                echo "<td>" . $row['course_code'] . "</td>";
                                echo "<td>";
                                echo "<button type='button' class='btn btn-link' data-toggle='modal' data-target='#addStudentModal' data-course-id='" . $row['course_id'] . "' data-course-name='" . $row['course_name'] . "'>";
                                echo "<i class='fas fa-user-plus'></i> Add Student";
                                echo "</button>";
                                echo "<button type='button' class='btn btn-link' data-toggle='modal' data-target='#editModal' data-course-code='" . $row['course_code'] . "' data-course-name='" . $row['course_name'] . "'>";
                                echo "<i class='fa-solid fa-pencil' style='color: #ffc107;'></i>";
                                echo "</button>";
                                echo "<button type='button' class='btn btn-link delete-course' data-toggle='modal' data-target='#deleteModal' data-course-id='" . $row['course_id'] . "' data-course-name='" . $row['course_name'] . "'>";
                                echo "<i class='fa-solid fa-trash' style='color: #e7133d;'></i> ";
                                echo "</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No courses available</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- ... Rest of your HTML code ... -->

<<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Add Student to Course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addStudentForm">
                    <input type="hidden" id="courseId" value="">
                    <div id="studentCheckboxes"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addStudentsBtn">Add Students</button>
            </div>
        </div>
    </div>
</div>

    <!-- Modal for confirming course deletion -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the course?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- delete modal end -->

    <!-- ... Rest of your HTML code ... -->

    <!-- Modal for editing course details -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Course Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCourseForm">
                        <div class="form-group">
                            <label for="courseName">Course Name</label>
                            <input type="text" class="form-control" id="courseName" placeholder="Enter course name">
                        </div>
                        <div class="form-group">
                            <label for="courseCode">Course Code</label>
                            <input type="text" class="form-control" id="courseCode" placeholder="Enter course code" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmEditCourse">Save Changes</button>
                </div>
            </div>
        </div>
    </div>




    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\footer.php'; ?>



    
    <!-- Additional scripts go here -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="partials/delete_course.js"></script>
    <script src="partials/editCourse.js"></script>
    <script src="partials/add_students.js"></script>

    <script>
        // call the spinner 
        function spinner() {
            document.getElementById("overlay").style.display = "block";
            // set delay
            setTimeout(function() {
                window.location.href = "course.php";
            }, 2000); // 2 seconds delay
        }
    </script>
    <script>
    var lecturerId = <?php echo json_encode($_SESSION["user_id"]); ?>;
</script>

</body>

</html>
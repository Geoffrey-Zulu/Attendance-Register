<?php
require 'form.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Students</title>
    <link rel="stylesheet" href="partials/styles.css">
    <link rel="stylesheet" href="http://localhost/Attendance-Register/Front/styles/styles.css">
    <style>
        h4.page-header {
            text-align: center;
            margin-top: 50px;
            color: #a0594a;
            text-transform: uppercase;
        }
        h5 {
            margin-top: 50px;
            color: #65B741;
            text-transform: uppercase;
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        .hidden-image {
            display: none;
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\header.php'; ?>
    <!-- header end-->
    <h4 class="page-header">Student Management</h4>
    <!-- spinner -->
    <div id="overlay" class="overlay">
        <div class="lds-facebook">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- spinner end -->
    <!-- add student -->
    <div class="card dashboard-item">
        <div class="card-body">
            <h5 class="card-title">Add Student</h5>
            <form action="form.php" method="POST">
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
                        <div class="input-group">
                            <input type="hidden" class="form-control" name="fingerprint" id="fingerprintData">
                            <button type="button" class="btn btn-success" id="scanButton">Scan</button>
                            <img id="fingerprintImage" class="hidden-image" src="">
                        </div>
                    </div>
                    <?php
                    // success message 
                    if (isset($_SESSION["success_message"])) {
                        echo '<div class="alert alert-success">' . $_SESSION["success_message"] . '</div>';
                        unset($_SESSION["success_message"]);
                    }
                    ?>
                </div>
                <button type="submit" class="btn btn-primary" onclick="spinner()">Add Student</button>
            </form>
        </div>
    </div>
    <!-- add student end -->
    <!-- student list -->
    <div class="card dashboard-item mt-4">
        <div class="card-body">
            <h5 class="card-title">Your Student List</h5>
            <div class="table-container">
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
                        <?php include 'partials/table.php'; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- student list end -->
    <!-- Modal for confirming student deletion -->
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
                    Are you sure you want to delete this student?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- delete modal end -->
    <!-- edit modal start -->
    <!-- Modal for editing student details -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Student Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" placeholder="Enter first name">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" placeholder="Enter last name">
                        </div>
                        <div class="form-group">
                            <label for="studentNumber">Student Number</label>
                            <input type="text" class="form-control" id="studentNumber" placeholder="Enter student number">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmEdit">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- edit modal end -->
    <!-- Footer -->
    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\footer.php'; ?>
    <!-- footer end -->

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="partials/delete.js"></script>
    <script src="partials/edit.js"></script>
    <script src="partials/fingerprint.js"></script>

    <script type="text/javascript">
        var secugen_lic = ""; // Make sure this is set if required

        // Function to handle fingerprint capture
        function CallSGIFPGetData(successCall, failCall) {
            var uri = "https://localhost:8443/SGIFPCapture";
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var fpobject = JSON.parse(xmlhttp.responseText);
                    successCall(fpobject);
                } else if (xmlhttp.status == 404) {
                    failCall(xmlhttp.status);
                }
            };
            xmlhttp.onerror = function () {
                failCall(xmlhttp.status);
            };
            var params = "Timeout=10000&Quality=50&licstr=" + encodeURIComponent(secugen_lic) + "&templateFormat=ISO";
            xmlhttp.open("POST", uri, true);
            xmlhttp.send(params);
        }

        // Success function for fingerprint capture
        function SuccessFunc(result) {
            if (result.ErrorCode == 0) {
                document.getElementById('fingerprintImage').src = "data:image/bmp;base64," + result.BMPBase64;
                document.getElementById('fingerprintData').value = result.TemplateBase64;
                alert("Fingerprint captured successfully.");
            } else {
                alert("Fingerprint Capture Error: " + result.ErrorCode + " - " + ErrorCodeToString(result.ErrorCode));
            }
        }

        // Error function for fingerprint capture
        function ErrorFunc(status) {
            alert("Check if SGIBIOSRV is running; status = " + status);
        }

        // Event listener for scan button
        document.getElementById("scanButton").addEventListener("click", function () {
            CallSGIFPGetData(SuccessFunc, ErrorFunc);
        });

        function spinner() {
            document.getElementById("overlay").style.display = "block";
            setTimeout(function() {
                window.location.href = "student.php";
            }, 2000); 
        }
    </script>

</body>

</html>

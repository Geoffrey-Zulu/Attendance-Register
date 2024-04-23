<?php
session_start();
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the necessary data is present
    if (isset($_POST["firstName"]) && isset($_POST["lastName"]) && isset($_POST["studentNumber"])) {
        // Get the form data
        $first_name = $_POST["firstName"];
        $last_name = $_POST["lastName"];
        $student_number = $_POST["studentNumber"];

        // Prepare and execute the SQL statement to insert student details
        $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, student_number) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $first_name, $last_name, $student_number);

        if ($stmt->execute()) {
            echo "Student details inserted successfully. ";
        } else {
            echo "Error: Unable to insert student details. ";
        }
    } else {
        echo "Error: Insufficient data received. ";
    }

    // Check if fingerprint data is received
    if (isset($_POST['fingerprint'])) {
        $fingerprintData = $_POST['fingerprint'];

        // Prepare and execute the SQL statement to insert fingerprint data
        $stmt = $conn->prepare("UPDATE students SET fingerprint_data = ? WHERE student_number = ?");
        $stmt->bind_param("ss", $fingerprintData, $student_number);

        if ($stmt->execute()) {
            echo "Fingerprint data inserted successfully. ";
        } else {
            echo "Error: Unable to insert fingerprint data. ";
        }
    } else {
        echo "Fingerprint data not received. ";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Students
    </title>
    <link rel="stylesheet" href="partials/styles.css">
    <link rel="stylesheet" href="http://localhost/Attendance-Register/Front/styles/styles.css">
    <style>
        h4.page-header {
            text-align: center;
            margin-top: 50px;
            color: #a0594a;
            text-transform: uppercase;
            /* Adjust the margin as needed */
        }

        h5 {
            margin-top: 50px;
            color: #65B741;
            text-transform: uppercase;
            /* Adjust the margin as needed */
        }


        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\header.php'; ?>
    <h4 class="page-header">Student Management</h4>
    <div id="overlay" class="overlay">
        <div class="lds-facebook">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
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
                        <div class="input-group">
                            <input type="text" class="form-control" name="fingerprint" id="scanningStatus" placeholder="Enter fingerprint data" readonly>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success" id="scanButton">Scan</button>
                            </div>
                        </div>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary" onclick="spinner()">Add Student</button>
            </form>
        </div>
    </div>

    <div class="card dashboard-item mt-4">
        <div class="card-body">
            <h5 class="card-title">Your Student List <?php echo $first_name; ?></h5>
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

                        <?php
                        include 'partials/table.php';
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
                    Are you sure you want to delete student?'
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

    <script>
        // call the spinner 
        function spinner() {
            document.getElementById("overlay").style.display = "block";
            // set delay
            setTimeout(function() {
                window.location.href = "students.php";
            }, 2000); // 2 seconds delay
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Add this script at the end of your HTML body -->
    <script src="partials/delete.js"></script>
    <script src="partials/edit.js"></script>
    <script>
            // Add event listener for the scan button
            secugen_lic = ""
            document.getElementById("scanButton").addEventListener("click", scanFingerprint);
        async function scanFingerprint() {
            const uri = "https://localhost:8443/SGIFPCapture"; // Update the URI with the correct endpoint

            try {
                const response = await fetch(uri, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: new URLSearchParams({
                        licstr: secugen_lic, // Add your license key here
                    }),
                });

                if (response.ok) {
                    const fpobject = await response.json();
                    if (fpobject.ErrorCode === 0) {
                        // Display scanning status as "Scanning..."
                        document.getElementById("scanningStatus").value = "Scanning...";

                        // Get the fingerprint data
                        const fingerprintData = fpobject.TemplateBase64;
                        if (fingerprintData) {
                            // Send fingerprint data to PHP script using AJAX
                            sendFingerprintDataToPHP(fingerprintData);
                        } else {
                            // Update scanning status to indicate an error
                            document.getElementById("scanningStatus").value = "Error: Fingerprint data is empty.";
                        }
                    } else {
                        // Handle error
                        document.getElementById("scanningStatus").value = "Error: Fingerprint Capture Error Code: " + fpobject.ErrorCode;
                    }
                } else {
                    // Handle error
                    document.getElementById("scanningStatus").value = "Error occurred while capturing fingerprint.";
                }
            } catch (error) {
                // Handle error
                document.getElementById("scanningStatus").value = "Error occurred while capturing fingerprint: " + error.message;
            }
        }

        async function sendFingerprintDataToPHP(fingerprintData) {
            try {
                // Log the fingerprint data to verify
                console.log("Fingerprint data to be sent:", fingerprintData);

                // Send fingerprint data to PHP script using AJAX
                const response = await fetch('students.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        fingerprint: fingerprintData
                    })
                });

                if (response.ok) {
                    // Fingerprint data sent successfully
                    document.getElementById("scanningStatus").value = "Scan successful!";
                } else {
                    // Handle error
                    document.getElementById("scanningStatus").value = "Error: Failed to send fingerprint data.";
                }
            } catch (error) {
                // Handle error
                document.getElementById("scanningStatus").value = "Error occurred while sending fingerprint data: " + error.message;
            }
        }
    </script>
</body>

</html>
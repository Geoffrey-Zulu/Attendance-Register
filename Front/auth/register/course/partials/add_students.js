$(document).ready(function () {
    // Define populateStudentCheckboxes globally
    function populateStudentCheckboxes(students) {
        // Assuming there is a container with the id 'studentCheckboxes'
        var checkboxesContainer = $("#studentCheckboxes");
    
        // Clear previous checkboxes (if any)
        checkboxesContainer.empty();
    
        // Check if there are students to display
        if (students.length > 0) {
            // Loop through the students and create checkboxes
            students.forEach(function (student) {
                var checkboxId = "studentCheckbox_" + student.id;
    
                var checkbox = $("<input>")
                    .attr("type", "checkbox")
                    .attr("id", checkboxId)
                    .attr("value", student.id)
                    .addClass("student-checkbox");
    
                var label = $("<label>")
                    .attr("for", checkboxId)
                    .text(student.first_name + ' ' + student.last_name);
    
                // Append the checkbox and label to the container
                checkboxesContainer.append(checkbox);
                checkboxesContainer.append(label);
                checkboxesContainer.append("<br>"); // Add line break for better spacing
            });
        } else {
            // If no students are available, show a message or handle accordingly
            checkboxesContainer.text("No students available.");
        }
    }
    

    // Function to fetch students
    function fetchStudents(courseId) {
        return $.ajax({
            type: "POST",
            url: "partials/fetch_students.php",
            data: { courseId: courseId },
            dataType: 'text', // Expecting text data
            success: function (response) {
                try {
                    // Attempt to parse JSON data
                    var [debugInfo, jsonData] = response.split('|');
                    console.log("Debug Info:", debugInfo);
                    var students = JSON.parse(jsonData);
                    
                    // Log the response for debugging
                    console.log("Fetch Students Response:", students);
    
                    // Check if the response status is success
                    if (students && students.length > 0) {
                        // Continue with the rest of your logic
                        populateStudentCheckboxes(students);
                    } else {
                        // Handle error cases
                        console.error("Error: Invalid JSON data or no students found.");
                    }
                } catch (error) {
                    // Log any JSON parsing errors
                    console.error("Error parsing JSON data:", error);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                // Log detailed information about the error
                console.error("Error fetching students:");
                console.log("XHR:", xhr);
                console.log("Text Status:", textStatus);
                console.log("Error Thrown:", errorThrown);
            }
        });
    }
    

    // Handle the show event for the modal
    $("#addStudentModal").on("show.bs.modal", function (event) {
        // Get the course ID from the button that opened the modal
        var courseId = $(event.relatedTarget).data("course-id");

        // Store the course ID in the hidden input
        $("#courseId").val(courseId);

        // Log courseId to the console for debugging
        console.log("Course ID before fetchStudents:", courseId);

        // Call the fetchStudents function
        fetchStudents(courseId);
    });

    // Handle the form submission
    $("#addStudentsBtn").on("click", function (event) {
        // Prevent the form from being submitted normally
        event.preventDefault();

        // Get the selected student IDs
        var selectedStudents = $("input[type=checkbox]:checked").map(function () {
            return $(this).val();
        }).get();

        // Get the course ID
        var courseId = $("#courseId").val();

        // Make an AJAX request to add students to the course
        $.ajax({
            type: "POST",
            url: "partials/add_students.php",
            data: {
                courseId: courseId,
                studentIds: selectedStudents
            },
            dataType: 'json', // Expecting JSON data
            success: function (response) {
                // Log the response for debugging
                console.log("Add Students Response:", response);

                // Check if the response status is success
                if (response.status === 'success') {
                    // Hide modal
                    $("#addStudentModal").modal("hide");

                    // Show spinner
                    document.getElementById("overlay").style.display = "block";

                    // Set delay
                    setTimeout(function () {
                        window.location.href = "course.php";
                    }, 2000); // 2 seconds delay
                } else {
                    // Handle error cases
                    console.error("Error adding students:", response.message);
                }
            },
            error: function (error) {
                console.error("Error adding students:", error);
            }
        });
    });
});



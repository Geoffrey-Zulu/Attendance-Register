document.addEventListener("DOMContentLoaded", function () {
    // Handle the click event on the edit button
    $(".btn-link[data-target='#editModal']").on("click", function () {
        var courseCode = $(this).data("course-code");

        // Store the course code in the modal for reference
        $("#editModal").data("course-code", courseCode);

        // Pre-fill the form inputs with the current details
        $("#courseName").val($(this).data("course-name"));
        $("#courseCode").val(courseCode);
    });

    // Handle the confirm edit button click
    $("#confirmEditCourse").on("click", function () {
        // Get the course code from the modal
        var courseCode = $("#editModal").data("course-code");

        // Get the new details from the form inputs
        var courseName = $("#courseName").val();
        var newCourseCode = $("#courseCode").val();

        // Make an AJAX request to edit the course
        $.ajax({
            type: "POST",
            url: "partials/edit_course.php",
            data: {
                courseCode: courseCode,
                newCourseName: courseName,
                newCourseCode: newCourseCode,
            },
            success: function (response) {
                // Output the response to the console for debugging
                console.log(response);

                // Hide modal
                $("#editModal").modal("hide");

                document.getElementById("overlay").style.display = "block";
                setTimeout(function () {
                    window.location.href = "course.php";
                }, 2000); // 2 seconds delay
            },
            error: function (error) {
                console.error("Error editing course:", error);
            },
        });
    });
});

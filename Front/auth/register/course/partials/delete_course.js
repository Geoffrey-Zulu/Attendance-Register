document.addEventListener("DOMContentLoaded", function () {
  // Handle the click event on the delete button
  $(".delete-course").on("click", function () {
    var courseId = $(this).data("course-id");

    // Set the course ID in the modal for reference
    $("#deleteModal").data("course-id", courseId);
  });

  // Handle the confirm deletion button click
  $("#confirmDelete").on("click", function () {
    // Get the course ID from the modal
    var courseId = $("#deleteModal").data("course-id");

    // Make an AJAX request to delete the course
    $.ajax({
      type: "POST",
      url: "partials/delete_course.php",
      data: {
        courseId: courseId,
      },
      success: function (response) {
        // Output the response to the console for debugging
        console.log(response);

        // Remove the table row for the deleted course
        $("#courseRow_" + courseId).remove();

        // Hide the modal
        $("#deleteModal").modal("hide");

        // Show spinner
        document.getElementById("overlay").style.display = "block";

        // Set delay and redirect to course list page
        setTimeout(function () {
          window.location.href = "course.php";
        }, 2000); // Adjust the delay as needed
      },
      error: function (error) {
        console.error("Error deleting course:", error);
      },
    });
  });
});

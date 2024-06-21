document.addEventListener("DOMContentLoaded", function () {
  // Handle the click event on the delete button
  $(".delete-student").on("click", function () {
    var studentNumber = $(this).data("student-number");

    // Set the student number in the modal for reference
    $("#deleteModal").data("student-number", studentNumber);
  });

  // Handle the confirm deletion button click
  $("#confirmDelete").on("click", function () {
    // Get the student number from the modal
    var studentNumber = $("#deleteModal").data("student-number");

    // Make an AJAX request to delete the student
    $.ajax({
      type: "POST",
      url: "partials/delete_student.php", 
      data: {
        studentNumber: studentNumber,
      },
      success: function (response) {
        // Output the response to the console for debugging
        console.log(response);

        // Remove the table row for the deleted student
        $("#studentRow_" + studentNumber).remove();
        // hime modal
        $("#deleteModal").modal("hide");
        // show spinner
        document.getElementById("overlay").style.display = "block";
        // set delay
        setTimeout(function () {
          window.location.href = "students.php";
        }, 2000); // 2 seconds delay

        // Checking if there are no more students left
        if ($(".delete-student").length === 0) {
          // hime modal
          $("#deleteModal").modal("hide");
          //   show spinnner
          document.getElementById("overlay").style.display = "block";
          //   set delay
          setTimeout(function () {
            window.location.href = "students.php";
          }, 2000); // 2.5 seconds delay
        }
      },
      error: function (error) {
        console.error("Error deleting student:", error);
      },
    });
  });
});


document.addEventListener("DOMContentLoaded", function () {
// Handle the click event on the edit button
$(".edit-student").on("click", function () {
    var id = $(this).data("id");
  
    // Store the id in the modal for reference
    $("#editModal").data("id", id);
  
    // Pre-fill the form inputs with the current details
    $("#firstName").val($(this).data("first-name"));
    $("#lastName").val($(this).data("last-name"));
    $("#studentNumber").val($(this).data("student-number"));
  });
  
  // Handle the confirm edit button click
  $("#confirmEdit").on("click", function () {
    // Get the id from the modal
    var id = $("#editModal").data("id");
  
    // Get the new details from the form inputs
    var studentNumber = $("#studentNumber").val();
    var firstName = $("#firstName").val();
    var lastName = $("#lastName").val();

    // Make an AJAX request to edit the student
    $.ajax({
      type: "POST",
      url: "partials/edit_student.php",
      data: {
        id: id,
        studentNumber: studentNumber,
        firstName: firstName,
        lastName: lastName,
      },
      success: function (response) {
        // Output the response to the console for debugging
        console.log(response);

        // Hide modal
        $("#editModal").modal("hide");
        // Show spinner
        document.getElementById("overlay").style.display = "block";
        // Set delay
        setTimeout(function () {
          window.location.href = "students.php";
        }, 2000); // 2 seconds delay
      },
      error: function (error) {
        console.error("Error editing student:", error);
      },
    });
  });
});

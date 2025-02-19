// filter job script
function loadJobs() {
    let job_type = $('#job_type').val();
    let job_title = $('#searchInput').val();
    let location = $('#location').val();
    let salary = $('#salary').val();

    $.ajax({
        url: "profilefetch.php",
        type: "GET",
        data: {
            job_type,
            job_title,
            location,
            salary_range:salary
        },
        success: function(response) {
            $('#job_results').html(response);
        }
    });
}

$('#filterBtn').click(function() {
    loadJobs();
});

$('#resetBtn').click(function() {
    $('#job_type').val('');
    $('#searchInput').val('');
    $('#location').val('');
    $('#salary').val('');
    loadJobs();
});

$(document).ready(function() {
    loadJobs();
});




//---------------------------------------//
//Apply form js
// Apply form submission logic
$(document).ready(function() {
    $('#applyForm').submit(function(event) {
        event.preventDefault();  // Prevent form from submitting normally
        
        var formData = new FormData(this);  // Collect form data
        
        $.ajax({
            url: 'apply_job.php',  // PHP file to handle form submission
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var data = JSON.parse(response);
                
                // Show the success or error message in the modal
                if (data.status === 'success') {
                    $('#responseModal .modal-body').html('<p>' + data.message + '</p>');
                } else {
                    $('#responseModal .modal-body').html('<p>' + data.message + '</p>');
                }

                // Show the response modal
                $('#responseModal').modal('show');
                
                // Close the apply modal
                $('#applyModal').modal('hide');
                
                // Reset the apply form after submission
                $('#applyForm')[0].reset();

                // Optionally, hide the response modal after a delay (e.g., 3 seconds)
                setTimeout(function() {
                    $('#responseModal').modal('hide');
                }, 3000);  // Modal will close after 3 seconds
            },
            error: function() {
                // Handle AJAX error
                $('#responseModal .modal-body').html('<p>An error occurred while submitting your application.</p>');
                $('#responseModal').modal('show');
                
                // Optionally, hide the response modal after a delay (e.g., 3 seconds)
                setTimeout(function() {
                    $('#responseModal').modal('hide');
                }, 3000);  // Modal will close after 3 seconds
            }
        });
    });
});
//---------------------------------//




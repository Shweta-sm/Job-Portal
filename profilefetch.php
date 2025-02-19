<?php
include('config.php');

$job_type = isset($_GET['job_type']) ? $_GET['job_type'] : '';
$job_title = isset($_GET['job_title']) ? $_GET['job_title'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$salary_range = isset($_GET['salary']) ? $_GET['salary'] : '';

$query = "SELECT * FROM jobsss WHERE 1";

if ($job_type != '') {
    $query .= " AND job_type = '$job_type'";
}
if ($job_title != '') {
    $query .= " AND job_title LIKE '%$job_title%'";
}
if ($location != '') {
    $query .= " AND location LIKE '%$location%'";
}
if ($salary_range != '') {
    $salary_range = explode('-', $salary_range);
    $min_salary = $salary_range[0];
    $max_salary = $salary_range[1];
    $query .= " AND salary BETWEEN $min_salary AND $max_salary";
}

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "<div class='job-container'>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "
        <div class='job-card'>
            <div class='logo'>
                <img src='" . $row['company_logo'] . "' alt='Company Logo'>
            </div>
            <div class='job-details'>
                <h3>" . $row['job_title'] . "</h3>
                <p><strong>Company:</strong> " . $row['company_name'] . "</p>
                <p><strong>Location:</strong> " . $row['location'] . "</p>
                <p><strong>Salary:</strong> " . $row['salary_range'] . "</p>
                <p><strong>Job Type:</strong> " . $row['job_type'] . "</p>
                <button class='view-btn' onclick='viewJobDescription(" . $row['id'] . ")'>View</button>
                <button class='apply-btn' onclick='openModal(" . $row['id'] . ")'>Apply</button>
            </div>
        </div>";
    }
    echo "</div>";
} else {
    echo "<p>No jobs found.</p>";
}

mysqli_close($conn);
?>

<!-- Styles for the Card Layout -->
<style>
    .job-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .job-card {
        display: flex;
        align-items: center;
        width: 400px;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        background: #fff;
        transition: transform 0.2s ease-in-out;
    }

    .job-card:hover {
        transform: scale(1.02);
    }

    .logo {
        flex: 1;
        padding-right: 15px;
    }

    .logo img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }

    .job-details {
        flex: 3;
    }

    .view-btn,
    .apply-btn {
        margin-top: 10px;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .view-btn {
        background-color: #007bff;
        color: white;
    }

    .apply-btn {
        background-color: #28a745;
        color: white;
    }

    .modal-logo {
        width: 80px;
        /* Small size */
        height: 80px;
        object-fit: contain;
        /* Ensure the logo fits well */
        border-radius: 5px;
        display: block;
        margin: 0 auto 10px;
        /* Centering with margin */
    }
</style>

<script>
    function openModal(jobId) {
        document.getElementById('job_id').value = jobId;
        document.getElementById('applyModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('applyModal').style.display = 'none';
    }

    function validateFileSize(input) {
        if (input.files[0].size > 5 * 1024 * 1024) {
            alert("File size must be less than 5MB.");
            input.value = "";
        }
    }

    //---------------------------------------------------------------------

    function viewJobDescription(jobId) {
        $.ajax({
            url: "get_job_details.php",
            type: "POST",
            data: {
                job_id: jobId
            },
            dataType: "json",
            success: function(response) {
                // Ensure only one container is present
                document.getElementById('job-description-text').innerHTML = `
                <img src="${response.company_logo}" alt="Company Logo" class="modal-logo">
                <h3>${response.job_title}</h3>
                <p><strong>Company:</strong> ${response.company_name}</p>
                <p><strong>Location:</strong> ${response.location}</p>
                <p><strong>Salary:</strong> ${response.salary_range}</p>
                <p><strong>Job Type:</strong> ${response.job_type}</p>
                <p><strong>Application Deadline:</strong> ${response.application_deadline}</p>
                <p><strong>Description:</strong></p>
                <p>${response.job_description}</p>
                <button class="apply-btn" onclick="openModal(${response.id})">Apply Now</button>
            `;

                // Show the modal
                document.getElementById('jobDescriptionModal').style.display = 'block';
            },
            error: function() {
                alert("Failed to load job details.");
            }
        });
    }

    function closeJobDescriptionModal() {
        document.getElementById('jobDescriptionModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('applyModal')) {
            closeModal();
        } else if (event.target == document.getElementById('jobDescriptionModal')) {
            closeJobDescriptionModal();
        }
    }
</script>
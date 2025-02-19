<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "job_portal";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filter values from the GET request
$job_type = isset($_GET['job_type']) ? $_GET['job_type'] : '';
$company_name = isset($_GET['company_name']) ? $_GET['company_name'] : '';
$job_title = isset($_GET['job_title']) ? $_GET['job_title'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$salary_range = isset($_GET['salary_range']) ? $_GET['salary_range'] : '';

// Start building the query
$query = "SELECT * FROM jobsss WHERE 1";

// Add filters dynamically
if ($job_type != '') {
    $query .= " AND job_type = '$job_type'";
}
if ($job_title != '') {
    $query .= " AND job_title LIKE '%$job_title%'"; // Searching job title
}
if ($company_name != '') {
    $query .= " AND company_name LIKE '%$company_name%'"; // Searching company name
}
if ($location != '') {
    $query .= " AND location LIKE '%$location%'"; // Searching location
}
if ($salary_range != '') {
    $salary_range = explode('-', $salary_range);
    $min_salary = $salary_range[0];
    $max_salary = $salary_range[1];
    $query .= " AND salary BETWEEN $min_salary AND $max_salary"; // Searching salary range
}

// Execute the query
$result = mysqli_query($conn, $query);

// Check if any jobs are found
if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<thead><tr><th>Job Title</th><th>Company Name</th><th>Job Type</th><th>Location</th><th>Salary</th><th>Action</th></tr></thead><tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>" . $row['job_title'] . "</td>
            <td>" . $row['company_name'] . "</td>
            <td>" . $row['job_type'] . "</td>
            <td>" . $row['location'] . "</td>
            <td>" . $row['salary_range'] . "</td>
            <td>
                <button class='view-btn' onclick='viewJob(" . $row['id'] . ")'>View</button>
                <button class='apply-btn' onclick='applyJob(" . $row['id'] . ")'>Apply</button>
            </td>
        </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "No jobs found.";
}

mysqli_close($conn);
?>

<script>
    function applyJob(jobId) {
        // Pass the job ID into the modal (you can also add other job info here if needed)
        document.getElementById('job_id').value = jobId; // Assuming you have an input field with id='job_id'
        document.getElementById('applyModal').style.display = 'block'; // Show the modal
    }

    function viewJob(jobId) {
        // You can either show a modal with job details or redirect to a new page
        window.location.href = "view_job.php?id=" + jobId; // Redirect to a page where job details will be shown
    }
</script>
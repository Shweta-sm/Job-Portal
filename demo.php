<?php
include('config.php'); // Include database connection
session_start();

// if (!isset($_SESSION['employer_logged_in']) || $_SESSION['employer_logged_in'] !== true) {
//     header("Location: employerlogin.php");
//     exit();
// }

// Fetch jobs with applicant count
$sql = "SELECT j.id, j.job_title, j.company_name, 
               COUNT(ja.id) AS num_applicants 
        FROM jobsss j
        LEFT JOIN job_applications ja ON j.id = ja.job_id 
        GROUP BY j.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="container">
        <h2>Employer Dashboard</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Company Name</th>
                    <th>Applicants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="job-table">
                <?php while ($job = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($job['company_name']); ?></td>
                        <td>
                            <a href="jobApplication.php?job_id=<?php echo $job['id']; ?>">
                                <?php echo $job['num_applicants']; ?>
                            </a>
                        </td>
                        <td>
                            <button class="btn btn-custom view-details"
                                data-job-id="<?php echo $job['id']; ?>">View Details</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="jobSeekerModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Job Seeker Details</h3>
            <div id="jobSeekerInfo"></div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".view-details").click(function() {
                var jobId = $(this).data("job-id");

                $.ajax({
                    url: "fetch_job_seekers.php",
                    type: "POST",
                    data: {
                        job_id: jobId
                    },
                    success: function(response) {
                        $("#jobSeekerInfo").html(response);
                        $("#jobSeekerModal").show();
                    }
                });
            });

            $(".close").click(function() {
                $("#jobSeekerModal").hide();
            });

            $(window).click(function(event) {
                if (event.target.id === "jobSeekerModal") {
                    $("#jobSeekerModal").hide();
                }
            });
        });
    </script>

    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        .close {
            color: red;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>

</body>

</html>
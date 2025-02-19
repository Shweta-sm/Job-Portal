<?php
session_start();
include('config.php');
// Check if session variable is set
if (!isset($_SESSION['user_email'])) {
    $_SESSION['user_email'] = '';  // Provide a default empty value to avoid warnings
}

// Get job postings with application count
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
            font-size: 1.4rem;
        }


        h2,
        h3 {
            font-size: 1.8rem;
            /* Increased heading font size */
        }

        .search-input,
        .form-control {
            font-size: 1.4rem;
            /* Increased input field font size */
        }

        .table th,
        .table td {
            font-size: 1.4rem;
            /* Increased table text size */
        }

        .modal-header {
            font-size: 1.7rem;
            /* Increased modal header font size */
        }

        .modal-dialog {
            max-width: 90%;
        }

        /* Card hover effect */
        .card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            transform: translateY(-7px);
            transition: 0.3s;
        }

        .navbar {
            margin-bottom: 40px;
            font-size: 1.3rem;
        }


        .navbar {
            font-size: 1.5rem;
            /* Increased font size for navbar */
            padding: 15px 20px;
            /* Increased padding for more height */
        }

        .navbar-brand {
            font-size: 1.8rem;
            /* Increased size for brand text */
        }

        .navbar-nav .nav-link {
            font-size: 1.5rem;
            /* Increased size for navigation links */
        }

        .navbar-toggler {
            font-size: 1.5rem;
            /* Increased size for the toggle button */
        }


        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 25px;
            font-size: 1.2rem;
            padding: 12px 20px;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .modal-header {
            background-color: #007bff;
            color: white;
            font-size: 1.5rem;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.7);
        }

        .search-input {
            margin-bottom: 25px;
            max-width: 500px;
            font-size: 1.2rem;
            padding: 12px;
        }

        .job-table th,
        .job-table td {
            vertical-align: middle;
            font-size: 1.3rem;
            padding: 15px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="employerOperation.php">Employer Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="jobApplication.php">Job Applications</a></li>
                <!-- <li class="nav-item"><a class="nav-link" href="employer_dashboard.php">employer dashboard</a></li> -->
                <li class="nav-item"><a class="nav-link" href="#" data-toggle="modal" data-target="#postJobModal">Post Job</a></li>
                <li class="nav-item"><a class="nav-link" href="#" data-toggle="modal" data-target="#profileModal">Profile</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4">Job Postings</h2>
        <input type="text" class="form-control search-input" id="searchInput" placeholder="Search for jobs..." onkeyup="filterJobs()">
        <button class="btn btn-custom mt-3" data-toggle="modal" data-target="#postJobModal">Post New Job</button>

        <table class="table table-striped mt-4 job-table">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Company Name</th>
                    <th>Applicants</th>
                </tr>
            </thead>
            <tbody id="job-table">
                <?php while ($job = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $job['job_title']; ?></td>
                        <td><?php echo $job['company_name']; ?></td>
                        <td>
                            <a href="jobApplication.php?job_id=<?php echo $job['id']; ?>">
                                <?php echo $job['num_applicants']; ?>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>


    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Employer Profile</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
                    <p><strong></strong> Employer</p>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Post Job Modal -->
    <div class="modal fade " id="postJobModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Post New Job</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="post_job.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="jobTitle">Job Title</label>
                            <input type="text" class="form-control" id="jobTitle" name="jobTitle" maxlength="100" required>
                        </div>
                        <div class="form-group">
                            <label for="companyName">Company Name</label>
                            <input type="text" class="form-control" id="companyName" name="companyName" required>
                        </div>

                        <div class="form-group">
                            <label for="jobDescription">Job Description</label>
                            <textarea class="form-control" id="jobDescription" name="jobDescription" rows="5"></textarea>
                        </div>



                        <div class="form-group">
                            <label for="jobType">Job Type</label>
                            <select class="form-control" id="jobType" name="jobType" required>
                                <option value="full_time">Full Time</option>
                                <option value="part_time">Part Time</option>
                                <option value="contract">Contract</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" class="form-control" id="location" name="location" required>
                        </div>
                        <div class="form-group">
                            <label for="salaryRange">Salary Range</label>
                            <input type="number" class="form-control" id="salaryRange" name="salaryRange" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="applicationDeadline">Application Deadline</label>
                            <input type="date" class="form-control" id="applicationDeadline" name="applicationDeadline" required>
                        </div>
                        <div class="form-group">
                            <label for="companyLogo">Company Logo</label>
                            <input type="file" class="form-control" id="companyLogo" name="companyLogo" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="postJob">Post Job</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        ClassicEditor.create(document.querySelector('#jobDescription')).catch(error => {
            console.error(error);
        });

        function filterJobs() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let jobRows = document.getElementById('job-table').getElementsByTagName('tr');
            Array.from(jobRows).forEach(function(row) {
                let title = row.getElementsByTagName('td')[0].textContent.toLowerCase();
                if (title.indexOf(input) > -1) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }


        document.querySelector('form').addEventListener('submit', function(e) {
            // Get the content from CKEditor
            var editorData = CKEDITOR.instances.jobDescription.getData();

            // Strip out all HTML tags (including <p> tags)
            var plainTextData = editorData.replace(/<\/?[^>]+(>|$)/g, "");

            // Set the cleaned plain text back into the textarea
            document.querySelector('#jobDescription').value = plainTextData;
        });
    </script>
</body>

</html>
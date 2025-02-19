<?php


session_start();
include('config.php');
// Check if session variable is set
if (!isset($_SESSION['user_email'])) {
    $_SESSION['user_email'] = '';  // Provide a default empty value to avoid warnings
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Add Bootstrap CDN for Navbar -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="profile.css">
</head>

<body>

    <!-- Bootstrap Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="profile.php">JobSeeker Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="#">Job Applications</a></li>
                <li class="nav-item"><a class="nav-link" href="#">dashboard</a></li>

                <li class="nav-item"><a class="nav-link" href="#" data-toggle="modal" data-target="#profileModal">Profile</a></li>
            </ul>
        </div>
    </nav>


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
                    <p><strong></strong> Job Seeker</p>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Main Content -->
    <div class="container">


        <h2>Filter Jobs</h2>
        <div class="filter-container">
            <div class="filter-fields">
                <input type="text" id="searchInput" placeholder="Search by Job Title">
                <select id="job_type">
                    <option value="">Select Job Type</option>
                    <option value="full_time">Full Time</option>
                    <option value="part_time">Part Time</option>
                    <option value="contract">Contract</option>
                </select>
                <input type="text" id="location" placeholder="Enter Location">
                <select id="salary">
                    <option value="">Select Salary Range</option>
                    <option value="2-5">2-5 LPA</option>
                    <option value="5-10">5-10 LPA</option>
                    <option value="10-15">10-15 LPA</option>
                </select>
            </div>

            <!-- Buttons centered below the fields -->
            <div class="button-container">
                <button id="filterBtn">Filter</button>
                <button id="resetBtn">Reset</button>
            </div>
        </div>

        <div id="job_results">


        </div>
    </div>


    <!-- Job Description Modal -->
    <div id="jobDescriptionModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeJobDescriptionModal()">&times;</span>
            <h2>Job Description</h2>
            <p id="job-description-text"></p>
        </div>
    </div>




    <!-- Apply Now Modal -->
    <div id="applyModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Apply for Job</h2>
            <form id="applyForm" action="apply_job.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="job_id" name="job_id">
                <label>Full Name:</label>
                <input type="text" name="full_name" required>

                <label>Email:</label>
                <input type="email" name="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">

                <label>Resume (Max 5MB):</label>
                <input type="file" name="resume" accept=".pdf,.doc,.docx" required onchange="validateFileSize(this)">

                <label>Cover Letter (Optional):</label>
                <textarea name="cover_letter"></textarea>

                <label>LinkedIn Profile (Optional):</label>
                <input type="url" name="linkedin_profile">

                <button type="submit">Submit Application</button>
            </form>
        </div>
    </div>



    <!-- Response Modal for Success/Error Message -->
    <div id="responseModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Application Status</h2>
            <div class="modal-body">
                <!-- Dynamic message will be inserted here -->
            </div>
        </div>
    </div>



    <script src="profile.js"></script>

</body>

</html>
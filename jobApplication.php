<?php
include('config.php');
session_start();
$_SESSION['user_email'] = '';


// Fetch all job applications
$query = "SELECT ja.id, ja.full_name, ja.email, ja.linkedin_profile, ja.resume_path, ja.status, 
                 j.job_title, j.company_name
          FROM job_applications ja
          JOIN jobsss j ON ja.job_id = j.id
          ORDER BY ja.id ASC";

$result = $conn->query($query);

if (!$result) {
    die("Database query failed: " . $conn->error);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applications Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


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

        select {
            font-weight: bold;
            /* Makes the text bold */
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="employerOperation.php">Back</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <!-- <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li> -->
                <li class="nav-item active"><a class="nav-link" href="jobApplication.php">Job Applications</a></li>
                <!-- <li class="nav-item"><a class="nav-link" href="#" data-toggle="modal" data-target="#profileModal">Profile</a></li> -->
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
                    <p><strong></strong> Employer</p>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>


    <div class="container mt-4">
        <h3>Job Applications</h3>
        <table class="table table-bordered table-hover mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Job Role</th>
                    <th>LinkedIn</th>
                    <th>Resume</th>
                    <th>Status</th>

                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($row['linkedin_profile']); ?>" target="_blank">Profile</a></td>
                        <td><a href="<?php echo htmlspecialchars($row['resume_path']); ?>" target="_blank">Download</a></td>
                        <td>

                            <form method="POST" action="update_status.php">
                                <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
                                <select name="status" class="form-control" required>
                                    <option value="Received" <?php if ($row['status'] == 'Received') echo 'selected'; ?>>Received</option>
                                    <option value="Reviewed" <?php if ($row['status'] == 'Reviewed') echo 'selected'; ?>>Reviewed</option>
                                    <option value="Interview Scheduled" <?php if ($row['status'] == 'Interview Scheduled') echo 'selected'; ?>>Interview Scheduled</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary mt-2">Update</button>
                            </form>


                        </td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<?php
include('config.php');
session_start();

// Fetch all job details
$query = "SELECT ja.id, ja.full_name, ja.email, ja.linkedin_profile, ja.resume_path, ja.status, 
                 j.job_title, j.company_name ,j.job_description
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
    <title>Job Listings</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f4f7fc;
            font-family: 'Arial', sans-serif;
        }

        .vertical-navbar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #2c3e50;
            padding-top: 30px;
            box-shadow: 2px 0px 8px rgba(0, 0, 0, 0.1);
        }

        .vertical-navbar a {
            color: #fff;
            padding: 15px 25px;
            text-decoration: none;
            font-size: 1.2rem;
            display: block;
            transition: background-color 0.3s ease;
        }

        .vertical-navbar a:hover {
            background-color: #0072ff;
        }

        .content-area {
            margin-left: 250px;
            padding-top: 50px;
            padding-left: 30px;
            padding-right: 30px;
            flex-grow: 1;
        }

        .hero-section {
            background: #0072ff;
            color: white;
            padding: 50px 0;
            text-align: center;
            margin-bottom: 30px;
        }

        .hero-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .search-bar input {
            padding: 10px;
            font-size: 1rem;
            width: 300px;
            margin-right: 10px;
            border-radius: 5px;
            border: none;
        }

        .search-bar button {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #0056b3;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #003f8b;
        }

        .job-card {
            border-radius: 12px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease;
        }

        .job-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .job-card h4 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .job-card p {
            color: #f1f1f1;
            font-size: 1rem;
            line-height: 1.6;
        }

        .btn-primary {
            background-color: #0072ff;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #ff5e57;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #d2493b;
        }

        .container {
            margin-top: 50px;
        }

        .job-card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .job-card-container .col-md-4 {
            margin-bottom: 30px;
            padding-left: 15px;
            padding-right: 15px;
        }

        /* Responsive styles */
        @media (max-width: 767px) {
            .vertical-navbar {
                width: 100%;
                height: auto;
                position: relative;
                padding-top: 20px;
                display: flex;
                justify-content: space-between;
            }

            .vertical-navbar a {
                font-size: 1rem;
                text-align: center;
                padding: 10px;
            }

            .content-area {
                margin-left: 0;
                padding: 20px;
            }

            .job-card-container {
                flex-direction: column;
                align-items: center;
            }

            .job-card-container .col-md-4 {
                max-width: 100%;
                width: 90%;
                padding-left: 0;
                padding-right: 0;
            }

            .hero-section h1 {
                font-size: 2rem;
            }

            .hero-section p {
                font-size: 1rem;
            }

            .search-bar input {
                width: 100%;
                margin-bottom: 10px;
            }

            .search-bar button {
                width: 100%;
            }
        }
    </style>

</head>

<body>
    <!-- Vertical Navbar -->
    <div class="vertical-navbar">
        <a href="index.php">Job Portal</a>
        <!-- Login Button that triggers the modal -->
        <a href="#" data-toggle="modal" data-target="#loginModal">Login</a>
        <!-- Register Button that triggers the modal -->
        <a href="#" data-toggle="modal" data-target="#registerModal">Register</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </div>

    <!-- Right Content Area (Job Listings) -->
    <div class="content-area">
        <!-- Hero Section -->
        <div class="hero-section">
            <h1>Welcome to Job Portal</h1>
            <p>Find your dream job or hire the best talent!</p>
            <div class="search-bar text-center">
                <input type="text" placeholder="Search for jobs...">
                <button>Search</button>
            </div>
        </div>


        <!-- Right Content Area (Job Listings) -->
        <div class="job-card-container">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="col-md-4">
                    <div class="job-card">
                        <h4><?php echo htmlspecialchars($row['company_name']); ?> - <?php echo htmlspecialchars($row['job_title']); ?></h4>
                        <a href="#" data-toggle="modal" data-target="#loginModal" class="btn btn-primary">Login to Apply</a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Login Modal -->
        <div class="modal fade" id="loginModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Login</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="login.php">
                            <div class="form-group position-relative">
                                <label>Email</label>
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group position-relative">
                                <label>Password</label>
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="login">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Register Modal -->
        <div class="modal fade" id="registerModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Register</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="register.php">
                            <div class="form-group position-relative">
                                <label>First Name</label>
                                <i class="fas fa-user"></i>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="form-group position-relative">
                                <label>Last Name</label>
                                <i class="fas fa-user"></i>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="form-group position-relative">
                                <label>Email</label>
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group position-relative">
                                <label>Password</label>
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>User Type</label>
                                <select class="form-control" id="userType" name="user_type" required>
                                    <option value="Select">Select</option>
                                    <option value="job_seeker">Job Seeker</option>
                                    <option value="employer">Employer</option>
                                </select>
                            </div>
                            <div id="additionalFields"></div>

                            <button type="submit" class="btn btn-primary" name="register">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#userType').change(function() {
                    let userType = $(this).val();
                    let additionalFields = '';

                    if (userType === 'job_seeker') {
                        additionalFields = '<div class="form-group">' +
                            '<label>Experience</label>' +
                            '<select class="form-control" id="experience" name="experience">' +
                            '<option value="fresher">Fresher</option>' +
                            '<option value="experienced">Experienced</option>' +
                            '</select>' +
                            '</div>' +
                            '<div id="expDetails"></div>';
                    } else {
                        additionalFields = '<div class="form-group">' +
                            '<label>Position</label><input type="text" class="form-control" name="position" required>' +
                            '<label>Company Name</label><input type="text" name="company_name" class="form-control" required>' +
                            '<label>Experience (in years)</label><input type="number" name="experience" class="form-control" required>' +
                            '</div>';
                    }
                    $('#additionalFields').html(additionalFields);
                });

                $(document).on('change', '#experience', function() {
                    if ($(this).val() === 'experienced') {
                        $('#expDetails').html('<label>Company Name</label><input type="text" name="company_name" class="form-control" required>' +
                            '<label>Position</label><input type="text" name="position" class="form-control" required>' +
                            '<label>Years of Experience</label><input type="text" name="years_of_experience" class="form-control" required>');
                    } else {
                        $('#expDetails').empty();
                    }
                });
            });
        </script>

</body>

</html>
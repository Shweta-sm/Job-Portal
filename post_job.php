<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['postJob'])) {
    // Collect form data
    $jobTitle = trim($_POST['jobTitle']);
    $companyName = trim($_POST['companyName']);
    $jobDescription = trim($_POST['jobDescription']);
    $jobType = trim($_POST['jobType']);
    $location = trim($_POST['location']);
    $salaryRange = trim($_POST['salaryRange']);
    $applicationDeadline = trim($_POST['applicationDeadline']);

    // Check for empty fields
    if (empty($jobTitle) || empty($companyName) || empty($jobDescription) || empty($jobType) || empty($location) || empty($salaryRange) || empty($applicationDeadline)) {
        die("All fields are required.");
    }

    // Handling file upload
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true); // Create the uploads directory if it doesn't exist
    }

    $logoFile = $_FILES['companyLogo']['name'];
    $targetFilePath = $targetDir . basename($logoFile);
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Validate file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($fileType, $allowedTypes)) {
        die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
    }

    // Validate file size (max 5MB)
    if ($_FILES['companyLogo']['size'] > 5000000) {
        die("Sorry, your file is too large.");
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES['companyLogo']['tmp_name'], $targetFilePath)) {

        // Insert into database using prepared statement
        $stmt = $conn->prepare("INSERT INTO jobsss (job_title, company_name, job_description, job_type, location, salary_range, application_deadline, company_logo) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssdss", $jobTitle, $companyName, $jobDescription, $jobType, $location, $salaryRange, $applicationDeadline, $targetFilePath);

        if ($stmt->execute()) {
            // Use JavaScript to show the alert and redirect the user
            echo '<script type="text/javascript">',
            'alert("Job posted successfully!");',
            'window.location.href = "employerOperation.php";', // Redirect to employerOperation.php
            '</script>';
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        die("Error uploading the file.");
    }
} else {
    echo "Form not submitted.";
}

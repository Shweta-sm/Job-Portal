<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_id = $_POST['job_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $resume = $_FILES['resume'];
    $cover_letter = $_POST['cover_letter'];
    $linkedin_profile = isset($_POST['linkedin_profile']) ? $_POST['linkedin_profile'] : '';

    // Check if the job ID already exists
    $check_query = "SELECT * FROM JA WHERE id = '$job_id'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(["status" => "error", "message" => "This application has already been submitted for this job."]);
    } else {
        // Process Resume Upload
        $resume_file = 'uploads/' . basename($resume['name']);
        if (move_uploaded_file($resume['tmp_name'], $resume_file)) {
            // Insert data
            $query = "INSERT INTO JA (id, full_name, email, resume, cover_letter, linkedin_profile) 
                      VALUES ('$job_id', '$full_name', '$email', '$resume_file', '$cover_letter', '$linkedin_profile')";

            if (mysqli_query($conn, $query)) {
                echo json_encode(["status" => "success", "message" => "Your application has been submitted successfully!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error: " . mysqli_error($conn)]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Error uploading resume."]);
        }
    }
}

<?php
session_start();  // Start the session to manage login state

// Include database connection
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password
    $user_type = $_POST['user_type'];
    $experience = $_POST['experience'];  // Default to fresher if not provided
    $company_name = $_POST['company_name'] ?? null;
    $position = $_POST['position'] ?? null;
    $years_of_experience = $_POST['years_of_experience'] ?? null;

    // Insert user into the database
    $sql = "INSERT INTO jobdemo (first_name, last_name, email, password, user_type, experience, company_name, position, years_of_experience) 
            VALUES ('$first_name', '$last_name', '$email', '$password', '$user_type', '$experience', '$company_name', '$position', '$years_of_experience')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['user_id'] = $conn->insert_id;  // Store user ID in session
        $_SESSION['user_email'] = $email;  // Store email in session
        header("Location: index.html");  // Redirect to the dashboard after successful registration
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

<?php
session_start();  // Start the session to manage login state

// Include database connection
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists
    $sql = "SELECT * FROM jobdemo WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];  // Store user ID in session
            $_SESSION['user_email'] = $row['email'];  // Store email in session
            $_SESSION['user_type'] = $row['user_type'];  // Store user type in session
            $_SESSION['employer_Position'] = $row['position'];
            $_SESSION['employer_company'] = $row['company_name'];

            // Redirect based on user type
            if ($row['user_type'] == 'job_seeker') {
                header("Location:profile.php");  // Redirect to job seeker page
            } elseif ($row['user_type'] == 'employer') {
                header("Location: employerOperation.php");  // Redirect to employer page
            }
            exit();
        } else {
            echo "<script>alert('Invalid Password.'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('User  not found.'); window.location.href='index.php';</script>";
    }
}

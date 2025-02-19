<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load PHPMailer via Composer
include('config.php'); // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $application_id = $_POST['application_id'];
    $new_status = $_POST['status'];

    // Fetch job application details (email, name, job title, company name)
    $query = "SELECT ja.email, ja.full_name, j.job_title, j.company_name 
              FROM job_applications ja
              JOIN jobsss j ON ja.job_id = j.id
              WHERE ja.id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $applicant = $result->fetch_assoc();

    if ($applicant) {
        $email = $applicant['email'];
        $name = $applicant['full_name'];
        $job_title = $applicant['job_title'];
        $company_name = $applicant['company_name'];

        // Update the status in the database
        $updateQuery = "UPDATE job_applications SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $new_status, $application_id);
        $stmt->execute();

        // Send email notification
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'shwetamandavkar19@gmail.com'; // Your email
            $mail->Password = 'hnjr gold bxjm ufwu'; // Your email password or App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Sender & recipient
            $mail->setFrom('jobportal@gmail.com', $company_name);
            $mail->addAddress($email, $name);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "Job Application Status Update - $job_title at $company_name";
            $mail->Body = "
                <p>Dear $name,</p>
                <p>Your job application for the position of <strong>$job_title</strong> at <strong>$company_name</strong> has been updated.</p>
                <p><strong>New Status:</strong> $new_status</p>
                <p>Thank you for applying!</p>
                <p>Best regards,<br>$company_name Hiring Team</p>
            ";

            $mail->send();
            echo "<script>alert('Status updated and email sent successfully!'); window.location.href='jobApplication.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Status updated but email sending failed.'); window.location.href='jobApplication.php';</script>";
        }
    } else {
        echo "<script>alert('Application not found.'); window.location.href='jobApplication.php';</script>";
    }
}

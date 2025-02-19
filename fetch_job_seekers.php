<?php
include('config.php');

if (isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];

    $sql = "SELECT full_name, email, linkedin_profile, resume_path 
            FROM job_applications 
            WHERE job_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><strong>Name:</strong> " . htmlspecialchars($row['full_name']) . "</li>";
            echo "<li><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</li>";
            echo "<li><strong>LinkedIn:</strong> <a href='" . htmlspecialchars($row['linkedin_profile']) . "' target='_blank'>Profile</a></li>";
            echo "<li><strong>Resume:</strong> <a href='" . htmlspecialchars($row['resume_path']) . "' target='_blank'>Download</a></li>";
            echo "<hr>";
        }
        echo "</ul>";
    } else {
        echo "<p>No applicants found for this job.</p>";
    }
}

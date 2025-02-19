<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobId = intval($_POST['job_id']);

    $sql = "SELECT * FROM jobsss WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $jobId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $job = $result->fetch_assoc();
        echo json_encode($job); // Send job details as JSON response
    } else {
        echo json_encode(['error' => 'Job not found']);
    }

    $stmt->close();
    $conn->close();
}

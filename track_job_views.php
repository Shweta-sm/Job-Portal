<?php
include('config.php');
session_start();

if (isset($_GET['job_id']) && isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'job_seeker') {
    $job_id = $_GET['id'];
    $user_id = $_SESSION['id'];

    $query = "SELECT * FROM job_views WHERE job_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $job_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $update = "UPDATE job_views SET views = views + 1 WHERE job_id = ? AND user_id = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("ii", $job_id, $user_id);
        $stmt->execute();
    } else {
        $insert = "INSERT INTO job_views (job_id, user_id, views) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("ii", $job_id, $user_id);
        $stmt->execute();
    }
}

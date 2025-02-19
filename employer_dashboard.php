<?php
include('config.php');
session_start();

if (!isset($_SESSION['id']) || $_SESSION['user_type'] !== 'employer') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['id'];

$query = "SELECT j.id, j.job_title, j.company_name, 
                 COALESCE(v.views, 0) AS total_views, 
                 (SELECT COUNT(*) FROM job_applications WHERE job_id = j.id) AS total_applications
          FROM jobsss j
          LEFT JOIN job_views v ON j.id = v.id
          WHERE j.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employer Dashboard</title>
</head>

<body>
    <h2>Job Posting Analytics</h2>
    <table border="1">
        <tr>
            <th>Job Title</th>
            <th>Company Name</th>
            <th>Total Views</th>
            <th>Total Applications</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['job_title']; ?></td>
                <td><?php echo $row['company_name']; ?></td>
                <td><?php echo $row['total_views']; ?></td>
                <td><?php echo $row['total_applications']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>
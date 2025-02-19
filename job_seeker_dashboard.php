<?php
include('config.php');
session_start();

// if (!isset($_SESSION['id']) || $_SESSION['user_type'] !== 'job_seeker') {
//     header("Location: login.php");
//     exit();
// }

$user_id = $_SESSION['id'];

$query = "SELECT ja.id, j.job_title, j.company_name, ja.status, ja.applied_at
          FROM job_applications ja
          JOIN jobsss j ON ja.job_id = j.id
          WHERE ja.id = ?
          ORDER BY ja.applied_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Application History</title>
</head>

<body>
    <h2>Your Job Applications</h2>
    <table border="1">
        <tr>
            <th>Job Title</th>
            <th>Company Name</th>
            <th>Status</th>
            <th>Applied Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['job_title']; ?></td>
                <td><?php echo $row['company_name']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['applied_at']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>
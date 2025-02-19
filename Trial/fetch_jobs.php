<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "job_portal";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$where = [];

if (!empty($_GET['job_type'])) {
    $where[] = "job_type = '" . $conn->real_escape_string($_GET['job_type']) . "'";
}
if (!empty($_GET['job_title'])) {
    $where[] = "job_title LIKE '%" . $conn->real_escape_string($_GET['job_title']) . "%'";
}
if (!empty($_GET['location'])) {
    $where[] = "location LIKE '%" . $conn->real_escape_string($_GET['location']) . "%'";
}
if (!empty($_GET['salary'])) {
    $salaryRange = explode("-", $_GET['salary']);
    if (count($salaryRange) == 2) {
        $where[] = "salary BETWEEN " . intval($salaryRange[0]) . " AND " . intval($salaryRange[1]);
    }
}

$sql = "SELECT * FROM search";
if (count($where) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$result = $conn->query($sql);

$output = '<table border="1"><tr><th>Job Title</th><th>Type</th><th>Location</th><th>Salary</th></tr>';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>
                        <td>{$row['job_title']}</td>
                        <td>{$row['job_type']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['salary']} LPA</td>
                    </tr>";
    }
} else {
    $output .= "<tr><td colspan='4'>No jobs found</td></tr>";
}
$output .= "</table>";

echo $output;

$conn->close();

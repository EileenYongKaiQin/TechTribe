<?php
include('../database/config.php');

// Decode JSON request
$request = json_decode(file_get_contents('php://input'), true);

// Get search parameters
$jobTitle = isset($request['jobTitle']) ? $request['jobTitle'] : '';
$location = isset($request['location']) ? $request['location'] : '';

// Build the query
$sql = "SELECT * FROM jobPost WHERE endDate >= CURDATE()";
if (!empty($jobTitle) || !empty($location)) {
    $sql .= " AND (";
    if (!empty($jobTitle)) {
        $sql .= "jobTitle LIKE '%" . $con->real_escape_string($jobTitle) . "%'";
    }
    if (!empty($jobTitle) && !empty($location)) {
        $sql .= " AND ";
    }
    if (!empty($location)) {
        $sql .= "(location LIKE '%" . $con->real_escape_string($location) . "%' OR venue LIKE '%" . $con->real_escape_string($location) . "%')";
    }
    $sql .= ")";
}

// Execute the query
$result = $con->query($sql);

// Collect job results
$jobs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

// Return the jobs as JSON
echo json_encode($jobs);

// Close the connection
$con->close();
?>

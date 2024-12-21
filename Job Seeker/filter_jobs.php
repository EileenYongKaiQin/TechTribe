<?php
include('../database/config.php');
header('Content-Type: application/json');

// Decode incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Initialize variables from the request
$minSalary = isset($data['minSalary']) ? $data['minSalary'] : null;
$maxSalary = isset($data['maxSalary']) ? $data['maxSalary'] : null;
$locations = isset($data['locations']) ? $data['locations'] : [];
$workingHours = isset($data['workingHours']) ? $data['workingHours'] : [];

// Base query
$query = "SELECT * FROM jobPost WHERE endDate >= CURDATE()";

// Conditions for filtering
$conditions = [];
$params = [];
$types = "";

// Add salary range filter
if (!empty($minSalary)) {
    $conditions[] = "salary >= ?";
    $params[] = $minSalary;
    $types .= "d"; // Decimal for salary
}

if (!empty($maxSalary)) {
    $conditions[] = "salary <= ?";
    $params[] = $maxSalary;
    $types .= "d";
}

// Add location filter
if (!empty($locations)) {
    $locationPlaceholders = implode(',', array_fill(0, count($locations), '?'));
    $conditions[] = "location IN ($locationPlaceholders)";
    foreach ($locations as $location) {
        $params[] = $location;
        $types .= "s"; // String for location
    }
}

// Add working hour filter
if (!empty($workingHours)) {
    $workingHourPlaceholders = implode(',', array_fill(0, count($workingHours), '?'));
    $conditions[] = "workingHour IN ($workingHourPlaceholders)";
    foreach ($workingHours as $hour) {
        $params[] = $hour;
        $types .= "s"; // String for working hour
    }
}

// Append conditions to query
if (!empty($conditions)) {
    $query .= " AND " . implode(" AND ", $conditions);
}

// Prepare the statement
$stmt = $con->prepare($query);

if ($stmt === false) {
    echo json_encode(['error' => 'Failed to prepare statement']);
    exit();
}

// Bind parameters dynamically
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch results
$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
}

// Close the statement and connection
$stmt->close();
$con->close();

// Return jobs as JSON
echo json_encode($jobs);
?>

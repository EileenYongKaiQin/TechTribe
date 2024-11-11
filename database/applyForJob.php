<?php
header('Content-Type: application/json');

// Database connection variables
$servername = "localhost";
$username = "root";
$password = "";
$database = "flexmatch_db";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit;
}

// Retrieve data from the POST request
$data = json_decode(file_get_contents("php://input"), true);
$status = $data['status'] ?? 'Pending';  // Default to 'Pending' if no status is provided

// Insert into the database
$sql = "INSERT INTO job_application_status (status) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $status);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Application submitted successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to submit application."]);
}

// Close the connection
$stmt->close();
$conn->close();
?>

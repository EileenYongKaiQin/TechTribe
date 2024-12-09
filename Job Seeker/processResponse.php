<?php
ob_start();
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set the response header to JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include database configuration
    include('../database/config.php');

    // Check database connection
    if (!$con) {
        error_log("Database connection failed: " . mysqli_connect_error());
        echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
        exit();
    }

    // Ensure data is received from the POST request
    if (isset($_POST['applicationID']) && isset($_POST['response'])) {
        $applicationID = filter_var($_POST['applicationID'], FILTER_SANITIZE_STRING);
        $response = htmlspecialchars($_POST['response'], ENT_QUOTES, 'UTF-8');

        if (empty($applicationID) || empty($response)) {
            echo json_encode(['success' => false, 'message' => 'Application ID or response is missing.']);
            exit();
        }

        error_log("Received applicationID: $applicationID");
        error_log("Received response: $response");

        // Prepare and execute the database update
        $sql = "UPDATE jobApplication SET applicantResponse = ? WHERE applicationID = ?";
        $stmt = $con->prepare($sql);

        if ($stmt) {
            // Use 's' for string for applicationID
            $stmt->bind_param('ss', $response, $applicationID);

            if ($stmt->execute()) {
                ob_end_clean();
                echo json_encode(['success' => true, 'message' => 'Response submitted successfully']);
            } else {
                error_log("SQL Error: " . $stmt->error);
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
            }

            $stmt->close();
        } else {
            error_log("Failed to prepare statement: " . $con->error);
            echo json_encode(['success' => false, 'message' => 'Failed to prepare statement.']);
        }

        $con->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request. Missing parameters.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>

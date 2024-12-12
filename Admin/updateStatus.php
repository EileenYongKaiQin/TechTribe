<?php
include('../database/config.php');

// Ensure POST request is sent
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get IDs and status from the FormData sent via POST
    $ids = isset($_POST['ids']) ? explode(',', $_POST['ids']) : [];
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    // Validate data
    if (!empty($ids) && !empty($status)) {
        $statusMapping = [
            'under-review' => 'Under Review',
            'resolved' => 'Resolved',
            'pending' => 'Pending'
        ];

        if (!array_key_exists($status, $statusMapping)) {
            echo "Error: Invalid status value.";
            exit;
        }
        $status = $statusMapping[$status];
        
        // Prepare placeholders for query
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "UPDATE reportPost SET reportStatus = ? WHERE reportID IN ($placeholders)";

        // Prepare the statement
        $stmt = $con->prepare($query);

        if ($stmt) {
            // Dynamically bind parameters
            $types = str_repeat('s', count($ids) + 1); // Adding 1 for the status parameter
            $bindParams = array_merge([$status], $ids);

            // Bind parameters dynamically
            $stmt->bind_param($types, ...$bindParams);

            // Execute the query
            if ($stmt->execute()) {
                // Return success response
                echo "success";
            } else {
                echo "Error: " . $stmt->error; // If execution fails, send error message
            }

            $stmt->close();
        } else {
            echo "Error: Failed to prepare the statement.";
        }
    } else {
        echo "Error: Missing or invalid data (IDs or status).";
    }
} else {
    echo "Error: Invalid request method. Use POST.";
}
?>


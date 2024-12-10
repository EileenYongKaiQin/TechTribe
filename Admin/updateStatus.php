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

        // Check if any of the selected reports already have the status "Resolved"
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $checkQuery = "SELECT COUNT(*) AS resolvedCount FROM reportPost WHERE reportID IN ($placeholders) AND reportStatus = 'Resolved'";
        $stmtCheck = $con->prepare($checkQuery);

        if ($stmtCheck) {
            // Bind parameters dynamically for the check query
            $typesCheck = str_repeat('s', count($ids));
            $stmtCheck->bind_param($typesCheck, ...$ids);

            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();
            $rowCheck = $resultCheck->fetch_assoc();

            if ($rowCheck['resolvedCount'] > 0) {
                echo "Error: One or more reports are already resolved and cannot be updated.";
                exit;
            }
        } else {
            echo "Error: Failed to prepare the resolved check query.";
            exit;
        }

        // Proceed with the update query
        $updateQuery = "UPDATE reportPost SET reportStatus = ? WHERE reportID IN ($placeholders)";
        $stmtUpdate = $con->prepare($updateQuery);

        if ($stmtUpdate) {
            // Dynamically bind parameters for the update query
            $typesUpdate = str_repeat('s', count($ids) + 1); // Adding 1 for the status parameter
            $bindParamsUpdate = array_merge([$status], $ids);

            $stmtUpdate->bind_param($typesUpdate, ...$bindParamsUpdate);

            // Execute the query
            if ($stmtUpdate->execute()) {
                // Return success response
                echo "success";
            } else {
                echo "Error: " . $stmtUpdate->error; // If execution fails, send error message
            }

            $stmtUpdate->close();
        } else {
            echo "Error: Failed to prepare the update statement.";
        }
    } else {
        echo "Error: Missing or invalid data (IDs or status).";
    }
} else {
    echo "Error: Invalid request method. Use POST.";
}
?>

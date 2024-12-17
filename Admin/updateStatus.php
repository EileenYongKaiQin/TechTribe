<?php
include('../database/config.php');
include('../notification/notification.php');
session_start();

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
                foreach($ids as $id) {
                    $notiText1 = "The status of $id has been updated to \"$status\".";
                    $notiText2 = "Your report status has been updated to \"$status\".";
                    $notiType = 'status-change';

                    $getReporterQuery = "SELECT reporterID FROM reportPost WHERE reportID = ?";
                    $stmtReporter = $con->prepare($getReporterQuery);
                    $stmtReporter->bind_param("s",$id);
                    $stmtReporter->execute();
                    $resultReporter = $stmtReporter->get_result();
                    $reporter = $resultReporter->fetch_assoc();
                    $reporterID = $reporter['reporterID'];
                    
                    $adminID = $_SESSION['userID'];
                    createNotification($reporterID, $notiText2, $notiType);
                    createNotification($adminID, $notiText1, $notiType);
                    
                }
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


<?php
session_start();
include '../database/config.php'; // Ensure this path is correct

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the search query and IDs are provided
if (isset($_GET['query']) && isset($_GET['jobSeekerID']) && isset($_SESSION['userID'])) {
    $query = '%' . $_GET['query'] . '%'; // Prepare for LIKE statement
    $jobSeekerID = $_GET['jobSeekerID'];
    $userID = $_SESSION['userID']; // Get userID from session
    $employerID = $userID;

    // Prepare and execute the query
    $sql = "SELECT id, senderRole, messageContents, timestamp FROM message WHERE (messageContents LIKE ?) AND (jobSeekerID = ? AND employerID = ?) ORDER BY timestamp DESC"; // Fetching recent messages first
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("sss", $query, $jobSeekerID, $userID);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $messages = [];
            while ($row = $result->fetch_assoc()) {
                $messages[] = [
                    'id' => $row['id'], // Include the message ID
                    'senderRole' => htmlspecialchars($row['senderRole']),
                    'messageContents' => htmlspecialchars($row['messageContents']),
                    'formatted_date' => date('d M Y h:i A', strtotime($row['timestamp']))
                ];
            }
            echo json_encode(['status' => 'success', 'messages' => $messages]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Query execution failed.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Statement preparation failed.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No search query or IDs provided']);
}
?>

<?php
include '../database/config.php'; // Ensure this path is correct

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the search query is provided
if (isset($_GET['query'])) {
    $query = '%' . $_GET['query'] . '%'; // Prepare for LIKE statement

    // Prepare and execute the query
    $sql = "SELECT * FROM message WHERE messageContents LIKE ? ORDER BY timestamp DESC"; // Fetching recent messages first
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("s", $query);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $messages = [];
            while ($row = $result->fetch_assoc()) {
                $messages[] = [
                    'senderRole' => htmlspecialchars($row['senderRole']),
                    'messageContents' => htmlspecialchars($row['messageContents']),
                    'formatted_date' => date('Y-m-d H:i:s', strtotime($row['timestamp'])) // Format the timestamp as needed
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
    echo json_encode(['status' => 'error', 'message' => 'No search query provided']);
}
?>

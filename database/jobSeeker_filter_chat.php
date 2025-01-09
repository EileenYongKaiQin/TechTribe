<?php
session_start();
include '../database/config.php';

if (!isset($_GET['date'], $_GET['employerID'], $_GET['userID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Required parameters missing']);
    exit;
}

$date = $_GET['date'];
$employerID = $_GET['employerID'];
$userID = $_SESSION['userID']; // Get userID from session
$jobSeekerID = $userID;

// Validate date format
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid date format']);
    exit;
}

// Query to fetch messages for the given date, userID, and jobSeekerID
$sql = "
    SELECT id, userID, senderRole, messageContents, DATE_FORMAT(timestamp, '%d %b %Y') AS formatted_date, DATE_FORMAT(timestamp, '%h:%i %p') AS formatted_time, timestamp
    FROM message
    WHERE DATE(timestamp) = ? 
      AND (
          (jobSeekerID = ? AND employerID = ?) 
          OR (employerID = ? AND jobSeekerID = ?)
      )
    ORDER BY timestamp ASC
";
$stmt = $con->prepare($sql);
$stmt->bind_param("sssss", $date, $userID, $employerID, $employerID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'id' => $row['id'],
            'userID' => $row['userID'],
            'senderRole' => $row['senderRole'],
            'messageContents' => $row['messageContents'],
            'formatted_date' => $row['formatted_date'],
            'formatted_time' => $row['formatted_time'], // Include formatted time
            'timestamp' => $row['timestamp']
        ];
    }
    echo json_encode(['status' => 'success', 'messages' => $messages]);
} else {
    // If no messages found, find the next available date with messages
    $nextDateQuery = "
        SELECT DATE(timestamp) AS nextDate
        FROM message
        WHERE DATE(timestamp) > ? AND ((userID = ? AND senderRole = 'employer' AND employerID = ?) OR (userID = ? AND senderRole = 'job_seeker' AND employerID = ?))
        ORDER BY DATE(timestamp) ASC
        LIMIT 1
    ";
    $nextDateStmt = $con->prepare($nextDateQuery);
    $nextDateStmt->bind_param("sssss", $date, $userID, $employerID, $employerID, $userID);
    $nextDateStmt->execute();
    $nextDateResult = $nextDateStmt->get_result();

    if ($nextDateResult->num_rows > 0) {
        $nextDateRow = $nextDateResult->fetch_assoc();
        $nextDate = $nextDateRow['nextDate'];

        // Fetch messages for the next available date
        $nextMessagesQuery = "
            SELECT id, userID, senderRole, messageContents, DATE_FORMAT(timestamp, '%d %b %Y') AS formatted_date, DATE_FORMAT(timestamp, '%h:%i %p') AS formatted_time, timestamp
            FROM message
            WHERE DATE(timestamp) = ? 
              AND (
                  (jobSeekerID = ? AND employerID = ?) 
                  OR (employerID = ? AND jobSeekerID = ?)
              )
            ORDER BY timestamp ASC
        ";
        $nextMessagesStmt = $con->prepare($nextMessagesQuery);
        $nextMessagesStmt->bind_param("sssss", $nextDate, $userID, $employerID, $employerID, $userID);
        $nextMessagesStmt->execute();
        $nextMessagesResult = $nextMessagesStmt->get_result();

        $nextMessages = [];
        while ($nextRow = $nextMessagesResult->fetch_assoc()) {
            $nextMessages[] = [
                'id' => $nextRow['id'],
                'userID' => $nextRow['userID'],
                'senderRole' => $nextRow['senderRole'],
                'messageContents' => $nextRow['messageContents'],
                'formatted_date' => $nextRow['formatted_date'],
                'formatted_time' => $nextRow['formatted_time'], // Include formatted time
                'timestamp' => $nextRow['timestamp']
            ];
        }

        echo json_encode([
            'status' => 'nextAvailableDate',
            'nextDate' => $nextDate,
            'messages' => $nextMessages
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No messages found for the selected date or any future date']);
    }
}

$stmt->close();
$con->close();
?>

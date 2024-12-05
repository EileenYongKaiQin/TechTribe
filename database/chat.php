<?php
// Database connection
$servername = "localhost";
$username = "FlexMatch";
$password = "parttimejob2024";
$dbname = "flexmatch_db";  

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the POST request (for saving a message)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senderRole = $_POST['senderRole'];  // job_seeker or employer
    $messageContents = $_POST['messageContents'];  // The message content
    $messageID = isset($_POST['messageID']) ? $_POST['messageID'] : null;
    $delete = isset($_POST['delete']) ? $_POST['delete'] : false;

    if ($delete) {
        // Delete message
        $stmt = $conn->prepare("DELETE FROM message WHERE id = ? AND senderRole = ?");
        $stmt->bind_param("is", $messageID, $senderRole);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete message']);
        }
        $stmt->close();
    } elseif ($messageID) {
        // Update message (edit message)
        $stmt = $conn->prepare("UPDATE message SET messageContents = ? WHERE id = ? AND senderRole = ?");
        $stmt->bind_param("sis", $messageContents, $messageID, $senderRole);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update message']);
        }
        $stmt->close();
    } else {
        // Insert new message
        $stmt = $conn->prepare("INSERT INTO message (senderRole, messageContents) VALUES (?, ?)");
        $stmt->bind_param("ss", $senderRole, $messageContents);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save message']);
        }
        $stmt->close();
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle the GET request (for loading chat history)
    $sql = "SELECT id, senderRole, messageContents, timestamp FROM message ORDER BY timestamp ASC";
    $result = $conn->query($sql);

    $message = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $message[] = [
                'id' => $row['id'],
                'senderRole' => $row['senderRole'],
                'messageContents' => $row['messageContents'],
                'timestamp' => $row['timestamp']
            ];
        }
        echo json_encode($message);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No messages found']);
    }
}

$conn->close();
?>

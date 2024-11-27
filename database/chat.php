<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flexMatch_db";  // Replace with your database name

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
    $delete = isset($_POST['delete']) ? $_POST['delete'] : null;

    if ($delete) {
        // Delete message
        $stmt = $conn->prepare("DELETE FROM messages WHERE id = ? AND senderRole = ?");
        $stmt->bind_param("is", $messageID, $senderRole);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete message']);
        }
        $stmt->close();
    } elseif ($messageID) {
        // Update message (edit message)
        $stmt = $conn->prepare("UPDATE messages SET messageContents = ? WHERE id = ?");
        $stmt->bind_param("si", $messageContents, $messageID);
    } else {
        // Insert new message
        $stmt = $conn->prepare("INSERT INTO messages (senderRole, messageContents) VALUES (?, ?)");
        $stmt->bind_param("ss", $senderRole, $messageContents);
    }

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save message']);
    }

    $stmt->close();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle the GET request (for loading chat history)
    $sql = "SELECT id, senderRole, messageContents, timestamp FROM messages ORDER BY timestamp ASC";
    $result = $conn->query($sql);

    $messages = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $messages[] = [
                'id' => $row['id'],
                'senderRole' => $row['senderRole'],
                'messageContents' => $row['messageContents'],
                'timestamp' => $row['timestamp']
            ];
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No messages found']);
    }

    echo json_encode($messages);
}

$conn->close();
?>

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
    $senderRole = $_POST['sender_role'];  // job_seeker or employer
    $message = $_POST['message'];  // The message content
    $messageId = isset($_POST['message_id']) ? $_POST['message_id'] : null;
    $delete = isset($_POST['delete']) ? $_POST['delete'] : null;

    if ($delete) {
        // Delete message
        $stmt = $conn->prepare("DELETE FROM messages WHERE id = ? AND sender_role = ?");
        $stmt->bind_param("is", $messageId, $senderRole);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete message']);
        }
        $stmt->close();
    } elseif ($messageId) {
        // Update message (edit message)
        $stmt = $conn->prepare("UPDATE messages SET message = ? WHERE id = ?");
        $stmt->bind_param("si", $message, $messageId);
    } else {
        // Insert new message
        $stmt = $conn->prepare("INSERT INTO messages (sender_role, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $senderRole, $message);
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
    $sql = "SELECT id, sender_role, message, timestamp FROM messages ORDER BY timestamp ASC";
    $result = $conn->query($sql);

    $messages = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $messages[] = [
                'id' => $row['id'],
                'sender_role' => $row['sender_role'],
                'message' => $row['message'],
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

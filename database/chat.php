<?php
// Database connection (update with your own DB connection settings)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flexMatch_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request (for saving a message)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senderRole = $_POST['sender_role'];
    $message = $_POST['message'];

    // Insert message into the database
    $stmt = $conn->prepare("INSERT INTO messages (sender_role, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $senderRole, $message);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save message']);
    }
    $stmt->close();
} 
// Handle GET request (for loading chat history)
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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
// Handle PUT request (for editing a message)
else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);
    $messageId = $_PUT['message_id'];
    $newMessageContent = $_PUT['message'];

    $stmt = $conn->prepare("UPDATE messages SET message = ? WHERE id = ?");
    $stmt->bind_param("si", $newMessageContent, $messageId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update message']);
    }
    $stmt->close();
} 
// Handle DELETE request (for deleting a message)
else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $messageId = $_DELETE['message_id'];

    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $messageId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete message']);
    }
    $stmt->close();
}

// Close the connection
$conn->close();
?>

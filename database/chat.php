<?php
// Database connection (update with your own DB connection settings)
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

    // Prepare SQL query to insert the message into the database
    $stmt = $conn->prepare("INSERT INTO messages (sender_role, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $senderRole, $message);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save message']);
    }

    // Close the prepared statement
    $stmt->close();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle the GET request (for loading chat history)
    $sql = "SELECT sender_role, message, timestamp FROM messages ORDER BY timestamp ASC";
    $result = $conn->query($sql);

    // Initialize an array to store messages
    $messages = array();

    if ($result->num_rows > 0) {
        // Fetch each message and add it to the array
        while($row = $result->fetch_assoc()) {
            $messages[] = [
                'sender_role' => $row['sender_role'],
                'message' => $row['message'],
                'timestamp' => $row['timestamp']
            ];
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No messages found']);
    }

    // Return the messages as a JSON response
    echo json_encode($messages);
}

// Close the connection
$conn->close();
?>

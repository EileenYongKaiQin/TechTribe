
<?php
session_start();
include('../database/config.php');

// Handle user login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to validate user credentials
    $stmt = $con->prepare("SELECT userID FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['userID'] = $row['userID']; // Store userID in session
        echo json_encode(['status' => 'success', 'message' => 'Login successful']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
    }
    $stmt->close();
    exit; // Stop further execution after login
}

// Check if user is logged in before proceeding
if (!isset($_SESSION['userID'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$userID = $_SESSION['userID']; // Get userID from session

// Handle the POST request (for saving a message)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senderRole = $_POST['senderRole'];  // job_seeker or employer
    $messageContents = $_POST['messageContents'];  // The message content
    $messageID = isset($_POST['messageID']) ? $_POST['messageID'] : null;
    $delete = isset($_POST['delete']) ? $_POST['delete'] : false;

    // Debugging: Log the userID and message contents
    error_log("UserID: $userID, SenderRole: $senderRole, Message: $messageContents");

    if ($delete) {
        // Delete message
        $stmt = $con->prepare("DELETE FROM message WHERE id = ? AND userID = ? AND senderRole = ?");
        $stmt->bind_param("iis", $messageID, $userID, $senderRole);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete message']);
        }
        $stmt->close();
    } elseif ($messageID) {
        // Update message (edit message)
        $stmt = $con->prepare("UPDATE message SET messageContents = ? WHERE id = ? AND userID = ? AND senderRole = ?");
        $stmt->bind_param("siis", $messageContents, $messageID, $userID, $senderRole);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update message']);
        }
        $stmt->close();
    } else {
        // Insert new message
        $stmt = $con->prepare("INSERT INTO message (userID, senderRole, messageContents) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userID, $senderRole, $messageContents);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save message']);
        }
        $stmt->close();
    }
}

// Handle the GET request (for loading chat history)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT id, userID, senderRole, messageContents, DATE_FORMAT(timestamp, '%d %b %Y') AS formatted_date, timestamp FROM message ORDER BY timestamp ASC";
    $result = $con->query($sql);

    $message = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $message[] = [
                'id' => $row['id'],
                'userID' => $row['userID'], // Include userID in the result
                'senderRole' => $row['senderRole'],
                'messageContents' => $row['messageContents'],
                'formatted_date' => $row['formatted_date'],
                'timestamp' => $row['timestamp']
            ];
        }
        echo json_encode($message);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No messages found']);
    }
}

$con->close();
?>

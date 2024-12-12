<?php
// Include necessary files
include '../database/config.php';
include 'notification.php';

// Get the userID from the POST request
$data = json_decode(file_get_contents("php://input"), true);
$userID = $data['userID'];

// Call the function to mark all notifications as read
if (markNotificationsAsRead($userID)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to mark notifications as read']);
}
?>

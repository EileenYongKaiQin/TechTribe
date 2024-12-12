<?php
// Function to create a notification
function createNotification($userID, $notificationText, $notificationType) {
    global $con;

    // Check if the notification already exists for the same user and notification text
    $checkQuery = "SELECT COUNT(*) AS notificationCount FROM notification WHERE userID = ? AND notificationText = ? AND notificationType = ?";
    $stmtCheck = $con->prepare($checkQuery);
    $stmtCheck->bind_param('sss', $userID, $notificationText, $notificationType);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $rowCheck = $resultCheck->fetch_assoc();

    // If the notification already exists, return false (skip creation)
    if ($rowCheck['notificationCount'] > 0) {
        return false;
    }

    // Insert new notification if it doesn't exist
    $insertNotificationQuery = "INSERT INTO notification (userID, notificationText, notificationType) VALUES (?, ?, ?)";
    $stmt = $con->prepare($insertNotificationQuery);
    $stmt->bind_param('sss', $userID, $notificationText, $notificationType);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


// Fetch all notifications, but also calculate the unread count
function fetchNotifications($userID) {
    global $con;

    // Fetch all notifications for the user
    $query = "SELECT * FROM notification WHERE userID = ? ORDER BY createdAt DESC";
    $stmt = $con->prepare($query);
    $stmt->bind_param('s', $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the unread count
    $unreadCountQuery = "SELECT COUNT(*) as unreadCount FROM notification WHERE userID = ? AND isRead = 0";
    $stmtUnread = $con->prepare($unreadCountQuery);
    $stmtUnread->bind_param('s', $userID);
    $stmtUnread->execute();
    $unreadResult = $stmtUnread->get_result();
    $unreadCount = $unreadResult->fetch_assoc()['unreadCount'];

    return ['notifications' => $result, 'unreadCount' => $unreadCount];
}


// Function to mark a notification as read
function markNotificationsAsRead($userID) {
    global $con;

    $updateQuery = "UPDATE notification SET isRead = 1 WHERE userID = ? AND isRead = 0";
    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param('s', $userID);
    
    return $stmt->execute(); // Returns true if successfully updated
}


?>

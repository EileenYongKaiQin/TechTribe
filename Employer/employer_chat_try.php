<?php
session_start();
include('../database/config.php');



$employerID = $_SESSION['userID']; // Employer's ID from session
$employerName = htmlspecialchars($_SESSION['fullName']); // Employer's name
$jobSeekerID = $_GET['jobSeekerID'] ?? null; // Get jobSeekerID from URL

if (!$jobSeekerID) {
    echo "No job seeker selected.";
    exit;
}

// Handle new message submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $messageContents = $_POST['messageContents'] ?? '';
    $senderRole = 'employer';

    // Save the message in the database
    $stmt = $con->prepare("INSERT INTO message (userID, messageContents, senderRole, jobSeekerID) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $employerID, $messageContents, $senderRole, $jobSeekerID);

    if ($stmt->execute()) {
        echo "Message sent successfully.";
    } else {
        echo "Failed to send the message.";
    }
    $stmt->close();
}

// Fetch chat history
$stmt = $con->prepare("SELECT * FROM message WHERE (userID = ? AND jobSeekerID = ?) OR (userID = ? AND jobSeekerID = ?) ORDER BY timestamp ASC");
$stmt->bind_param("ssss", $employerID, $jobSeekerID, $jobSeekerID, $employerID);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat with Job Seeker</title>
</head>
<body>
<h2>Chat with Job Seeker (ID: <?php echo htmlspecialchars($jobSeekerID); ?>)</h2>

<!-- Chat History -->
<div id="chat-history">
    <?php foreach ($messages as $message): ?>
        <p><strong><?php echo htmlspecialchars($message['senderRole']); ?>:</strong> <?php echo htmlspecialchars($message['messageContents']); ?></p>
    <?php endforeach; ?>
</div>

<!-- Message Input Form -->
<form method="POST">
    <textarea name="messageContents" placeholder="Type your message..." required></textarea>
    <button type="submit">Send</button>
</form>
</body>
</html>

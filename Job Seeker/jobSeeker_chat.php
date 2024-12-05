<?php
// Include the necessary files for session handling and user verification
include '../database/config.php';
include 'jobSeeker1.php'; // This includes the session_start and user verification

// Main content
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <title>Job Seeker Chat</title>
    <link rel="stylesheet" href="../css/jobSeeker_chat.css"> <!-- Link to your CSS file -->
</head>
<body>
    <!-- The header and sidebar are included in jobSeekerNew.php -->

    <div class="main-content">
        <section class="content">
            <h2 class="chat-window-title">Chat Section</h2>
            <div class="chat-section" id="chatSection"></div>
        
            <form id="chatForm" onsubmit="sendMessage(event, 'job_seeker')">
                <input type="text" id="chatInput" class="chat-input" placeholder="Type a message...">
                <button type="submit">Send</button>
            </form>
        </section>
    </div>

    <!-- Grey Overlay -->
    <div id="overlay" class="overlay"></div>

    <!-- Notification Modal -->
    <div id="notificationModal" class="modal">
        <div class="modal-content">
            <p>Your message has been sent to the employer.</p>
            <button onclick="closeNotification()">Ok</button>
        </div>
    </div>

    <script src="../js/job_seeker_chat.js"></script>
</body>
</html>

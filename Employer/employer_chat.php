<?php
// Include the employerNew.php, which handles session and user verification
include '../database/config.php';
include 'employerNew.php'; // This includes the session_start and user verification

// Main content
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <title>Employer Chat</title>
    <link rel="stylesheet" href="../css/employer_chat.css"> <!-- Link to your CSS file -->
</head>
<body>
    <!-- The header and sidebar are included in employerNew.php -->

    <div class="main-content">
        <section class="content">
            <h2 class="chat-window-title">Chat Section</h2>
            <div class="chat-section" id="chatSection"></div>
        
            <form id="chatForm" onsubmit="sendMessage(event, 'employer')">
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
            <p>Your message has been sent to the job seeker.</p>
            <button onclick="closeNotification()">Ok</button>
        </div>
    </div>

    <script src="../js/employer_chat.js"></script>
</body>
</html>

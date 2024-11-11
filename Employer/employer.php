<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Chat</title>
    <link rel="stylesheet" href="../css/chat.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <button class="close-btn" onclick="toggleSidebar()">X</button>
    <ul>
        <li>Dashboard</li>
        <li>Profile</li>
        <li>Messages</li>
        <li>Settings</li>
    </ul>
</div>

<!-- Main content -->
<div class="content">
    <header>
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <span class="flexmatch-title">FlexMatch</span>  
    </header>

    <div class="container">
        <h2 class="chat-window-title">Chat Window - Employer</h2>
        <div class="chat-section" id="chatSection"></div>

        <form id="chatForm" onsubmit="sendMessage(event, 'employer')">
            <input type="text" id="chatInput" class="chat-input" placeholder="Type a message...">
            <button type="submit">Send</button>
        </form>
    </div>

    <footer><?php include('../footer/footer.php'); ?></footer>
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

<!-- Edit Message Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <textarea id="editMessageInput" class="chat-input" rows="3"></textarea>
        <button onclick="saveEditedMessage()">Save</button>
        <button onclick="closeEditModal()">Cancel</button>
    </div>
</div>

<script src="../js/chat.js"></script>

</body>
</html>

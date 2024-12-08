<?php
include '../database/config.php';
include 'employerNew.php'; // Include session_start and user verification
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
    <div class="main-content">
        <section class="content">
            <div class="chat-container">
                <div class="job-seeker-info">
                    <span id="jobSeekerName">Karry Wang</span>
                    <span class="arrow" onclick="toggleDropdown()">▼</span>
                    <div id="dropdownMenu" class="dropdown-menu">
                        <ul>
                            <li onclick="viewProfile()">View Profile</li>
                            <li onclick="searchChat()">Search Chat</li>
                            <li onclick="deleteChat()">Delete Chat</li>
                            <li onclick="report()">Report</li>
                        </ul>
                    </div>
                </div>
                <div class="chat-section" id="chatSection"></div>
            </div>

            <form id="chatForm" onsubmit="sendMessage(event, 'employer')">
                <input type="text" id="chatInput" class="chat-input" placeholder="Type a message...">
                <button type="submit">Send</button>
            </form>
        </section>
    </div>

    <div id="overlay" class="overlay"></div>
    <div id="notificationModal" class="modal">
        <div class="modal-content">
            <p>Your message has been sent to the job seeker.</p>
            <button onclick="closeNotification()">Ok</button>
        </div>
    </div>

    <script src="../js/employer_chat.js"></script>
    <script>
    function toggleDropdown() {
        const dropdownMenu = document.getElementById("dropdownMenu");
        dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
    }

    function viewProfile() {
        alert("Viewing profile...");
    }

    function searchChat() {
        alert("Searching chat...");
    }

    function deleteChat() {
        alert("Deleting chat...");
    }

    function report() {
        alert("Reporting...");
    }

    window.onclick = function(event) {
        if (!event.target.matches('.arrow') && !event.target.matches('.dropdown-menu')) {
            const dropdowns = document.getElementsByClassName("dropdown-menu");
            for (let i = 0; i < dropdowns.length; i++) {
                dropdowns[i].style.display = "none";
            }
        }
    };
    </script>
</body>
</html>

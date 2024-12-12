
<?php
include '../database/config.php';
include 'employerNew.php'; // Include session_start and user verification



// Get the job seeker ID from the URL
if (isset($_GET['jobSeekerID'])) {
    $jobSeekerID = $_GET['jobSeekerID'];

    // Fetch job seeker details from the database
    $sql = "SELECT fullName FROM jobseeker WHERE userID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $jobSeekerID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $jobSeeker = $result->fetch_assoc();
        $jobSeekerName = htmlspecialchars($jobSeeker['fullName']);
    } else {
        // Handle case where the job seeker is not found
        $jobSeekerName = "Unknown Job Seeker";
    }
} else {
    // Handle case where no job seeker ID is provided
    $jobSeekerName = "No Job Seeker Selected";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <title>Employer Chat</title>
    <link rel="stylesheet" href="../css/employer_chat.css"> Link to your CSS file

</head>
<body>
    <div class="main-content">
        <section class="content">
            <!-- <?php echo htmlspecialchars($fullName); ?>
            <?php echo htmlspecialchars($userID); ?> -->
            <div class="chat-container">
                <div class="job-seeker-info">
                    <span id="jobSeekerName"><?php echo $jobSeekerName; ?></span>
                    <!-- <span id="jobSeekerName"><?php echo $jobSeekerID; ?></span> -->
                    <img src="../images/down-arrow.png" alt="Toggle Dropdown" class="arrow-icon" onclick="toggleDropdown()">
                    <!-- <input type="text" id="searchBar" class="search-bar" placeholder="Search chat..."> -->
                    <div id="dropdownMenu" class="dropdown-menu">
                        <ul>
                            <!-- <li onclick="viewProfile()">View Profile</li> -->
                            <li onclick="searchChat()">Search Chat</li>
                            <!-- <li onclick="deleteChat()">Delete Chat</li>
                            <li onclick="report()">Report</li> -->
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

    <div id="editMessageModal" class="modal">
    <div class="modal-content">
        <span class="close" id="modalCloseBtn">&times;</span>
        <h2>Edit Message</h2><br>
        <textarea id="editMessageInput" rows="5" placeholder="Edit your message..."></textarea><br>
        <button id="saveEditButton" class="modal-content-edit">Save</button>
    </div>
    </div>

    <!-- Delete Message Modal -->
    <div id="deleteMessageModal" class="modal">
        <div class="modal-content">
            <span class="close" id="deleteModalCloseBtn">&times;</span>
            <h2>Delete Message</h2><br>
            <p>Are you sure you want to delete this message?</p>
            <button id="confirmDeleteButton" class="modal-content-delete">Delete</button>
            <button id="cancelDeleteButton" class="modal-content-cancel">Cancel</button>
        </div>
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

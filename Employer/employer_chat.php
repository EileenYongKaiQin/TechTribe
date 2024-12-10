
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
    <!-- <link rel="stylesheet" href="../css/employer_chat.css"> Link to your CSS file -->
    <style>
        /* General Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background: #F0FDFF;
}

.main-content {
    margin-left: 180px;
    width: calc(100% - 180px);
}

.content {
    padding: 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 1px;
    align-items: center;
    justify-content: flex-start;
}

/* Chat Container */
.chat-container {
    display: flex;
    flex-direction: column; /* Stack job seeker info and chat section vertically */
    width: 60%; /* Ensure consistent width */
}

/* Chat Window Title */
.chat-window-title {
    font-size: 24px;
    margin-bottom: 5px;
}

/* Chat Section */
.chat-section {
    min-height: 400px;
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #DBDDE0;
    border-bottom-left-radius: 8px; /* No rounding for bottom left corner */
    border-bottom-right-radius: 8px; 
    padding: 10px;
    background-color: #e8e8e8;
    display: flex; /* Ensure chat section uses flexbox */
    flex-direction: column; /* Stack messages vertically */
}

.chat-section::before {
    content: "No messages yet. Start the conversation!";
    color: #888;
    font-size: 16px;
    font-style: italic;
    display: none; /* Hidden when messages exist */
}

/* Show placeholder only if the chat section is empty */
.chat-section:empty::before {
    display: block;
}

/* Chat Message Styles */
.chat-message {
    flex-direction: column; /* Stack author and message */
    margin-bottom: 20px; /* Increased space between messages */
    padding: 15px 10px;
    border-radius: 8px;
    border-color: black;
    box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
    position: relative; /* For absolute positioning of buttons */
    width: fit-content; /* Allow messages to shrink to fit content */
}

/* Job Seeker Message */
.job-seeker-message {
    background-color: rgb(255, 255, 255);
    align-self: flex-start; /* Align to the left */
    text-align: left;
}

/* Employer Message */
.employer-message {
    background-color: rgb(224, 246, 237);
    align-self: flex-end; /* Align to the right */
    text-align: right;
}

/* Chat Input and Send Button */
#chatForm {
    display: flex;
    margin-top: 20px; /* Space between chat section and form */
}

.chat-input {
    width: 710px;
    padding: 18px;
    border-radius: 4px;
    border: 1px solid #DBDDE0;
}

.chat-input:focus {
    outline: none; /* Remove outline on focus */
    border-color: #AAE1DE; /* Change border color on focus */
}

button {
    padding: 10px 20px; /* Adjusted padding for button */
    border: none;
    border-radius: 4px;
    background-color: #AAE1DE;
    color: #FFFFFF;
    cursor: pointer;
    margin-left: 10px; /* Space between input and button */
}

button:hover {
    background-color: #009688; /* Darker shade on hover */
}

/* Overlay Styles */
.overlay {
    display: none; /* Hidden by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    z-index: 1000; /* On top of everything */
}

/* Notification Modal Styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    z-index: 1001; /* Above the overlay */
    width: 300px; /* Fixed width for the modal */
}

.modal-content {
    padding: 20px;
    text-align: center;
}

.modal-content p {
    margin-bottom: 20px;
    font-size: 16px;
    color: #333333;
}

.modal-content button {
    background-color: #AAE1DE;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.modal-content button:hover {
    background-color: #88C7C0; /* Darker shade on hover */
}

.button-container {
    display: flex; /* Align buttons horizontally */
    gap: 5px; /* Space between buttons */
}


/* Job Info */
.job-info {
    margin-bottom: 10px; /* Add margin for spacing */
    font-weight: bold; /* Make job name bold */
}

/* Job Seeker Info */
.job-seeker-info {
    display: flex;
    /* display: inline-block; */
    align-items: center;
    position: relative;
    width: 100%; /* Full width to match chat section */
    border: 1px solid #DBDDE0; /* Border */
    border-top-left-radius: 8px; /* Round the top left corner */
    border-top-right-radius: 8px; /* Round the top right corner */
    padding: 10px; /* Padding */
    background-color: #f0eded; /* Background color */
    justify-content: flex-start; /* Align items to the left */
    
}

.search-bar {
    margin-left: auto; /* Space between the dropdown arrow and the search bar */
    padding: 5px; /* Padding for the search bar */
    border: 1px solid #ccc; /* Border for the search bar */
    border-radius: 4px; /* Rounded corners */
    width: 200px; /* Width of the search bar */
    font-size: 14px; /* Font size */
}

.arrow {
    cursor: pointer;
    margin-left: 5px;
}

.dropdown-menu {
    display: none; /* Hide by default */
    position: absolute;
    background-color: #e0f7fa;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    z-index: 100;
    margin-top: 5px; /* Space below the arrow */
    left: 0; /* Align dropdown menu to the left */
}

.dropdown-menu ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.dropdown-menu li {
    padding: 10px;
    cursor: pointer;
}

.dropdown-menu li:hover {
    background-color: #b2ebf2; /* Highlight on hover */
}


.ellipsis-button {
    position: absolute; /* Position the button absolutely within the container */
    left: -54px; /* Adjust this value to move the button left as needed */
    top: 50%; /* Center vertically */
    transform: translateY(-50%); /* Adjust to align center */
    z-index: 1000;
    display: none; /* Hide by default */
    background-color: #e8e8e8;
    color: black;
    font-size: 20px;
}

.ellipsis-button:hover {
    background-color: #e8e8e8; /* Darker shade on hover */
}

.message-container:hover .ellipsis-button {
    display: inline; /* Show the button on hover */
}

.edit-delete-menu {
    display: flex; /* Align buttons horizontally */
    flex-direction: column; /* Stack buttons vertically */
    position: absolute; /* Position relative to the message */
    left: -50%; /* Adjust position as necessary */
    top: 100%; /* Position below the message */
    background: white; /* Background color */
    border: 1px solid #ccc; /* Border style */
    border-radius: 6px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow for depth */
    z-index: 1000; /* Ensure it appears above other elements */
    padding: 6px; /* Add padding around the buttons inside the menu */
}

.edit-delete-menu button {
    display: block; /* Ensure buttons take full width */
    background-color: #AAE1DE; /* Primary color for buttons */
    color: white; /* White text color */
    border: none; /* Remove border */
    padding: 8px 12px; /* Padding for comfort */
    cursor: pointer; /* Change cursor to pointer on hover */
    border-radius: 4px; /* Slightly rounded corners */
    transition: background-color 0.3s; /* Smooth transition for hover effect */
    margin: 4px 0; /* Margin between buttons */

}

.edit-delete-menu button:hover {
    background-color: #009688; /* Darker shade on hover */
}




.message-timestamp {
    display: block; /* Display as block to occupy full width */
    font-size: 10px; /* Smaller font size for the timestamp */
    color: gray; /* Gray color for the timestamp */
    text-align: right; /* Align text to the right */
    margin-top: 3px; /* Margin above the timestamp */
  

}


.date-separator {
    text-align: center;
    margin: 10px 0;
    font-weight: bold;
    color: #888; /* Change color as desired */
    position: relative;
}

.date-separator:before {
    content: '';
    display: block;
    width: 100%;
    border-top: 1px solid #ccc; /* Line above the date */
    margin: 5px 0; /* Space above and below the line */
}
    </style>
</head>
<body>
    <div class="main-content">
        <section class="content">
            <div class="chat-container">
                <div class="job-seeker-info">
                    <span id="jobSeekerName"><?php echo $jobSeekerName; ?></span>
                    <span id="jobSeekerName"><?php echo $jobSeekerID; ?></span>
                    <span class="arrow" onclick="toggleDropdown()">▼</span>
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
                <?php echo htmlspecialchars($fullName); ?>
                <?php echo htmlspecialchars($userID); ?>
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

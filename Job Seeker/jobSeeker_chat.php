
<?php
include '../database/config.php';
include 'jobSeeker1.php'; // Include session_start and user verification



// Get the job seeker ID from the URL
if (isset($_GET['employerID'])) {
    $employerID = $_GET['employerID'];

    // Fetch job seeker details from the database
    $sql = "SELECT fullName FROM employer WHERE userID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $employerID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employer = $result->fetch_assoc();
        $employerName = htmlspecialchars($employer['fullName']);
    } else {
        // Handle case where the job seeker is not found
        $employerName = "Unknown Employer";
    }
} else {
    // Handle case where no job seeker ID is provided
    $employerName = "No Employer Selected";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <title>Job Seeker Chat</title>
    <!-- <link rel="stylesheet" href="../css/employer_chat.css"> Link to your CSS file -->
<style>

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
    padding: 10px;
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
    border-bottom-left-radius: 15px; /* No rounding for bottom left corner */
    border-bottom-right-radius: 15px; 
    padding: 10px;
    background-color: #efefef;
    display: flex; /* Ensure chat section uses flexbox */
    flex-direction: column; /* Stack messages vertically */
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.3);
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
    margin-bottom: 15px; /* Increased space between messages */
    padding: 10px 10px;
    border-radius: 15px;
    border-color: black;
    box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
    position: relative; /* For absolute positioning of buttons */
    width: fit-content; /* Allow messages to shrink to fit content */
}

/* Job Seeker Message */
.job-seeker-message {
    background-color: rgb(224, 246, 237);
    align-self: flex-end; /* Align to the left */
    text-align: left;
    font-size: 16px;
    border-radius: 15px;
    transition: background-color 0.3s ease, transform 0.3s ease;
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.2);
}

.job-seeker-message:hover {
    transform: scale(1.02); /* Slightly increase the button size */
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.3);
}

/* Employer Message */
.employer-message {
    background-color: rgb(255, 255, 255);
    align-self: flex-start; /* Align to the right */
    text-align: right;
    font-size: 16px;
    border-radius: 15px;
    transition: background-color 0.3s ease, transform 0.3s ease;
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.2);
}

.employer-message:hover {
    transform: scale(1.02); /* Slightly increase the button size */
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.3);
}


/* Chat Input and Send Button */
#chatForm {
    display: flex;
    margin-top: 20px; /* Space between chat section and form */
}

.chat-input {
    width: 720px;
    padding: 18px;
    border-radius: 4px;
    border: 1px solid #DBDDE0;
    border-radius: 15px;
    font-size: 15px;
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transition for color and scaling */
}

.chat-input:focus {
    outline: none; /* Remove outline on focus */
    border-color: #AAE1DE; /* Change border color on focus */
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.5);
}

.chat-input:hover {
    outline: none; /* Remove outline on focus */
    border-color: #AAE1DE; /* Change border color on focus */
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.5);
}

button {
    padding: 10px 20px; /* Adjusted padding for button */
    border: none;
    border-radius: 15px;
    background-color: #AAE1DE;
    color: #FFFFFF;
    cursor: pointer;
    font-size: 15px;
    margin-left: 10px; /* Space between input and button */
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transition for color and scaling */
}

button:hover {
    background-color: #009688; /* Darker shade on hover */
    transform: scale(1.05); /* Slightly increase the button size */
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.5);
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

.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 9999; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 3px solid #888;
    width: 25%; /* Could be more or less, depending on screen size */
    text-align: center; /* Center the text */
    border-radius: 13px;
    font-size: 12px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

/* .modal-content button {
    margin-top: 8%;
    margin-right: 2%;
    margin-left: 2%;
    border-radius: 20px;
    background-color: blue;
    font-size: 15px;
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.3);
} */

.modal-content-edit {
    margin-top: 8%;
    margin-right: 2%;
    margin-left: 2%;
    border-radius: 20px;
    background-color: blue;
    font-size: 15px;
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.modal-content-edit:hover {
    background-color: rgb(0, 0, 159); /* Darker gray on hover */
    transform: scale(1.05);
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.5);
}

.modal-content-delete {
    margin-top: 8%;
    margin-right: 2%;
    margin-left: 2%;
    border-radius: 20px;
    background-color: red;
    font-size: 15px;
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.modal-content-delete:hover {
    background-color: rgb(189, 0, 0); /* Darker gray on hover */
    transform: scale(1.05);
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.5);
}

.modal-content-cancel {
    margin-top: 8%;
    margin-right: 2%;
    margin-left: 2%;
    border-radius: 20px;
    background-color: blue;
    font-size: 15px;
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.modal-content-cancel:hover {
    background-color: rgb(0, 0, 159); /* Darker gray on hover */
    transform: scale(1.05);
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.5);
}

.modal-content-okay {
    margin-top: 8%;
    margin-right: 2%;
    margin-left: 2%;
    border-radius: 20px;
    background-color: blue;
    font-size: 15px;
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.modal-content-okay:hover {
    background-color: rgb(0, 0, 159); /* Darker gray on hover */
    transform: scale(1.05);
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.5);
}

.button-container {
    display: flex; /* Align buttons horizontally */
    gap: 5px; /* Space between buttons */
}

#editMessageInput {
    width: 100%; /* Sets the width to 100% of the parent container */
    max-width: 600px; /* Adjust this value to set a maximum width */
    padding: 10px; /* Add padding for better text input experience */
    border: 1px solid #ccc; /* Add border for visibility */
    border-radius: 5px; /* Round the corners */
    resize: none; /* Disable resizing if desired */
    font-size: 16px; /* Set font size for better readability */
}

/* Job Info */
.job-info {
    margin-bottom: 12px; /* Add margin for spacing */
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
    border-top-left-radius: 15px; /* Round the top left corner */
    border-top-right-radius: 15px; /* Round the top right corner */
    padding: 14px; /* Padding */
    background-color: #f1f1f1; /* Background color */
    justify-content: space-between; /* Align items to the left */
    box-shadow:  2px 0px 5px rgba(0, 0, 0, 0.3);
}

.job-seeker-name {
    text-align: left; /* Align text to the left */
    font-weight: bold; /* Optional: make the name bold */
    white-space: nowrap; /* Prevent the text from wrapping to a new line */
}

.search-container {
    width: 80%;
    position: relative; /* Set position to relative to position the icon */
    display: inline-block; /* Allow the container to adjust to the input size */
}

.search-icon {
    width: 20px; /* Set the size of the icon */
    height: 20px; /* Set the size of the icon */
    margin-right: 10px; /* Space between the icon and input */
    margin-left: 305px; 
    margin-top: 0px; /* Adjust this value to move the icon down */
    vertical-align: middle; /* Align the icon vertically with the input */
    transition: background-color 0.3s ease, transform 0.3s ease;
    cursor: pointer;
}

.search-icon:hover {
    transform: scale(1.1);
}



.search-bar {
    width: 30%;
    padding:    8px;
    border-radius: 4px;
    border: 1px solid #DBDDE0;
    border-radius: 15px;
    font-size: 15px;
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transition for color and scaling */
}

.search-bar:focus {
    outline: none; /* Remove outline on focus */
    border-color: #AAE1DE; /* Change border color on focus */
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.3);
}

.search-bar:hover {
    outline: none; /* Remove outline on focus */
    border-color: #AAE1DE; /* Change border color on focus */
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.5);
}

.search-button {
    padding: 8px 12px; /* Padding for the button */
    border: none; /* No border */
    border-radius: 15px; /* Rounded corners */
    background-color: #007BFF; /* Button background color */
    color: white; /* Button text color */
    cursor: pointer; /* Pointer cursor on hover */
}

.search-button:hover {
    background-color: #0056b3; /* Darker shade on hover */
}

.filter-icon {
    width: 20px; /* Set the size of the icon */
    height: 20px; /* Set the size of the icon */
    margin-right: 3px; /* Space between the icon and input */
    margin-left: 10px; 
    margin-top: 0px; /* Adjust this value to move the icon down */
    vertical-align: middle; /* Align the icon vertically with the input */
    transition: background-color 0.3s ease, transform 0.3s ease;
    cursor: pointer;

}

.filter-icon:hover {
    transform: scale(1.1); /* Slightly increase the button size */
}

.arrow {
    cursor: pointer;
    margin-left: 5px;
}

.dropdown-menu {
    display: none; /* Hide by default */
    position: absolute;
    width: 17%;
    background-color: #e0f7fa;
    border-radius: 15px;
    z-index: 100;
    margin-top: 17.5%; /* Space below the arrow */
    left: 2px; /* Align dropdown menu to the left */
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.dropdown-menu ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
    border-radius: 15px;
    transition: background-color 0.3s ease, transform 0.3s ease
}

.dropdown-menu li {
    padding: 10px;
    cursor: pointer;
    border-radius: 15px;
    transition: background-color 0.3s ease, transform 0.3s ease
}

.dropdown-menu li:hover {
    background-color: #b2ebf2; /* Highlight on hover */
    transform: scale(1.02);
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.3);
}


.ellipsis-button {
    position: absolute; /* Position the button absolutely within the container */
    left: -56px; /* Adjust this value to move the button left as needed */
    top: 10%; /* Center vertically */
    z-index: 1000;
    display: none; /* Hide by default */
    background-color: transparent;
    color: black;
    font-size: 22px;
    transition: background-color 0.3s ease, transform 0.3s ease;
    box-shadow: none;
}

.ellipsis-button:hover {
    background-color: transparent; /* Darker shade on hover */
    transform: scale(1.05); /* No scaling on hover */
    box-shadow: none;
}

.message-container:hover .ellipsis-button {
    display: inline; /* Show the button on hover */
}

.edit-delete-menu {
    display: flex; /* Align buttons horizontally */
    flex-direction: column; /* Stack buttons vertically */
    position: absolute; /* Position relative to the message */
    left: -40px; /* Adjust position as necessary */
    top: 100%; /* Position below the message */
    background: white; /* Background color */
    border: 1px solid #ccc; /* Border style */
    border-radius: 15px; /* Rounded corners */
    z-index: 1000; /* Ensure it appears above other elements */
    padding: 6px; /* Add padding around the buttons inside the menu */
    align-items: center;
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease, transform 0.3s ease; 
}

.edit-delete-menu:hover {
    transform: scale(1.02);
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.3);
}

.edit-delete-menu button {
    display: block; /* Ensure buttons take full width */
    color: white; /* White text color */
    border: none; /* Remove border */
    padding: 4px 5px; /* Padding for comfort */
    cursor: pointer; /* Change cursor to pointer on hover */
    border-radius: 8px; /* Slightly rounded corners */
    font-size: 15px;
    margin: 5px 0; /* Margin between buttons */
    width: 100%;
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.edit-button {
    background-color: blue; /* Background color for Edit button */
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.edit-button:hover {
    background-color: rgb(0, 0, 159);  /* Darker color on hover for Edit button */
    transform: scale(1.05); /* Slight scaling effect */
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.5);
}

.delete-button {
    background-color: red; /* Background color for Delete button */
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.delete-button:hover {
    background-color: rgb(189, 0, 0);  /* Darker color on hover for Delete button */
    transform: scale(1.05); /* Slight scaling effect */
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.5);
}


.message-timestamp {
    display: block; /* Display as block to occupy full width */
    font-size: 10px; /* Smaller font size for the timestamp */
    color: gray; /* Gray color for the timestamp */
    text-align: right; /* Align text to the right */
    margin-top: 5px; /* Margin above the timestamp */
  

}


.date-separator {
    text-align: center;
    margin-bottom: 30px;
    font-weight: bold;
    color: #757575; /* Change color as desired */
    position: relative;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.date-separator:hover {
    transform: scale(1.02); 
}

.date-separator:before {
    content: '';
    display: block;
    width: 100%;
    border-top: 2px solid #ccc; /* Line above the date */
    margin: 5px 0; /* Space above and below the line */
}

.arrow-icon {
    margin: 0 7px; 
    width: 16px; /* Adjust width as needed */
    height: 16px; /* Adjust height as needed */
    cursor: pointer; /* Pointer cursor for clickability */
    display: inline-block; /* Allow the icon to be inline with text */
    vertical-align: middle; /* Align with text */
    text-align: left; /* Align text inside the icon if necessary */
    margin-left: 0; /* Remove any left margin */
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.arrow-icon:hover {
    transform: scale(1.1); /* Slightly increase the button size */
}


.filter-container {
    display: flex;
    flex-direction: column;
    background-color: #f9f9f9;
    border-radius: 15px;
    border: 1px solid #ccc;
    padding: 10px;
    position: absolute;
    z-index: 10;
    left: 634px;
    top: -16px;
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transition for color and scaling */
}

.filter-container:hover {
    transform: scale(1.02); /* Slightly increase the button size */
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.5);
}

.filter-container input {
    margin-top: 5px;
    padding: 5px;
    transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transition for color and scaling */

}

.filter-container button {
    margin-top: 10px;
    padding: 5px 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.3s ease; 
}

.filter-container button:hover {
    background-color: #45a049;
    transform: scale(1.05); /* Slightly increase the button size */
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.5);
}

.message-body b {
    color: #ff5722; /* Example: Orange text */
    font-weight: bold;
}


.search-results {
    position: absolute;
    background: #fff;
    border: 1px solid #ccc;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 260px; /* Adjust based on your layout */
    max-height: 400px; /* Scrollable area */
    overflow-y: auto;
    z-index: 1000; 
    display: none;
    border-radius: 15px;
    top: 50px;
    right: -275px;
    box-shadow:  2px 2px 5px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.3s ease; 
}

.search-results:hover {
    transform: scale(1.02); 
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.5);
}

.search-results .message {
    padding: 5px;
    border-bottom: 0.5px solid grey;
    transition: background-color 0.3s ease, transform 0.3s ease; 

}
.search-results .message:last-child {
    border-bottom: none;
}

.search-results .message:hover {
    background-color: #efefef;
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.3);

}

.clear-icon {
    color: gray; /* Change color as needed */
    font-size: 30px; /* Adjust size as needed */
    width: 20px; /* Set the size of the icon */
    height: 20px; /* Set the size of the icon */
    margin-right: 10px; /* Space between the icon and input */
    margin-left: 10px; 
    margin-top: 0px; /* Adjust this value to move the icon down */
    vertical-align: middle; /* Align the icon vertically with the input */
    transition: background-color 0.3s ease, transform 0.3s ease;
    cursor: pointer;
}

.clear-icon:hover {
    transform: scale(1.1);
}

/* Style for the message container */
.message-header {
    font-size: 14px; /* Adjust as needed */
    color: #888; /* Light gray for subtle text */
    text-align: left; /* Align date to the right */
    padding: 5px 10px; /* Add some spacing around the header */
    border-bottom: 1px solid #ddd; /* Optional border for separation */
}

/* Style for the message body */
.message-body {
    font-size: 16px; /* Slightly larger for better readability */
    color: #333; /* Darker text for contrast */
    padding: 10px; /* Add some padding inside the body */
    border-radius: 5px; /* Rounded corners for modern look */
    margin: 5px 0; /* Spacing between messages */
    word-wrap: break-word; /* Prevent long words or URLs from overflowing */
    white-space: pre-wrap; /* Preserve line breaks in text */
    text-align: left; /* Align date to the right */
    
}

.message-body-no-date {
    font-size: 14px; /* Adjust as needed */
    color: #888; /* Light gray for subtle text */
    text-align: center; /* Align date to the right */
    padding: 5px 10px; /* Add some spacing around the header */
    font-weight: bold; /* Make the text bold */
}

.highlight-message {
    transform: scale(1.02);
    background-color: #efefef;
    transition: background-color 0.3s ease;
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.3);
}

</style>
</head>
<body>
    <div class="main-content">
        <section class="content">
            <!-- <?php echo htmlspecialchars($fullName); ?>
            <?php echo htmlspecialchars($userID); ?> -->
            <div class="chat-container">
                <div class="job-seeker-info">
                    <span id="employerName" class="job-seeker-name"><?php echo $employerName; ?></span>
                    <!-- <span id="jobSeekerName"><?php echo $employerID; ?></span> -->
                    <img src="../images/down-arrow.png" alt="Toggle Dropdown" class="arrow-icon" onclick="toggleDropdown()">
                    <div class="search-container">
                        <img src="../images/Search.png" alt="Search" class="search-icon" onclick="searchChat()">
                        <input type="text" id="searchBar" class="search-bar" placeholder="Search chat..." oninput="toggleClearButton()" onkeydown="checkEnter(event)">
                        <span id="clearSearch" class="clear-icon" onclick="clearSearch()" style="display: none;">&times;</span>
                        <div id="searchResults" class="search-results" style="display: none;"></div> <!-- Search Results Window -->
                        <img src="../images/calendar.png" alt="Filter" class="filter-icon" onclick="toggleDateFilter()">
                        <div id="filterContainer" class="filter-container" style="display: none;">
                            <label for="filterDate">Filter by Date:</label>
                            <input type="date" id="filterDate">
                            <button onclick="applyDateFilter()">Apply</button>
                        </div>
                        <div id="searchResults" class="search-results" style="display: none;"></div> <!-- Search Results Window -->
                    </div>
                    <div id="dropdownMenu" class="dropdown-menu">
                        <ul>
                            <li onclick="viewProfile()">View Profile</li>
                            <!-- <li onclick="searchChat()">Search Chat</li> -->
                            <!-- <li onclick="deleteChat()">Delete Chat</li> -->
                            <li onclick="report()">Report</li>
                        </ul>
                    </div>
                </div>
                <div class="chat-section" id="chatSection"></div>
            </div>

            <form id="chatForm" onsubmit="sendMessage(event, 'job_seeker')">
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

    <!-- Modal Structure -->
    <div id="modal" class="modal" style="display: none;"> <!-- Initially hidden -->
    <div class="modal-content">
        <span id="modalClose" class="close">&times;</span> <!-- Close icon -->
        <p id="modalMessage"></p> <!-- Message placeholder -->
        <button id="modalOkButton" class="modal-content-okay">Okay</button> <!-- OK button -->
    </div>
</div>

    <script src="../js/job_seeker_chat.js"></script>
    <script>
    function toggleDropdown() {
        const dropdownMenu = document.getElementById("dropdownMenu");
        dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
    }

    function viewProfile() {
        const employerID = "<?php echo $employerID; ?>"; // Pass the jobSeekerID from PHP to JavaScript
        window.location.href = "../Employer/visit_job_seeker.php?userID=" + employerID; // Redirect to the profile page
    }

    function toggleDateFilter() {
        const filterContainer = document.getElementById("filterContainer");
        filterContainer.style.display = filterContainer.style.display === "block" ? "none" : "block";
    }

// Close the filter menu and dropdown menu when clicking outside of them
document.addEventListener("click", function (event) {
        const filterContainer = document.getElementById("filterContainer");
        const filterIcon = document.querySelector('.filter-icon');
        
        // Check if the click was outside the filter container and the filter icon
        if (!filterContainer.contains(event.target) && !filterIcon.contains(event.target)) {
            filterContainer.style.display = "none";
        }

        // Close the dropdown menu when clicking outside of it
        const dropdownMenu = document.getElementById("dropdownMenu");
        const arrowIcon = document.querySelector('.arrow-icon');

        if (!dropdownMenu.contains(event.target) && !arrowIcon.contains(event.target)) {
            dropdownMenu.style.display = "none";
        }
    });

    function checkEnter(event) {
    if (event.key === "Enter") {
        searchChat();
    }
}

    function applyDateFilter() {
    const selectedDate = document.getElementById("filterDate").value;
    const formattedSelectedDate = formatDate(selectedDate); // Format the selected date
    const employerID = "<?php echo $employerID; ?>"; // Get jobSeekerID from PHP
    const userID = "<?php echo htmlspecialchars($userID); ?>"; // Get userID from PHP

    if (!selectedDate) {
        showModal("Please select a date to filter.");
        return;
    }

    // Make an AJAX request to filter chats by the selected date
    fetch(`../database/jobSeeker_filter_chat.php?date=${encodeURIComponent(selectedDate)}&employerID=${encodeURIComponent(employerID)}&userID=${encodeURIComponent(userID)}`)
        .then(response => response.json())
        .then(data => {
            const searchResults = document.getElementById("searchResults");
            searchResults.innerHTML = ""; // Clear current search results display
            searchResults.style.display = "none"; // Hide search results by default

            if (data.status === "error") {
                showModal(data.message); // Show error message in modal
                return; // Exit early on error
            } else if (data.status === "nextAvailableDate") {
                // Format the next available date
                const formattedNextDate = formatDate(data.nextDate);
                
                // Display no history message in search results
                const noHistoryMessage = document.createElement("div");
                noHistoryMessage.className = "message"; // Apply your message styling
                noHistoryMessage.innerHTML = `
                    <div class="message-body-no-date">
                        No chat history found for ${formattedSelectedDate}. Showing history for ${formattedNextDate}.
                    </div>
                `;
                searchResults.appendChild(noHistoryMessage); // Add no history message to results
            }

            // Populate the search results with filtered messages
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(message => {
                    const messageDiv = document.createElement("div");
                    messageDiv.className = "message"; // Apply your message styling
                    messageDiv.innerHTML = `
                        <div class="message-header">
                            <span>${message.formatted_date} ${message.formatted_time}</span>
                        </div>
                        <div class="message-body">${message.messageContents}</div>
                    `;

                    // Set a click event listener to scroll to the specific message in the chat section
                    messageDiv.addEventListener("click", () => {
                        const chatMessage = document.getElementById(`message-${message.id}`);
                        if (chatMessage) {
                            chatMessage.scrollIntoView({ behavior: "smooth", block: "center" });
                            // Optionally highlight the message for better visibility
                            chatMessage.classList.add("highlight-message");
                            setTimeout(() => chatMessage.classList.remove("highlight-message"), 1000);
                        } else {
                            alert("Message not found in chat section.");
                        }
                    });

                    searchResults.appendChild(messageDiv);
                });
                searchResults.style.display = "block"; // Show search results if there are messages
            }else if (data.status === "nextAvailableDate") {
                // Show search results if there are no messages, only showing the no history message
                searchResults.style.display = "block"; // Show the no history message as part of search results
            }

            // Close the date filter container
            toggleDateFilter(); 
        })
        .catch(error => console.error("Error filtering chat history:", error));
}


function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);

    // Get the individual parts of the date
    const day = date.getDate(); // Get the day of the month
    const month = date.toLocaleString('en-US', { month: 'long' }); // Get the full month name
    const year = date.getFullYear(); // Get the full year

    // Return the formatted date as "23 December 2024"
    return `${day} ${month} ${year}`;
}

function showModal(message) {
    const modal = document.getElementById("modal");
    const modalMessage = document.getElementById("modalMessage");
    const modalHeading = document.getElementById("modalHeading");
    const closeModal = document.getElementById("modalClose");
    const modalOkButton = document.getElementById("modalOkButton");
    const filterContainer = document.getElementById("filterContainer"); // Reference to filter container
    const searchResults = document.getElementById("searchResults"); // Reference to search results

    // Hide the filter container if it's open
    if (filterContainer.style.display === "block") {
        filterContainer.style.display = "none";
    }

    // Hide search results when showing the modal
    if (searchResults.style.display === "block") {
        searchResults.style.display = "none";
    }

    modalMessage.innerHTML = `<h2>${message}</h2>`; // Display the message inside an h2
    modal.style.display = "block"; // Show the modal

    // Close the modal when the close button or "Okay" button is clicked
    closeModal.onclick = closeModalHandler;
    modalOkButton.onclick = closeModalHandler;

    // Close the modal when clicking outside the modal content
    window.onclick = (event) => {
        if (event.target === modal) {
            closeModalHandler();
        }
    };

    function closeModalHandler() {
        modal.style.display = "none";
    }
}

function searchChat() {
    const searchQuery = document.getElementById("searchBar").value.trim();
    const employerID = "<?php echo $employerID; ?>"; // Get jobSeekerID from PHP

    if (!searchQuery) {
        showModal("Please enter a search term.");
        return;
    }

    // Make an AJAX request to search the chat history
    fetch(`../database/jobSeeker_search_chat.php?query=${encodeURIComponent(searchQuery)}&employerID=${encodeURIComponent(employerID)}`)
        .then(response => response.json())
        .then(data => {
            const searchResults = document.getElementById("searchResults");
            searchResults.innerHTML = ""; // Clear current results

            if (data.status === "error") {
                searchResults.innerHTML = `<div>${data.message}</div>`;
            } else {
                // Populate the search results window with filtered messages
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(message => {
                        const highlightedContent = message.messageContents.replace(
                            new RegExp(`(${searchQuery})`, 'gi'),  // Correct regex syntax with backticks
                            "<b>$1</b>"
                        );

                        const messageDiv = document.createElement("div");
                        messageDiv.className = "message"; // Apply your message styling
                        messageDiv.innerHTML = `
                            <div class="message-header">
                                <span>${message.formatted_date}</span>
                            </div>
                            <div class="message-body">${highlightedContent}</div>
                        `;

                        // Set a click event listener to scroll to the specific message in the chat section
                        messageDiv.addEventListener("click", () => {
                            const chatMessage = document.getElementById(`message-${message.id}`);  // Correct the id reference
                            if (chatMessage) {
                                console.log(`Found chat message with ID: ${chatMessage.id}`); // Debugging line
                                chatMessage.scrollIntoView({ behavior: "smooth", block: "center" });
                                // Optionally highlight the message for better visibility
                                chatMessage.classList.add("highlight-message");
                                setTimeout(() => chatMessage.classList.remove("highlight-message"), 1000);
                            } else {
                                console.error("Message not found in chat section:", message.id); // Debugging line
                                alert("Message not found in chat section.");
                            }
                        });

                        searchResults.appendChild(messageDiv);
                    });
                } else {
                    showModal("No messages found for the search term."); // Use showModal to inform the user
                }
            }

            // Display the results window
            searchResults.style.display = "block";
        })
        .catch(error => {
            console.error("Error searching chat history:", error);
            alert("An error occurred while searching. Please try again.");
        });
}






// Close the search results when clicking outside
document.addEventListener("click", function (event) {
    const searchResults = document.getElementById("searchResults");
    const searchBar = document.getElementById("searchBar");

    if (!searchResults.contains(event.target) && !searchBar.contains(event.target)) {
        searchResults.style.display = "none";
    }
});


function toggleClearButton() {
    const searchBar = document.getElementById("searchBar");
    const clearSearch = document.getElementById("clearSearch");
    
    // Show the "x" button if there is text in the search bar
    if (searchBar.value.trim() !== "") {
        clearSearch.style.display = "inline"; // or "block" depending on your styling
    } else {
        clearSearch.style.display = "none"; // Hide if input is empty
    }
}

function clearSearch() {
    const searchBar = document.getElementById("searchBar");
    searchBar.value = ""; // Clear the search input
    toggleClearButton(); // Update the clear button visibility
    searchResults.style.display = "none"; // Optionally hide search results
}


function jumpToMessage(messageId) {
    const chatSection = document.getElementById("chatSection");
    const messages = chatSection.getElementsByClassName("message");

    // Loop through messages to find the one with the given ID
    for (let i = 0; i < messages.length; i++) {
        // Assuming the message divs have a data-id attribute with the message ID
        if (messages[i].dataset.id == messageId) {
            messages[i].scrollIntoView({ behavior: 'smooth' }); // Smooth scroll to the message
            break;
        }
    }

    // Optionally, hide the search results
    document.getElementById("searchResults").style.display = "none";
}


function sendMessage(event, senderRole, userID) {
    event.preventDefault();
    const chatInput = document.getElementById("chatInput");
    const messageContents = chatInput.value.trim();
    
    if (!messageContents) {
        showModal("Please enter a message before sending."); // Notify user
        return; // Exit the function
    }

    // Get current timestamp
    const timestamp = new Date().toISOString();

    // Add message to the chat section with timestamp
    addMessageToChat(messageContents, senderRole, null, timestamp);
    chatInput.value = "";

    address = window.location.search 
  
    // Returns a URLSearchParams object instance 
    parameterList = new URLSearchParams(address) 
    const employerID = parameterList.get("employerID"); 

    // Send message to the server
    fetch("../database/jobSeekerChat.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `userID=${userID}&senderRole=${senderRole}&messageContents=${encodeURIComponent(messageContents)}&employerID=${employerID}&timestamp=${timestamp}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            setTimeout(() => {
                const autoResponse = senderRole === 'employer' ? 
                    "Thank you for your message. I will get back to you soon!" : 
                    "Thank you for your message. We will get back to you soon!";

                const autoResponseTimestamp = new Date().toISOString();
                addMessageToChat(autoResponse, senderRole === "employer" ? "job_seeker" : "employer", null, autoResponseTimestamp);
                location.reload()
                fetch("../database/jobSeekerChat.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `senderRole=${senderRole === "employer" ? "job_seeker" : "employer"}&messageContents=${encodeURIComponent(autoResponse)}&employerID=${employerID}&timestamp=${autoResponseTimestamp}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status !== "success") {
                        console.error(data.error);
                    }
                });
            }, 1000);
        }
    });
}

    function report() {
        const employerID = "<?php echo $employerID; ?>";
        if(employerID) {
            window.location.href = "report_form_nojobpost.php?reportedUserID=" + employerID;
        } else {
            alert("No employer selected to report.")
        }
    }

    window.onclick = function(event) {
        if (!event.target.matches('.arrow-icon') && !event.target.matches('.dropdown-menu')) {
            const dropdowns = document.getElementsByClassName("dropdown-menu");
            for (let i = 0; i < dropdowns.length; i++) {
                dropdowns[i].style.display = "none";
            }
        }
    };
    </script>
</body>
</html>

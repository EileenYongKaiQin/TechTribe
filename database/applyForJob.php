<?php
session_start();
include('../database/config.php');

// Check if user is logged in and jobPostID is provided
if (isset($_SESSION['userID']) && isset($_POST['jobPostID'])) {
    $userID = $_SESSION['userID']; // Get logged-in user ID from session
    $jobPostID = $_POST['jobPostID']; // Get jobPostID from frontend (AJAX)
    
    // Generate application ID
    $applicationID = "A" . rand(100, 999); // Example: A123
    
    // Prepare and execute the insert query
    $sql = "INSERT INTO jobApplication (applicationID, jobPostID, applicantID, applyDate, applyStatus) 
            VALUES ('$applicationID', '$jobPostID', '$userID', CURRENT_TIMESTAMP, 'Pending')";
    
    if ($con->query($sql) === TRUE) {
        echo json_encode(["message" => "Application submitted successfully!"]);
    } else {
        echo json_encode(["message" => "Error submitting application: " . $con->error]);
    }
}
?>

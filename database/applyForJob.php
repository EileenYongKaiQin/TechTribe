<?php
session_start();
include('../database/config.php');

if (isset($_SESSION['userID']) && isset($_POST['jobPostID'])) {
    $userID = $_SESSION['userID']; // Get logged-in user ID from session
    $jobPostID = $_POST['jobPostID']; // Get jobPostID from frontend (AJAX)

    $sql = "SELECT * FROM jobApplication WHERE jobPostID = '$jobPostID' AND applicantID = '$userID'";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        // User has already applied
        echo json_encode(["message" => "You applied for this job."]);
    } else {
        $applicationID = "A" . rand(100, 999); // Generate a unique application ID

        // Insert application into the database
        $sql = "INSERT INTO jobApplication (applicationID, jobPostID, applicantID, applyDate, applyStatus) 
                VALUES ('$applicationID', '$jobPostID', '$userID', CURRENT_TIMESTAMP, 'Pending')";

        if ($con->query($sql) === TRUE) {
            echo json_encode(["message" => "Application submitted successfully!"]);
        } else {
            echo json_encode(["message" => "Error submitting application: " . $con->error]);
        }
    }
} else {
    echo json_encode(["message" => "User not logged in or missing job post ID."]);
}
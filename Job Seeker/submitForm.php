<?php
// Include database configuration
include('../database/config.php');

session_start();
// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    echo "<script>alert('Session expired. Please log in again.'); window.location.href = '../login.html';</script>";
    exit();
}

// Retrieve the userID from the session
$userID = $_SESSION['userID'];

// Check if jobPostID is passed as a POST parameter
if (isset($_POST['jobPostID'])) {
    $_SESSION['jobPostID'] = $_POST['jobPostID']; // Store it in the session
}

$jobPostID = $_SESSION['jobPostID'] ?? null; // Retrieve from session

if (!$jobPostID) {
    echo "<script>alert('Job post information is missing. Please try again.'); window.location.href = 'jobseeker_dashboard.php';</script>";
    exit();
}

// Process the submitted form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $report_reason = isset($_POST["report_reason"]) ? $_POST["report_reason"] : null;
    $description = isset($_POST["description"]) ? $_POST["description"] : null;

    // Handle file uploads
    $uploadedFiles = [];
    $targetDir = "../uploads/reports/";

    // Ensure the target directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Create the directory if it doesn't exist
    }

    if (isset($_FILES["evidence"]["name"])) {
        foreach ($_FILES["evidence"]["name"] as $key => $fileName) {
            if ($_FILES["evidence"]["error"][$key] == 0) { // Check for upload errors
                $targetFile = $targetDir . basename($fileName);
                if (move_uploaded_file($_FILES["evidence"]["tmp_name"][$key], $targetFile)) {
                    $uploadedFiles[] = $targetFile; // Save file paths for storage
                } else {
                    echo "<script>alert('Failed to upload file: $fileName');</script>";
                }
            }
        }
    }

    // Join all uploaded file paths into a single string (separated by commas)
    $evidence = implode(",", $uploadedFiles);

    // Generate a new reportID
    $newReportID = '';
    $query = "SELECT reportID FROM reportPost ORDER BY reportID DESC LIMIT 1";
    $result = $con->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastReportID = $row['reportID'];
        // Extract the numeric part of the last reportID
        $lastNumber = intval(substr($lastReportID, 3));
        // Increment the number and format the new reportID
        $newReportID = 'REP' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    } else {
        // No reports exist, start from REP001
        $newReportID = 'REP001';
    }

    // Get the reported user ID dynamically
    $reportedUserQuery = $con->prepare("
        SELECT l.userID AS reportedUserID 
        FROM jobPost jp
        INNER JOIN login l ON jp.userID = l.userID
        WHERE jp.jobPostID = ?
    ");
    $reportedUserQuery->bind_param("s", $jobPostID);
    $reportedUserQuery->execute();
    $reportedUserResult = $reportedUserQuery->get_result();
    $reportedUserData = $reportedUserResult->fetch_assoc();
    $reportedUserID = $reportedUserData['reportedUserID'] ?? null;

    if (!$reportedUserID) {
        echo "<script>alert('Unable to identify the user associated with this job post. Please try again.'); history.back();</script>";
        exit();
    }

    // Insert the report into the database
    $stmt = $con->prepare("
        INSERT INTO reportPost (reportID, reason, description, evidence, reporterID, jobPostID, reportedUserID) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssssss", $newReportID, $report_reason, $description, $evidence, $userID, $jobPostID, $reportedUserID);

    if ($stmt->execute()) {
        echo "<script>alert('Report submitted successfully!'); window.location.href = 'report_form.php';</script>";
    } else {
        echo "<script>alert('Error submitting report. Please try again.'); history.back();</script>";
    }

    $stmt->close();
}

$con->close();
?>
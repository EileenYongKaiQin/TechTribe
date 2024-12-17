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
$jobPostID = null;

// Process the submitted form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reportedUserID = $_POST['reportedUserID'] ?? null;
    $report_reason = $_POST["report_reason"] ?? null;
    $description = $_POST["description"] ?? null;

    if (!$reportedUserID || !$report_reason || !$description) {
        echo "<script>alert('Missing required fields.'); history.back();</script>";
        exit();
    }
    
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

    // Insert the report into the database
    $stmt = $con->prepare("
        INSERT INTO reportPost (reportID, reason, description, evidence, reporterID, jobPostID, reportedUserID) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt) {
        // Log SQL error for debugging
        error_log("SQL Error: " . $con->error);
        echo "<script>alert('Database error. Please try again later.'); history.back();</script>";
        exit();
    }
    $stmt->bind_param("sssssss", $newReportID, $report_reason, $description, $evidence, $userID, $jobPostID, $reportedUserID);

    if ($stmt->execute()) {
        echo "<script>alert('Report submitted successfully!'); window.location.href = 'employer_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error submitting report. Please try again.'); history.back();</script>";
    }

    $stmt->close();
}

$con->close();
?>
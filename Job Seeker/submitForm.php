<?php
// Include database configuration
include('../database/config.php');

// Hardcoded placeholders for userID and jobPostID for testing
$userID = "JS001"; // Temporary user ID
$jobPostID = "JP001"; // Ensure this exists in the jobPost table

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

    // Check if the jobPostID exists in the jobPost table
    $checkJobPost = $con->prepare("SELECT jobPostID FROM jobPost WHERE jobPostID = ?");
    $checkJobPost->bind_param("s", $jobPostID);
    $checkJobPost->execute();
    $checkJobPost->store_result();

    if ($checkJobPost->num_rows > 0) {
        // Insert the report into the database
        $stmt = $con->prepare("INSERT INTO reportPost (reason, description, evidence, userID, jobPostID) 
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $report_reason, $description, $evidence, $userID, $jobPostID);

        if ($stmt->execute()) {
            echo "<script>alert('Report submitted successfully!'); window.location.href = 'report_form.php';</script>";
        } else {
            echo "<script>alert('Error submitting report. Please try again.'); history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Invalid Job Post ID. Please try again.'); history.back();</script>";
    }

    $checkJobPost->close();
}

$con->close();
?>
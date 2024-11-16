<?php
// Include database configuration
include('../database/config.php');

// Hardcoded placeholders for userID and jobPostID
$userID = "JS001"; // Temporary user ID
$jobPostID = "JP001"; // Temporary job post ID

// Process the submitted form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $report_reason = isset($_POST["report_reason"]) ? $_POST["report_reason"] : null;
    $description = isset($_POST["description"]) ? $_POST["description"] : null;

    // Handle file uploads
    $uploadedFiles = [];
    if (isset($_FILES["evidence"]) && $_FILES["evidence"]["error"][0] == 0) {
        $targetDir = "../uploads/reports/";
        foreach ($_FILES["evidence"]["name"] as $key => $fileName) {
            $targetFile = $targetDir . basename($fileName);
            if (move_uploaded_file($_FILES["evidence"]["tmp_name"][$key], $targetFile)) {
                $uploadedFiles[] = $targetFile; // Save file paths for storage
            }
        }
    }

    // Join all uploaded file paths into a single string (separated by commas)
    $evidence = implode(",", $uploadedFiles);

    // Insert the report into the database
    $stmt = $conn->prepare("INSERT INTO reportPost (reason, description, evidence, userID, jobPostID) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $report_reason, $description, $evidence, $userID, $jobPostID);

    if ($stmt->execute()) {
        echo "<script>alert('Report submitted successfully!'); window.location.href = 'report_form.php';</script>";
    } else {
        echo "<script>alert('Error submitting report. Please try again.'); history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>

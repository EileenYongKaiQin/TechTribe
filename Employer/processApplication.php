<?php
session_start();
include('../database/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['applicationID'], $_POST['action'])) {
    $applicationID = $_POST['applicationID'];
    $action = $_POST['action'];

    if ($action === 'accept' || $action === 'reject') {
        $status = ($action === 'accept') ? 'Accepted' : 'Rejected';

        $sql = "UPDATE jobApplication SET applyStatus = ? WHERE applicationID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $status, $applicationID);

        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "Application status successfully updated to '$status'.";
        } else {
            $_SESSION['flash_message'] = "Failed to update application status.";
        }

        $stmt->close();
    } elseif ($action === 'requestMore' && isset($_POST['additionalDetails'])) {
        $additionalDetails = $_POST['additionalDetails'];

        if (empty($additionalDetails)) {
            $_SESSION['flash_message'] = "Please provide additional details.";
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
            exit();
        }

        $sql = "UPDATE jobApplication SET additionalDetails = ?,applicantResponse = NULL ,applyStatus = 'Under Review' WHERE applicationID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $additionalDetails, $applicationID);

        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "Request for additional details sent successfully.";
        } else {
            $_SESSION['flash_message'] = "Failed to send the request.";
        }

        $stmt->close();
    }

    // Redirect back to the same page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>

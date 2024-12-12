<?php
include('../database/config.php');
include('../notification/notification.php');
session_start();

// Get the data from the request (JSON)
$data = json_decode(file_get_contents("php://input"), true);
// Ensure data exists
if (!isset($data['userID'], $data['comment'], $data['reportID'])) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

$userID = $data['userID'];
$comment = $data['comment']?? null;
$reportID = $data['reportID'];

// First, determine if the user is a job seeker or employer
// Select role from login table
$sql = "SELECT role FROM login WHERE userID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('s', $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $role = $user['role'];

    if ($role == 'jobSeeker') {
        // If job seeker, update jobSeeker table and reportPost table
        $updateSQL = "UPDATE jobSeeker SET warningHistory = warningHistory + 1 WHERE userID = ?";
    } elseif ($role == 'employer') {
        // If employer, update employer table and reportPost table
        $updateSQL = "UPDATE employer SET warningHistory = warningHistory + 1 WHERE userID = ?";
    } else {
        echo json_encode(['success' => false, 'message' => 'User role not recognized']);
        exit;
    }

     // Update the warningHistory
     $stmt = $con->prepare($updateSQL);
     $stmt->bind_param('s', $userID);
     if ($stmt->execute()) {
         // Update the comment in the reportPost table using reportID
         if (isset($comment) && !empty($comment)) {
             $commentSQL = "UPDATE reportPost SET comment = ? WHERE reportID = ?";
             $stmt = $con->prepare($commentSQL);
             $stmt->bind_param('ss', $comment, $reportID);
             $stmt->execute();
         }

         // Fetch reporter's name from the reportPost table
        $getReporterQuery = "SELECT reporterID, reportedUserID FROM reportPost WHERE reportID = ?";
        $stmtReporter = $con->prepare($getReporterQuery);
        $stmtReporter->bind_param("s", $reportID);
        $stmtReporter->execute();
        $resultReporter = $stmtReporter->get_result();
        
        if ($resultReporter->num_rows > 0) {
            $reporter = $resultReporter->fetch_assoc();
            $reporterID = $reporter['reporterID'];
            $reportedUserID = $reporter['reportedUserID'];
        
            if (substr($reportedUserID, 0, 2) == 'JS') {
                // Job seeker
                $getReportedUserNameQuery = "SELECT fullName FROM jobSeeker WHERE userID = ?";
            } elseif (substr($reportedUserID, 0, 2) == 'EP') {
                // Employer
                $getReportedUserNameQuery = "SELECT fullName FROM employer WHERE userID = ?";
            } else {
                // Unknown user type
                $reportedUserName = 'Unknown User';
                error_log("Unknown reportedUserID type: " . $reportedUserID);
            }

            $stmtReportedUser = $con->prepare($getReportedUserNameQuery);
            $stmtReportedUser->bind_param('s', $reportedUserID);
            $stmtReportedUser->execute();
            $resultReportedUser = $stmtReportedUser->get_result();

            if ($resultReportedUser->num_rows > 0) {
                $reportedUserName = $resultReportedUser->fetch_assoc()['fullName'];
            } else {
                error_log("No reported user name found for reportedUserID: " . $reportedUserID);
                $reportedUserName = 'Unknown User';  // Default value if name is not found
            }
        } else {
            error_log("No reporterID or reportedUserID found for reportID: " . $reportID);
            $reportedUserName = 'Unknown User';  // Default value if reporterID is not found
        }

         // Create notifications
        $notiTextReportedUser = "You have received a warning.";
        $notiTextReporter = "Your report has triggered a warning on $reportedUserName.";
        $notiTextAdmin = "You issued a warning for report $reportID.";
        $notiType = 'warning';

        // Create notifications for the reported user, reporter, and admin
        createNotification($userID, $notiTextReportedUser, $notiType);
        createNotification($reporterID, $notiTextReporter, $notiType);
        $adminID = $_SESSION['userID'];
        createNotification($adminID, $notiTextAdmin, $notiType);

         // Return success
         echo json_encode(['success' => true, 'message' => 'Warning issued successfully.']);
     } else {
         echo json_encode(['success' => false, 'message' => 'Failed to update warning history']);
     }
 
     $stmt->close();
 } else {
     echo json_encode(['success' => false, 'message' => 'User not found']);
 }
?>

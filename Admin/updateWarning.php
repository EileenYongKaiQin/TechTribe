<?php
include('../database/config.php');

// Get the data from the request (JSON)
$data = json_decode(file_get_contents("php://input"), true);

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
 
         // Return success
         echo json_encode(['success' => true]);
     } else {
         echo json_encode(['success' => false, 'message' => 'Failed to update warning history']);
     }
 
     $stmt->close();
 } else {
     echo json_encode(['success' => false, 'message' => 'User not found']);
 }
?>

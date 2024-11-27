<?php
// Start session and include database configuration
session_start();
include('../database/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['applicationID'])) {
        $applicationID = $data['applicationID']; 

        if (isset($_SESSION['userID'])) {
            $userID = $_SESSION['userID'];

            // Delete the application
            $sql = "DELETE FROM jobApplication WHERE applicationID = '$applicationID' AND applicantID = '$userID'";

            // Execute the query
            if ($con->query($sql) === TRUE) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $con->error]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
        }
    }
}
?>

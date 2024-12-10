<?php
header('Content-Type: application/json');
include('../database/config.php');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['jobPostID'], $data['userID'])) {
        echo json_encode(["success" => false, "error" => "Missing data"]);
        exit();
    }

    $jobPostID = $data['jobPostID'];
    $userID = $data['userID'];

    // Delete the saved Job from the database
    $stmt = $con->prepare("DELETE FROM savedJob WHERE jobPostID = ? AND userID = ?");
    $stmt->bind_param("ss", $jobPostID, $userID);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
$con->close();
?>

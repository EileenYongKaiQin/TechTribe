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

    // Check if the job has been saved
    $stmt = $con->prepare("SELECT * FROM savedJob WHERE jobPostID = ? AND userID = ?");
    $stmt->bind_param("ss", $jobPostID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "error" => "Job already saved."]);
        $stmt->close();
        exit();
    }
    $stmt->close();

    // Get all existing IDs
    $idResult = $con->query("SELECT savedJobID FROM savedJob");
    $existingIds = [];
    while ($row = $idResult->fetch_assoc()) {
        $existingIds[] = $row['savedJobID'];
    }

    // Extract the numeric part and store it in an array.
    $numbers = [];
    foreach ($existingIds as $savedJobID) {
        $numPart = (int)substr($savedJobID, 2);
        $numbers[] = $numPart;
    }

    sort($numbers);
    // Start checking from 1 and find the first vacancy
    $newNum = 1;
    foreach ($numbers as $num) {
        if ($num == $newNum) {
            $newNum++;
        } else if ($num > $newNum) {
            break;
        }
    }

    $nextId = 'SJ' . str_pad($newNum, 3, '0', STR_PAD_LEFT);

    $stmt = $con->prepare("INSERT INTO savedJob (savedJobID, jobPostID, userID) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nextId, $jobPostID, $userID);

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

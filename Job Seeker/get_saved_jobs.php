<?php
header("Content-Type: application/json");
include("../database/config.php");

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["jobPostIDs"], $data["userID"])) {
        echo json_encode(["success" => false, "error" => "Missing data"]);
        exit();
    }

    $jobPostIDs = $data["jobPostIDs"];
    $userID = $data["userID"];

    // Query the savedJob table to get the jobPostID saved by the user
    $placeholders = implode(",", array_fill(0, count($jobPostIDs), "?"));
    $stmt = $con->prepare("SELECT jobPostID FROM savedJob WHERE userID = ? AND jobPostID IN ($placeholders)");
    $types = str_repeat("s", count($jobPostIDs) + 1);
    $params = array_merge([$userID], $jobPostIDs);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $savedJobs = [];
    while ($row = $result->fetch_assoc()) {
        $savedJobs[] = $row["jobPostID"];
    }

    echo json_encode(["success" => true, "savedJobs" => $savedJobs]);
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
$con->close();
?>

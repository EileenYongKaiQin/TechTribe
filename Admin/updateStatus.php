<?php
include('../database/config.php');

// Decode JSON input
$data = json_decode(file_get_contents('php://input'), true);
$ids = $data['ids'];
$status = $data['status'];

if (!empty($ids) && !empty($status)) {
    // Prepare placeholders for query
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $con->prepare("UPDATE reportPost SET reportStatus = ? WHERE reportID IN ($placeholders)");

    // Dynamically bind parameters
    $types = str_repeat('s', count($ids) + 1); // 's' for string placeholders
    $stmt->bind_param($types, $status, ...$ids);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}
?>

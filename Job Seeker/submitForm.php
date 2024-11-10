<?php
session_start();
include('../database/config.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn->begin_transaction();

        // Insert report data
        $reportReason = $_POST['report_reason'];
        $description = $_POST['description'];
        $reporterId = $_SESSION['user_id'];
        $reporterType = $_SESSION['user_type'];
    

        $stmt = $conn->prepare("INSERT INTO reports (reporter_type, reporter_id,  
                                report_reason, description) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("ssssss", $reporterType, $reporterId, $reportReason, $description);
        $stmt->execute();
        
        $reportId = $conn->insert_id;

        // Handle file uploads
        if (isset($_FILES['evidence']) && !empty($_FILES['evidence']['name'][0])) {
            foreach ($_FILES['evidence']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['evidence']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileName = $_FILES['evidence']['name'][$key];
                    $fileType = $_FILES['evidence']['type'][$key];
                    $fileSize = $_FILES['evidence']['size'][$key];
                    
                    // Read file content
                    $fileContent = file_get_contents($tmp_name);
                    
                    // Insert file into database
                    $stmt = $conn->prepare("INSERT INTO report_evidence (report_id, file_name, 
                                          file_data, file_type, file_size) 
                                          VALUES (?, ?, ?, ?, ?)");
                    
                    $stmt->bind_param("isssi", $reportId, $fileName, $fileContent, 
                                    $fileType, $fileSize);
                    $stmt->execute();
                }
            }
        }

        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Report submitted successfully']);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
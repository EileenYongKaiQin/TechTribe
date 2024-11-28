<?php
include('../database/config.php');

// Check if the 'id' parameter is passed
if (isset($_GET['id'])) {
    $reportID = $_GET['id'];

    // Prepare SQL to fetch report details along with related data from other tables
    $sql = "
        SELECT 
            reportPost.reportID,
            reportPost.reason,
            reportPost.description,
            reportPost.evidence,
            reportPost.createTime,
            reportPost.reportStatus,
            reportPost.jobPostID,
            jobPost.userID AS reportedUserID,  -- The user who posted the job
            reportPost.userID AS reportUserID,  -- The user who reported the job post
            login.role AS reporterRole,
            jobSeeker.fullName AS reporterFullName,
            employer.fullName AS employerName,
            jobSeeker.userID AS jobSeekerID
        FROM reportPost
        LEFT JOIN jobPost ON jobPost.jobPostID = reportPost.jobPostID
        LEFT JOIN login ON login.userID = reportPost.userID
        LEFT JOIN jobSeeker ON jobSeeker.userID = reportPost.userID
        LEFT JOIN employer ON employer.userID = jobPost.userID  -- Join employer on jobPost.userID
        WHERE reportPost.reportID = ?
    ";

    $stmt = $con->prepare($sql);
    $stmt->bind_param('s', $reportID); // Bind the reportID parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $report = $result->fetch_assoc();

        // Determine the reporter's full name based on the user role
        $reporterName = '';
        $reportedUserName = '';

        // If the reporter is a jobSeeker, we take the jobSeeker's name
        if ($report['reporterRole'] == 'jobSeeker') {
            $reporterName = $report['reporterFullName']; // Reporter is the job seeker
            $reportedUserName = $report['employerName']; // Reported user is the employer
        } 
        // If the reporter is an employer, we take the employer's name
        else if ($report['reporterRole'] == 'employer') {
            $reporterName = $report['employerName']; // Reporter is the employer
            $reportedUserName = $report['reporterFullName']; // Reported user is the job seeker
        }

        // Return the fetched data as JSON
        echo json_encode([
            'reporterName' => $reporterName,
            'submissionDate' => $report['createTime'],
            'reason' => $report['reason'],
            'description' => $report['description'],
            'evidenceLink' => "../reports/" . htmlspecialchars($report['evidence']), // Correct file path
            'reportedUser' => $reportedUserName
        ]);
    } else {
        echo json_encode(['error' => 'Report not found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
?>

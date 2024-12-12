<?php
include('../database/config.php');

// Get the report ID from the GET request
$reportID = isset($_GET['id']) ? $_GET['id'] : null;

if ($reportID) {

    // Prepare SQL to fetch report details along with related data from other tables
    $sql = "
        SELECT 
            reportPost.reportID,
            reportPost.reason,
            reportPost.description,
            reportPost.evidence,
            reportPost.createTime,
            reportPost.reportStatus,
            reportPost.reporterID,
            reportPost.reportedUserID,
            reportPost.jobPostID,
            login.role AS reporterRole,
            jobSeeker.fullName AS jobSeekerFullName,
            employer.fullName AS employerFullName,
            reportedJobSeeker.fullName AS reportedJobSeekerFullName,
            reportedEmployer.fullName AS reportedEmployerFullName

        FROM reportPost
        LEFT JOIN login ON login.userID = reportPost.reporterID
        LEFT JOIN 
            jobSeeker ON reportPost.reporterID = jobSeeker.userID
        LEFT JOIN 
            employer ON reportPost.reporterID = employer.userID
        LEFT JOIN jobSeeker AS reportedJobSeeker ON reportPost.reportedUserID = reportedJobSeeker.userID
        LEFT JOIN employer AS reportedEmployer ON reportPost.reportedUserID = reportedEmployer.userID
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
            $reporterName = $report['jobSeekerFullName']; // Reporter is the job seeker
        } 
        // If the reporter is an employer, we take the employer's name
        else if ($report['reporterRole'] == 'employer') {
            $reporterName = $report['employerName']; // Reporter is the employer
        }

        // Determine the reported user's full name based on their role
        if ($report['reportedJobSeekerFullName']) {
            $reportedUserName = $report['reportedJobSeekerFullName']; // Reported user is a job seeker
        } else if ($report['reportedEmployerFullName']) {
            $reportedUserName = $report['reportedEmployerFullName']; // Reported user is an employer
        }

        // Return the fetched data as JSON
        echo json_encode([
            'reporterName' => $reporterName,
            'submissionDate' => $report['createTime'],
            'reason' => $report['reason'],
            'description' => $report['description'],
            'evidenceLink' => "../reports/" . htmlspecialchars($report['evidence']), // Correct file path
            'reportedUser' => $reportedUserName,
            'reportedUserID' => $report['reportedUserID'],
            'jobPostID' => $report['jobPostID']
            // 'reporterID' => $report['reporterID']
            
        ]);
    } else {
        echo json_encode(['error' => 'Report not found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
?>

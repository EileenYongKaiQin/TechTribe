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

        $reporterName = $report['reporterRole'] === 'jobSeeker' 
            ? $report['jobSeekerFullName'] 
            : $report['employerFullName'];

        // Determine the reported user's full name based on their role
        if ($report['reportedJobSeekerFullName']) {
            $reportedUserName = $report['reportedJobSeekerFullName']; // Reported user is a job seeker
        } else if ($report['reportedEmployerFullName']) {
            $reportedUserName = $report['reportedEmployerFullName']; // Reported user is an employer
        }

        $evidenceLink = !empty($report['evidence']) 
            ? "../reports/" . htmlspecialchars($report['evidence']) 
            : 'N/A';

        // Return the fetched data as JSON
        echo json_encode([
            'reporterName' => $reporterName?? 'N/A',
            'submissionDate' => $report['createTime']?? 'N/A',
            'reason' => $report['reason']?? 'N/A',
            'description' => $report['description']?? 'N/A',
            'evidenceLink' => $evidenceLink,
            'reportedUser' => $reportedUserName,
            'reportedUserID' => $report['reportedUserID']?? 'N/A',
            'jobPostID' => $report['jobPostID']?? NULL
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

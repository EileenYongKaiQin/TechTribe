<?php
// Include database configuration file
include '../database/config.php';
include 'job_seeker_header.php';

// Check if a report ID is passed via GET
if (!isset($_GET['reportID'])) {
    echo "No report ID provided.";
    exit();
}

$reportID = $_GET['reportID'];

// Fetch report details from the database
$query = $con->prepare("SELECT 
    rp.reportID, rp.description, rp.reason, rp.createTime, rp.reportStatus,
    CASE
        WHEN SUBSTRING(ru.userID, 1, 2) = 'EP' THEN ru.fullName 
        WHEN SUBSTRING(js.userID, 1, 2) = 'JS' THEN js.fullName
        ELSE 'Unknown'
    END AS reportedUserName
FROM 
    reportPost rp
LEFT JOIN 
    jobseeker js ON rp.reportedUserID = js.userID
LEFT JOIN 
    employer ru ON rp.reportedUserID = ru.userID
WHERE 
    rp.reportID = ?");
$query->bind_param("s", $reportID);
$query->execute();
$result = $query->get_result();

if ($result && $result->num_rows > 0) {
    $report = $result->fetch_assoc();
} else {
    echo "Report not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Status</title>
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .report-header {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 20px;
            border-radius: 5px;
            position: relative;
        }

        .report-header h1 {
            font-size: 24px;
            color: black;
        }

        .report-details {
            margin-top: 20px;
            padding: 20px;
            border-radius: 10px;
        }

        .report-details table {
            width: 60%;
            margin: 0 auto;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .report-details table th,
        .report-details table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #E5E7EB;
            overflow: hidden; /* Ensures content doesn't overflow */
            text-overflow: ellipsis; /* Adds ellipsis for long content */
            white-space: nowrap;
        }

        .report-details table th{
            background-color: #AAE1DE;
            color: black;
            width: 25%;
        }

        .report-details table td{
            background-color:rgb(233, 236, 235);
            color: black;
            width: 75%;
        }


        .progress-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .circle {
            width: 30px;
            height: 30px;
            background-color: #A1AEBE;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #FFFFFF;
            font-weight: bold;
        }

        .circle.active {
            background-color: #0162DD;
        }

        .line {
            height: 4px;
            flex-grow: 1;
            background-color: #A1AEBE;
        }

        .line.active {
            background-color: #0162DD;
        }


        
    </style>
</head>
<body>
    <div class="container">
        <div class="report-header">
            <h1>Report Status</h1>
        </div>

        <div class="report-details">
            <table>
                <tr>
                    <th>Reported User</th>
                    <td><?php echo htmlspecialchars($report['reportedUserName']); ?></td>
                </tr>
                <tr>
                    <th>Submission Date</th>
                    <td><?php echo htmlspecialchars($report['createTime']); ?></td>
                </tr>
                <tr>
                    <th>Reason</th>
                    <td><?php echo htmlspecialchars($report['reason']); ?></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><?php echo htmlspecialchars($report['description']); ?></td>
                </tr>
            </table>
        </div>
            <div class="progress-bar">
                <div class="progress-step">
                    <div class="circle <?php echo ($report['reportStatus'] == 'Pending' || $report['reportStatus'] == 'Under Review' || $report['reportStatus'] == 'Resolved') ? 'active' : ''; ?>">1</div>
                    <span>Pending</span>
                </div>
                <div class="line <?php echo ($report['reportStatus'] == 'Under Review' || $report['reportStatus'] == 'Resolved') ? 'active' : ''; ?>"></div>
                <div class="progress-step">
                    <div class="circle <?php echo ($report['reportStatus'] == 'Under Review' || $report['reportStatus'] == 'Resolved') ? 'active' : ''; ?>">2</div>
                    <span>Under Review</span>
                </div>
                <div class="line <?php echo ($report['reportStatus'] == 'Resolved') ? 'active' : ''; ?>"></div>
                <div class="progress-step">
                    <div class="circle <?php echo ($report['reportStatus'] == 'Resolved') ? 'active' : ''; ?>">3</div>
                    <span>Resolved</span>
                </div>
            </div>
    </div>
</body>
</html>

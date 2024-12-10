<?php
include('../database/config.php');
include('jobSeeker1.php');


if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

$userID = $_SESSION['userID'];

// Query the user's saved positions
$sql = "SELECT jp.* 
        FROM savedJob sj 
        JOIN jobPost jp ON sj.jobPostID = jp.jobPostID 
        WHERE sj.userID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();

$jobs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Jobs</title>
    <link rel="stylesheet" type="text/css" href="../css/view_job.css">
    <style>
        .main-content {
            display: flex;
            justify-content: center;  
            margin-top: 0px; 
            padding: 20px;           
        }

        .content h1{        
            margin-left: 190px; 
            text-align: center;
            margin-top:-20px;
     
            font-weight: 700;
        }
    </style>
</head>
<body>
<div class="content">
    <h1>Saved Job Listing</h1>
</div>
    <!-- Main Content -->
    <div class="main-content">
        <div class="rectangle">
            <?php
            // Check if there are any jobs to display
            if (count($jobs) > 0) {
                foreach ($jobs as $job) {
            ?>

        <div class="job-card" data-id="<?php echo $job['jobPostID']; ?>">
                <div class="job-header">
                    <h3><?php echo htmlspecialchars($job['jobTitle']); ?></h3>
                </div>
                <div class="job-details">
                    <p class="salary">
                        <span class="time-label <?php echo ($job['workingHour'] === 'Day Shift') ? 'day' : 'night'; ?>">
                            <?php echo htmlspecialchars($job['workingHour']); ?>
                        </span>
                        Salary: RM <?php echo number_format($job['salary'], 2); ?> / hour
                    </p>
                    <p class="date">
                        <?php 
                            $startDate = date("d/m/Y", strtotime($job['startDate']));
                            $endDate = date("d/m/Y", strtotime($job['endDate']));
                            echo $startDate . " - " . $endDate;
                        ?>
                    </p>
                    <p class="location">
                        <img src="../images/MapPin.png" alt="Location Icon" class="map-pin"> <?php echo htmlspecialchars($job['location']); ?>
                    </p>

                    <div class="job-details-buttons">
                        <button class="view-details-btn" onclick="location.href='manageJob.php?jobPostID=<?php echo $job['jobPostID']; ?>'">View Details</button>

                    </div>
                </div>
            </div>

            <?php
                }
            } else {
                echo "<div class='no-jobs-container'><p>No saved jobs found.</p></div>";
            }
            ?>
        </div>
    </div>
   
</body>
</html>

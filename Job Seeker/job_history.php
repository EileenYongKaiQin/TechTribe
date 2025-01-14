<?php
    include('../database/config.php');
    include('job_seeker_header.php');

    $sql = "SELECT h.historyID, jp.jobTitle, jp.salary, jp.location, jp.startDate, jp.endDate, jp.workingHour, jp.venue, jp.workingTimeStart, jp.workingTimeEnd, COALESCE(jr.rating, 0) AS isRated
            FROM jobHistory h
            JOIN jobPost jp ON h.jobPostID = jp.jobPostID
            LEFT JOIN jobRating jr ON h.historyID = jr.historyID
            WHERE h.jobSeekerID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    $hasJobHistory = $result->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job History</title>
    <link rel="stylesheet" href="../css/job_history.css">
</head>
<body>
    <div class="content">
        <h1>Job History</h1>
    </div>
    <div class="main-content">
        <div class="rectangle">
            <?php if($hasJobHistory): ?>
                <?php while($row = $result->fetch_assoc()) : ?>
                <div class="job-card">
                    <div class="job-header">
                        <h3><?php echo htmlspecialchars($row['jobTitle']) ?></h3>
                    </div>
                    <div class="job-details">
                        <p class="time">
                            <span class="time-label <?php echo ($job['workingHour'] === 'Day Shift')? 'day' : 'night';?>">
                                <?php echo htmlspecialchars($row['workingHour']); ?>
                            </span>
                            <?php
                                $workingTimeStart = date("h:i A",strtotime($row['workingTimeStart']));
                                $workingTimeEnd = date("h:i A", strtotime($row['workingTimeEnd']));
                                echo $workingTimeStart . " - " . $workingTimeEnd;
                            ?>
                    </p>

                    <p class="date">
                        <?php
                            $startDate = date("d/m/Y", strtotime($row['startDate']));
                            $endDate = date("d/m/Y", strtotime($row['endDate']));
                            echo $startDate . " - " . $endDate;
                        ?>
                    </p>

                    <p class="location">
                        <img src="../images/MapPin.png" alt="Location Icon" class="map-pin"> 
                        <?php echo htmlspecialchars($row['venue']); ?>, <?php echo htmlspecialchars($row['location']); ?>
                    </p>

                    <div class="salary-line"></div>
                        <!-- Salary -->
                        <p class="salary">
                            Salary: RM <?php echo number_format($row['salary'], 2); ?> / hour
                        </p>
                    </div>

                    <div class="rating-button">
                        <?php if ($row['isRated'] > 0): ?>
                            <!-- Already Rated -->
                            <button class="view-details-btn rated" disabled>Rated</button>
                        <?php else: ?>
                            <!-- Not Yet Rated -->
                            <button class="view-details-btn" onclick="location.href='rateJob.php?historyID=<?php echo $row['historyID']; ?>'">Rate Now</button>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endwhile; ?>
            <?php else: ?>
                <div class="no-history-container">
                    <p>No Job History Found</p>
                </div>
            <?php endif; ?>
            </div>     
        </div>
</body>
</html>
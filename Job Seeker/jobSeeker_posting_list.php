<?php 
    include('../database/config.php');
    include('jobSeeker1.php'); 

    // Fetch job posts where endDate is greater than or equal to the current date
    $sql = "SELECT * FROM jobPost WHERE endDate >= CURDATE()";
    $result = $con->query($sql);

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
    <title>FlexMatch</title>
    <style>
        .apply-header {
            margin: 20px auto;
            text-align: center;
            font-size: 32px;
        }

        .job-card {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
            text-align: center;
        }

        .job-card h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        .job-card p {
            font-size: 16px;
            color: #555;
            margin: 10px 0;
        }

        .apply-btn {
            padding: 10px 20px;
            background-color: #216ce7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .apply-btn:hover {
            background-color: #1a59d4;
        }
    </style>
</head>
<body>
    <h1 class="apply-header">Apply For Jobs</h1>

    <?php
    // Check if there are any jobs to display
    if (count($jobs) > 0) {
        // Loop through each job and display the job details dynamically
        foreach ($jobs as $job) {
    ?>
        <div class="job-card">
            <h2><?php echo htmlspecialchars($job['jobTitle']); ?></h2>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
            <p><strong>Salary:</strong> RM <?php echo number_format($job['salary'], 2); ?> / hour</p>
            <p><strong>End Date:</strong> <?php echo date("F j, Y", strtotime($job['endDate'])); ?></p>
            <p><strong>Job Description:</strong> <?php echo nl2br(htmlspecialchars($job['jobDescription'])); ?></p>
            <p><strong>Requirements:</strong> <?php echo nl2br(htmlspecialchars($job['jobRequirement'])); ?></p>
            <button class="apply-btn" onclick="location.href='manageJob.php?jobPostID=<?php echo $job['jobPostID']; ?>'">Apply</button>
        </div>
    <?php
        }
    } else {
        // If no jobs are found, display a message
        echo "<p>No available job posts at the moment.</p>";
    }
    ?>
</body>
</html>

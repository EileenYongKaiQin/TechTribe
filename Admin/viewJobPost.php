<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Job Post</title>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/admin.css">

    <style>
        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 10px auto;
            padding: 20px;
            max-width: 1200px;
        }

        .content h1 {
            font-weight: bold;
        }

        .job-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            text-align: left;
            line-height: 2em;
        }

        .job-details p {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .btn-back {
            position: absolute;
            top: 120px;
            right: 150px;
            width: 99px;
            height: 43px;
            margin: 20px 0;
            text-decoration: none;
            background-color: #aae1de;
            color: #000;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 50px;
            transition: background 0.3s ease;
        }

        .btn-back:hover {
            background-color: #8dcbc8;
        }
    </style>
</head>
<body>
    <?php
        include('../database/config.php');
        include('admin.php');

        // Get the jobPostID from the URL
        $jobPostID = isset($_GET['jobPostID']) ? $_GET['jobPostID'] : '';

        if (empty($jobPostID)) {
            echo "Job post not found!";
            exit;
        }
        
        // Fetch the job details
        $sql = "SELECT * FROM jobPost WHERE jobPostID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('s', $jobPostID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $job = $result->fetch_assoc();
        } else {
            echo "<div class='content'><p>Job not found!</p></div>";
            exit;
        }
    ?>

    <div class="content">
        <h1>Job Posting</h1>
        <a href="reviewReport.php" class="btn-back">Back</a>

        <div class="job-details">
            <p><strong>Job Title:</strong> <?php echo htmlspecialchars($job['jobTitle']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
            <p><strong>Salary:</strong> RM <?php echo number_format($job['salary'], 2); ?> / hour</p>
            <p><strong>Job Description:</strong> <?php echo nl2br(htmlspecialchars($job['jobDescription'])); ?></p>
            <p><strong>Requirements:</strong> <?php echo nl2br(htmlspecialchars($job['jobRequirement'])); ?></p>
            <p><strong>Working Hours:</strong> <?php echo htmlspecialchars($job['workingHour']); ?></p>
        </div>
    </div>
</body>
</html>

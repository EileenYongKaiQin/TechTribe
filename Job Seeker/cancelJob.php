<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlexMatch</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/manageJob.css">
   
</head>
<body>
    <?php 
        include('../database/config.php');
        include('job_seeker_header.php'); 

        $jobPostID = isset($_GET['jobPostID']) ? $_GET['jobPostID'] : '';

        $sql = "SELECT *
        FROM jobApplication ja
        JOIN jobPost jp ON ja.jobPostID = jp.jobPostID
        WHERE ja.applicantID = '{$_SESSION['userID']}' AND ja.jobPostID = '$jobPostID'";

        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            $job = $result->fetch_assoc();
            $jobTitle = $job['jobTitle'];
            $location = $job['location'];
            $salary = $job['salary'];
            $jobDescription = $job['jobDescription'];
            $jobRequirement = $job['jobRequirement'];
            $workingHour = $job['workingHour'];
            $venue = $job['venue'];
            $startDate = $job['startDate'];
            $endDate = $job['endDate'];
            $workingTimeStart = $job['workingTimeStart'];
            $workingTimeEnd = $job['workingTimeEnd'];
            $language = $job['language'];
            $race = $job['race'];
            $applyStatus = $job['applyStatus'];
        } else {
            echo "No such application found.";
            exit;
        }
    ?>

     <!-- Content -->
     <div class="content">
        <a href="my_application.php" id="back-btn">Back</a>
        <h1>Detail Job Posting</h1>

        <div class="jobDescription">
            <div class="section-title">
                <img src="../images/description.png" alt="icon"> <!-- Icon on the left -->
                <h2>Description</h2>
            </div>
            <p><strong>Job Title:</strong> <?php echo $jobTitle; ?></p>
            <p><strong>Job Description:</strong> <?php echo nl2br(htmlspecialchars($jobDescription)); ?></p>
            <p><strong>Venue:</strong> <?php echo htmlspecialchars($venue); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($location); ?></p>
            <p><strong>Salary:</strong> RM <?php echo number_format($salary, 2); ?> / hour</p>

            <div class="divider"></div>

            <div class="section-title">
                <img src="../images/duration.png" alt="icon"> <!-- Icon on the left -->
                <h2>Duration & Time</h2>
            </div>
            <p><strong>Job Duration:</strong> <?php echo htmlspecialchars($startDate) . " - " . htmlspecialchars($endDate); ?></p>
            <p><strong>Working Time:</strong> 
                <?php 
                    echo date("h:i A", strtotime($workingTimeStart)) . " - " . date("h:i A", strtotime($workingTimeEnd)); 
                ?>
            </p>
            <p><strong>Working Hours:</strong> <?php echo htmlspecialchars($workingHour); ?></p>

            <div class="divider"></div>

            <div class="section-title">
                <img src="../images/requirement.png" alt="icon"> <!-- Icon on the left -->
                <h2>Requirement</h2>
            </div>
            <p><strong>Language Requirement:</strong> <?php echo htmlspecialchars($language); ?></p>
            <p><strong>Race Preference:</strong> <?php echo htmlspecialchars($race); ?></p>
            <p><strong>Requirements:</strong> <?php echo nl2br(htmlspecialchars($jobRequirement)); ?></p>
        </div>
    <?php if (!in_array($applyStatus, ['Accepted', 'Rejected'])) { ?>
        <!-- Cancel Button -->
        <button id="cancelButton">Cancel Application</button>
    <?php } ?>
    </div>

    <script>
        document.getElementById('cancelButton').addEventListener('click', function() {
            var applicationID = "<?php echo htmlspecialchars($job['applicationID']); ?>";

            // Show confirmation alert
            Swal.fire({
                title: "Are you sure you want to cancel your application?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('cancelJobAction.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            applicationID: applicationID 
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Cancelled!', 'Your application has been cancelled.', 'success')
                            .then(() => {
                                window.location.href = 'my_application.php'; 
                            });
                        } else {
                            Swal.fire('Error', 'Failed to cancel the application. Please try again later.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Something went wrong. Please try again later.', 'error');
                    });
                }
            });
        });
    </script>
</body>
</html>

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
        include('jobSeeker1.php'); 

        $jobPostID = isset($_GET['jobPostID']) ? $_GET['jobPostID'] : '';

        // Fetch job details from the database
        $sql = "SELECT * FROM jobPost WHERE jobPostID = '$jobPostID'";
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
        } else {
            echo "<p>Job not found!</p>";
            exit;
        }

        // Check if the user has already applied for the job
        $sqlCheck = "SELECT * FROM jobApplication WHERE jobPostID = '$jobPostID' AND applicantID = '$userID'";
        $resultCheck = $con->query($sqlCheck);

        $showApplyButton = ($resultCheck->num_rows === 0); // true if user has not applied
    ?>

    <!-- Content -->
    <div class="content">
        <a href="jobSeeker_posting_list.php" id="back-btn">Back</a>
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

        <?php if ($showApplyButton) { ?>
            <button id="applyButton" data-job-id="<?php echo $job['jobPostID']; ?>">Apply</button>
        <?php } else { ?>
            <p class="applied-message">You have already applied for this job.</p>
        <?php } ?>
    </div>

    <script>
        document.getElementById('applyButton')?.addEventListener('click', function() {
            var jobPostID = this.getAttribute('data-job-id');

            Swal.fire({
                title: "Are you sure you want to apply for this job?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request
                    var formData = new FormData();
                    formData.append('jobPostID', jobPostID);

                    fetch('../database/applyForJob.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                    title: 'Success!',
                    text: data.message || 'Application submitted successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Reload the page to reflect the changes
                    window.location.reload();
                });
            })
                    
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Failed to apply for the job. Please try again.', 'error');
                    });
                } else {
                    Swal.fire('Cancelled', 'Application cancelled.', 'info');
                }
            });
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlexMatch</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }

        /* Centered container */
        .content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            margin-left: 250px;
        }

        .content h1 {
            margin-top: -15px;
            margin-bottom: 15px;
            font-size: 2em;
            text-align: center;
            color: #333;
        }

        /* Job Details Card */
        .jobDescription {
            width: 90%;
            max-width: 700px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
            line-height: 1.6;
            color: #333;
            text-align: left;
        }

        .jobDescription p {
            font-size: 18px;
            margin: 10px 0;
        }

        .jobDescription p strong {
            font-size: 20px;
            color: #000;
        }

        /* Buttons */
        #applyButton, #back-btn {
            display: inline-block;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #000;
            text-decoration: none;
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        #applyButton {
            background-color: #BEC6BF;
            color: #fff;
            margin-top: 20px;
        }

        #applyButton:hover {
            background-color: #8EFAAB;
            color: #000;
        }

        #back-btn {
            position: absolute;
            /* top: 10px; */
            right: 60px;
            background-color: #AAE1DE;
        }

        #back-btn:hover {
            background-color: #8DCBC8;
        }

        /* Alert Styling */
        .applied-message {
            font-size: 1.2em;
            font-weight: bold;
            text-align: center;
            color: #4caf50;
        }
    </style>
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
            <p><strong>Job Title:</strong> <?php echo $jobTitle; ?></p>
            <p><strong>Venue:</strong> <?php echo htmlspecialchars($venue); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($location); ?></p>
            <p><strong>Salary:</strong> RM <?php echo number_format($salary, 2); ?> / hour</p>
            <p><strong>Job Duration:</strong> <?php echo htmlspecialchars($startDate) . " - " . htmlspecialchars($endDate); ?></p>
            <p><strong>Working Time:</strong> 
                <?php 
                    echo date("h:i A", strtotime($workingTimeStart)) . " - " . date("h:i A", strtotime($workingTimeEnd)); 
                ?>
            </p>
            <p><strong>Working Hours:</strong> <?php echo htmlspecialchars($workingHour); ?></p>
            <p><strong>Language Requirement:</strong> <?php echo htmlspecialchars($language); ?></p>
            <p><strong>Race Preference:</strong> <?php echo htmlspecialchars($race); ?></p>
            <p><strong>Job Description:</strong> <?php echo nl2br(htmlspecialchars($jobDescription)); ?></p>
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
                confirmButtonText: "Yes, apply",
                cancelButtonText: "No, cancel"
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
                        Swal.fire('Success!', data.message || 'Application submitted successfully!', 'success');
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

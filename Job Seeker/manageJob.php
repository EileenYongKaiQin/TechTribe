<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlexMatch</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    
    <style>
        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: 250px; /* Adjusted for sidebar offset */
            padding: 20px;
         }

        .content h1{
            text-align: center;
            margin-top:-20px;
            margin-bottom:10px;
            font-weight: 700;
        }

        .jobDescription{
            margin: 0 auto;
            padding: 40px 60px 20px 60px;
            width: 80%;
            max-width: 700px;
            height: auto;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.25);
        }

        .jobDescription p {
            font-size: 20px;
            text-align: left;
            margin-bottom: 5px; 
        }

        .jobDescription p strong{
            font-size: 25px;
        }

        #applyButton{
            display: block;
            margin: 0 auto;
            padding: 20px 40px;
            width: 180px;
            height: 58px;
            background: #BEC6BF;
            color: #FFFFFF;
            font-family: 'Inter', sans-serif;
            font-size: 18px;
            font-weight: 700;
            border: none;
            border-radius: 50px;
            text-align: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.25);
            cursor: pointer;
            line-height: 1;
        }

        #applyButton:hover {
        background: #8EFAAB;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.25);
        border-radius: 50px;
        color: #000000; /* Black text color */
        text-align: center;
        }

        /* Back Button */
        #back-btn {
            position: absolute;
            top: 110px;
            right: 300px;
            width: 99px;
            height: 43px;
            background: #AAE1DE;
            border-radius: 50px;
            text-align: center;
            line-height: 43px;
            font-size: 16px;
            font-weight: 700;
            color: #000000;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        #back-btn:hover {
            background: #8DCBC8;
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
                // Fetch job details
                $job = $result->fetch_assoc();
                $jobTitle = $job['jobTitle'];
                $location = $job['location'];
                $salary = $job['salary'];
                $jobDescription = $job['jobDescription'];
                $jobRequirement = $job['jobRequirement'];
                $workingHour = $job['workingHour'];
            } else {
                echo "Job not found!";
                exit;
            }

            // Check if the user has already applied for the job
            $sqlCheck = "SELECT * FROM jobApplication WHERE jobPostID = '$jobPostID' AND applicantID = '$userID'";
            $resultCheck = $con->query($sqlCheck);

            // Display Apply button only if the user hasn't applied yet
            $showApplyButton = ($resultCheck->num_rows === 0); // true if user has not applied
    
    ?>
     <!-- Main content -->
     <div class="content">
        <h1>Detail Job Posting</h1>
        <a href="jobSeeker_posting_list.php" id="back-btn">Back</a>
    
    <div class="jobDescription">
        <p><strong>Job Title:</strong> <?php echo $jobTitle; ?></p><br>
        <p><strong>Location:</strong> <?php echo $location; ?></p><br>
        <p><strong>Salary:</strong> RM <?php echo number_format($salary, 2); ?> / hour</p><br>
        <p><strong>Job Description:</strong> <?php echo $jobDescription; ?></p><br>
        <p><strong>Requirements:</strong> <?php echo $jobRequirement; ?></p><br>
        <p><strong>Working Hours:</strong> <?php echo $workingHour; ?></p><br>
    </div>
    <?php if ($showApplyButton) { ?>
    <button id="applyButton" data-job-id="<?php echo $job['jobPostID']; ?>">Apply</button>
    <?php } else { ?>
        <p style="font-size: 30px;">You applied for this job.</p>
    <?php } ?>
  </div>

 <script>
       // Handle button click to apply for the job
    document.getElementById('applyButton').addEventListener('click', function() {
        var jobPostID = this.getAttribute('data-job-id');

        Swal.fire({
            title: "Are you sure you want to apply for the job?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, apply",
            cancelButtonText: "No, cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                // Create FormData object
                var formData = new FormData();
                formData.append('jobPostID', jobPostID); // Send the job post ID to the backend

                // Make an AJAX request to submit the application
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
                    Swal.fire('Error', 'Failed to apply for the job. Please try again later.', 'error');
                });
            } else {
                Swal.fire('Cancelled', 'Application cancelled.', 'info');
            }
        });
    });

 </script>

</body>
</html>

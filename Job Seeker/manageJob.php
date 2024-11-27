<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlexMatch</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .jobDescription{
            margin: 30px 500px;
            padding: 50px;
            background-color: white;
            border-radius: 20px;
        }

        .jobDescription p {
            padding: 20px;
            font-size: 20px;
            float: left;
        }

        .jobDescription p strong{
            font-size: 25px;
        }

        #applyButton{
            padding:15px 20px;
            float: right;
            margin: 30px 500px;
            border-radius: 0.5rem;
            color: white;
            background: #216ce7;
            border: 2px solid rgb(243, 243, 243);
            letter-spacing: 3px;
        }
        
    </style>
</head>
<body>
    <?php 
        include('../database/config.php');
        include('jobSeeker1.php'); 

        $jobPostID = isset($_GET['jobPostID']) ? $_GET['jobPostID'] : '';

           // Fetch job details from the database
          
           $sql = "SELECT * FROM jobPost WHERE jobPostID = '$jobPostID'"; // Example jobPostID, adjust dynamically
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
    
    ?>
     <!-- Main content -->
     <div class="content">
        <h1>APPLY JOB!</h1>
    
    <div class="jobDescription">
        <p><strong>Job Title:</strong> <?php echo $jobTitle; ?></p><br>
        <p><strong>Location:</strong> <?php echo $location; ?></p><br>
        <p><strong>Salary:</strong> RM <?php echo number_format($salary, 2); ?> / hour</p><br>
        <p><strong>Job Description:</strong> <?php echo $jobDescription; ?></p><br>
        <p><strong>Requirements:</strong> <?php echo $jobRequirement; ?></p><br>
        <p><strong>Working Hours:</strong> <?php echo $workingHour; ?></p><br>
    </div>
    <button id="applyButton" data-job-id="<?php echo $job['jobPostID']; ?>">Apply</button>
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

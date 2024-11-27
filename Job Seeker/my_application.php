<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    
</style>

<body>
<?php include('jobSeeker1.php'); ?>

    
    <div class="container">
        <h1 style="padding: 20px">My Applications</h1>

        <?php
        // Assuming user is logged in and we have access to $userID
        if (isset($_SESSION['userID'])) {
            $userID = $_SESSION['userID'];

            // Connect to the database and fetch job applications for the logged-in user
            include('../database/config.php');  // Include database connection

            $sql = "SELECT ja.applicationID, ja.jobPostID, jp.jobTitle, ja.applyDate, ja.applyStatus 
                    FROM jobApplication ja 
                    JOIN jobPost jp ON ja.jobPostID = jp.jobPostID 
                    WHERE ja.applicantID = '$userID'";

            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <div class='card' style='margin: 20px;'>
                        <div class='card-body'>
                            <h3 class='card-title'>{$row['jobTitle']}</h3>
                            <p><strong>Application ID: </strong>{$row['applicationID']}</p>
                            <p><strong>Applied On:</strong> {$row['applyDate']}</p>
                            <p><strong>Status:</strong> {$row['applyStatus']}</p>
                            <a href='cancelJob.php?jobPostID={$row['jobPostID']}' class='btn btn-primary'>View Job</a>
                        </div>
                    </div>";
                }
            } else {
                echo "<p>You have not applied for any jobs yet.</p>";
            }
        } else {
            echo "<p>Please log in to view your applications.</p>";
        }
        ?>
    </div>

  
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlexMatch</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
</head>
<style>
    .jobDescription{
        margin: 30px 500px;
        padding: 60px;
        background-color: white;
        border-radius: 20px;
    }

    .jobDescription p {
        padding: 20px 50px;
        font-size: 20px;
        float: left;
        letter-spacing: 0.75px;
    }

    .jobDescription p strong{
        font-size: 25px;
    }

    #cancelButton{
        float: right;
        padding:15px 20px;
        margin: 30px 500px;
        border-radius: 0.5rem;
        color: white;
        background: #216ce7;
        border: 2px solid rgb(243, 243, 243);
    }
</style>
<body>
    <?php 
        include('../database/config.php');
        include('jobSeeker1.php'); 

        $jobPostID = isset($_GET['jobPostID']) ? $_GET['jobPostID'] : '';

        $sql = "SELECT ja.applicationID, ja.jobPostID, jp.jobTitle, ja.applyDate, ja.applyStatus, jp.location, jp.salary, jp.workingHour
        FROM jobApplication ja
        JOIN jobPost jp ON ja.jobPostID = jp.jobPostID
        WHERE ja.applicantID = '{$_SESSION['userID']}' AND ja.jobPostID = '$jobPostID'";

        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            $application = $result->fetch_assoc();
        } else {
            echo "No such application found.";
            exit;
        }
    ?>

    <div class="content">
        <h1>My Application</h1>

        <!-- Display job details -->
        <div class="jobDescription">
            <p><strong>Job Title: </strong><?php echo $application['jobTitle']; ?></p><br>
            <p><strong>Application ID: </strong><?php echo $application['applicationID']; ?></p><br>
            <p><strong>Applied On: </strong> <?php echo $application['applyDate']; ?></p><br>
            <p><strong>Status: </strong> <?php echo $application['applyStatus']; ?></p><br>
            <p><strong>Location: </strong> <?php echo $application['location']; ?></p><br>
            <p><strong>Salary: </strong> <?php echo $application['salary']; ?></p><br>
            <p><strong>Working Hour: </strong> <?php echo $application['workingHour']; ?></p><br>
        </div>
    <?php if (!in_array($application['applyStatus'], ['Accepted', 'Rejected'])) { ?>
        <!-- Cancel Button -->
        <button id="cancelButton">Cancel Application</button>
    <?php } ?>
    </div>

    <script>
        document.getElementById('cancelButton').addEventListener('click', function() {
            var applicationID = "<?php echo $application['applicationID']; ?>";

            // Show confirmation alert
            Swal.fire({
                title: "Are you sure you want to cancel your application?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, cancel it",
                cancelButtonText: "No, keep it"
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

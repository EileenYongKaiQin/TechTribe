<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlexMatch</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        /* Container for the content */
        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            margin: 0 auto;
            width: 50%;
            max-width: 600px;
        }

        /* Title styling */
        .my-application {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        /* Job Description Container */
        .jobDescription {
            margin: 20px auto;
            padding: 30px;
            width: 90%;
            max-width: 700px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .jobDescription p {
            font-size: 18px;
            line-height: 1.6;
            color: #555;
            margin-bottom: 10px;
        }

        .jobDescription p strong {
            font-size: 20px;
            color: #000;
        }

        /* Cancel Button Styling */
        #cancelButton {
            display: inline-block;
            text-align: center;
            margin: 20px auto;
            padding: 12px 20px;
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            background-color: #e74c3c;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        #cancelButton:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .jobDescription {
                padding: 20px;
                width: 95%;
            }

            .jobDescription p {
                font-size: 16px;
            }

            .my-application {
                font-size: 28px;
            }

            #cancelButton {
                padding: 10px 18px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .my-application {
                font-size: 24px;
            }

            .jobDescription p strong {
                font-size: 18px;
            }

            #cancelButton {
                width: 100%;
                padding: 10px;
                font-size: 16px;
            }
        }
    </style>
</head>
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
        <h1 class="my-application">My Application</h1>

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

<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer - Job Applications</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/response_application.css">
</head>
<body>
    <?php include('employer1.php');?>

    <div class="container">
        <h1 class="my-4">Job Applications</h1>

        <?php
        $employerID = $_SESSION['userID'];

        include('../database/config.php');

        // Fetch job applications related to the employer's job posts
        if (isset($_SESSION['userID'])) {
            $userID = $_SESSION['userID'];
        
            include('../database/config.php');
        
            $sql = "SELECT ja.applicationID, ja.jobPostID, jp.jobTitle, ja.additionalDetails, ja.applyDate, ja.applyStatus, jp.location, ja.applicantResponse, u.userID AS applicantID, u.fullName AS applicantName
                    FROM jobApplication ja 
                    JOIN jobPost jp ON ja.jobPostID = jp.jobPostID 
                    JOIN jobSeeker u ON ja.applicantID = u.userID
                    WHERE jp.userID = ?";
        
            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $userID);
            $stmt->execute();
            $result = $stmt->get_result();

            $currentStatus = "Pending"; 
            // Define the steps for the status tracker
            $steps = ['New Application', 'Under Review', 'Accepted/Rejected'];

            // Helper function to determine the step progress
            function getStepClass($currentStatus, $step) {
                $statuses = ['Pending', 'Under Review', 'Accepted', 'Rejected'];
                $currentIndex = array_search($currentStatus, $statuses);
                $stepIndex = array_search($step, $statuses);

                if ($step === 'Accepted/Rejected') {
                    return ($currentStatus === 'Accepted') ? 'accepted' : ($currentStatus === 'Rejected' ? 'rejected' : '');
                }
                
                if ($stepIndex < $currentIndex) {
                    return 'completed'; // Step already passed
                } elseif ($stepIndex == $currentIndex) {
                    return 'current'; // Current step
                } else {
                    return ''; // Upcoming steps
                }
            }
        
            $stmt->close();
            
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userID = $row['applicantID'];
                $status = $row['applyStatus']; 

                echo "
                <div class='card'>
                    <div class='card-body'>
                     <div class='status-tracker'>";
                     foreach ($steps as $step) { 
                        $stepClass = getStepClass($status, $step);
                        echo "<div class='status-step {$stepClass}'>
                                <div class='circle'>" . (array_search($step, $steps) + 1) . "</div>
                                <div class='label'>" . htmlspecialchars($step) . "</div>
                              </div>";
                    }
                        echo "    </div>
                        <h3 class='card-title'>{$row['jobTitle']}</h3>
                        <p><strong>Applicant Name:</strong><a href='visit_job_seeker.php?userID={$userID}'> {$row['applicantName']}</a></p>
                        <p><strong>Location:</strong> {$row['location']}</p>
                        <p><strong>Application ID:</strong> {$row['applicationID']}</p>
                        <p><strong>Applied On:</strong> {$row['applyDate']}</p>
                        <p><strong>Status:</strong> 
                        <span class='status-label {$row['applyStatus']}'>{$row['applyStatus']}</span>
                        </p>";

                if ($row['applyStatus'] === 'Under Review') {
                    echo "
                    <div class='alert alert-warning'>
                        <p><strong>Request More:</strong> " . ($row['additionalDetails'] ?: 'No message provided yet.') . "</p>
                    </div>";

                    if (!empty($row['applicantResponse'])) {
                        echo "
                        <div class='alert alert-info'>
                            <p><strong>Job Seeker Response:</strong> " . ($row['applicantResponse'] ?: 'No message provided yet.') . "</p>
                        </div>";
                    }
                }

                if (in_array($row['applyStatus'], ['Pending', 'Under Review'])) {
                    echo "
                    <div class='d-flex gap-2'>
                        <button class='btn btn-success' onclick='confirmAction(\"accept\", \"{$row['applicationID']}\")'>Accept</button>
                        <button class='btn btn-danger' onclick='confirmAction(\"reject\", \"{$row['applicationID']}\")'>Reject</button>
                    </div>

                    <div id='details-{$row['applicationID']}' class='collapse'>
                        <textarea id='message-{$row['applicationID']}' name='additionalDetails' placeholder='Enter your message for the applicant'></textarea>
                        <button type='button' style='background-color:#5a6268; color:white;' class='btn btn-primary' onclick='sendRequestMore(\"{$row['applicationID']}\")'>Request More</button>
                    </div>";
                }

                echo "</div></div>";
            }
        } else {
            echo "<p class='alert alert-info'>No job applications found.</p>";
        }
        ?>
    </div>

    <script>
        function confirmAction(action, applicationID) {
            const actionText = action === 'accept' ? 'Accept' : 'Reject';

            Swal.fire({
                title: `Are you sure you want to ${actionText} this application?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: action === 'accept' ? '#28a745' : '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: `${actionText}`
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'processApplication.php';

                    const appIdInput = document.createElement('input');
                    appIdInput.type = 'hidden';
                    appIdInput.name = 'applicationID';
                    appIdInput.value = applicationID;
                    form.appendChild(appIdInput);

                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = action;
                    form.appendChild(actionInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function sendRequestMore(applicationID) {
            const message = document.getElementById(`message-${applicationID}`).value.trim();

            if (!message) {
                Swal.fire('Error', 'Please enter a message before sending the request.', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('applicationID', applicationID);
            formData.append('additionalDetails', message);
            formData.append('action', 'requestMore');

            fetch('processApplication.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                Swal.fire('Success', 'Request sent successfully!', 'success').then(() => {
            // Reload the page to reflect the changes
            window.location.reload();
        });
                
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An unexpected error occurred. Please try again.', 'error');
            });
        }

        
    </script>
</body>
</html>

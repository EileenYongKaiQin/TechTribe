<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer - Job Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
</head>
<style>
    .status-label {
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .Accepted {
        color: #fff;
        background-color: #28a745; /* Green */
    }

    .Rejected {
        color: #fff;
        background-color: #dc3545; /* Red */
    }
</style>
<body>
    <?php include('employerNew.php');?>


    <div class="container">
        <h1 class="my-4">Job Applications</h1>

        <?php

        $employerID = $_SESSION['userID'];

        include('../database/config.php');

        // Fetch job applications related to the employer's job posts
        if (isset($_SESSION['userID'])) {
            $userID = $_SESSION['userID'];
        
            include('../database/config.php');
        
            $sql = "SELECT ja.applicationID, ja.jobPostID, jp.jobTitle,ja.additionalDetails, ja.applyDate, ja.applyStatus, jp.location,ja.additionalDetails ,ja.applicantResponse ,u.userID AS applicantID, u.fullName AS applicantName
                    FROM jobApplication ja 
                    JOIN jobPost jp ON ja.jobPostID = jp.jobPostID 
                    JOIN jobSeeker u ON ja.applicantID = u.userID
                    WHERE ja.applicantID = ? ";
                    // WHERE jp.userID = ?";
        
            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $userID);
            $stmt->execute();
            $result = $stmt->get_result();
        
            $stmt->close();
        }

        // Check if there are applications to display
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='card mb-3'>
                    <div class='card-body'>
                        <h3 class='card-title'>{$row['jobTitle']}</h3>
                        <p><strong>Applicant Name:</strong><a href='view_jobseeker_profile' class='text-decoration-none'> {$row['applicantName']}</a></p>
                        <p><strong>Location:</strong> {$row['location']}</p>
                        <p><strong>Application ID:</strong> {$row['applicationID']}</p>
                        <p><strong>Applied On:</strong> {$row['applyDate']}</p>
                        <p><strong>Status:</strong> 
                        <span id='status-{$row['applicationID']}' class='status-label {$row['applyStatus']}'>{$row['applyStatus']}</span>
                        </p>";

                
                        if ($row['applyStatus'] === 'Under Review'){
                            if(empty($row['applicantResponse'])) {
                            echo "
                            <div class='alert alert-warning mt-2'>
                                <p><strong>Request More:</strong> " . ($row['additionalDetails'] ?: 'No message provided yet.') . "</p>
                            </div>";
                        }else {
                            echo "
                            <div class='alert alert-warning mt-2'>
                                <p><strong>Request More:</strong> " . ($row['additionalDetails'] ?: 'No message provided yet.') . "</p>
                            </div>
                            <div class='alert alert-info'>
                                <p><strong>Job Seeker Response:</strong> " . ($row['applicantResponse'] ?: 'No message provided yet.') . "</p>
                            </div>";
                            }   

                        }
                        
                    if (in_array($row['applyStatus'], ['Pending', 'Under Review'])){
                echo "

                        <div class='d-flex gap-2'>
                            <button class='btn btn-success' onclick='confirmAction(\"accept\", \"{$row['applicationID']}\")'>Accept</button>
                            <button class='btn btn-danger' onclick='confirmAction(\"reject\", \"{$row['applicationID']}\")'>Reject</button>
                            <button type='button' class='btn btn-secondary' data-bs-toggle='collapse' data-bs-target='#details-{$row['applicationID']}'>Request More</button>
                        </div>
                            
                        <div id='details-{$row['applicationID']}' class='collapse mt-3'>
                            <textarea id='message-{$row['applicationID']}' name='additionalDetails' class='form-control' placeholder='Enter your message for the applicant'></textarea>
                            <button type='button' class='btn btn-primary mt-2' onclick='sendRequestMore(\"{$row['applicationID']}\")'>Send Request</button>
                        </div>";
                    }
                    
                echo "    </div>
                </div>";
                    
            }
        
        } else {
            echo "<p class='alert alert-info'>No job applications found for your job posts.</p>";
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
                confirmButtonColor: action === 'accept' ? '#3085d6' : '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: `${actionText}`
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form and submit it programmatically
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

            // Create FormData object for the AJAX request
            const formData = new FormData();
            formData.append('applicationID', applicationID);
            formData.append('additionalDetails', message); // Make sure the field name matches the PHP script
            formData.append('action', 'requestMore');

            // Send the request via Fetch API
            fetch('processApplication.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text()) // Expect text or JSON response
            .then(data => {
                // Display success or error message
                Swal.fire('Success', 'Request sent successfully!', 'success');
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An unexpected error occurred. Please try again.', 'error');
            });
        }

    </script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

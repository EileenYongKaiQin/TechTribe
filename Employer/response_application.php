<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer - Job Applications</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        /* Container */
        .container {
            margin: 0 auto;
            padding: 30px;
            width: 80%;
            max-width: 800px;
        }

        /* Header Styling */
        .container h1 {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        /* Card Styling */
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 35px;
        }

        .card-body {
            padding: 10px;
        }

        .card-body p{
            padding: 8px;
        }

        .card-title {
            font-size: 22px;
            font-weight: 600;
            color: #333;
        }

        /* Label for status */
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

        /* Button Styling */
        button {
            margin: 10px 0;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        /* Collapse & Textarea */
        .collapse {
            margin-top: 15px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .alert {
            padding: 10px;
            margin-top: 15px;
            border-radius: 6px;
        }

        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
        }

        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }

        /* Link Styling */
        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .container {
                width: 95%;
            }

            .card-body {
                padding: 15px;
            }

            button {
                padding: 8px 12px;
                font-size: 14px;
            }
        }
    </style>
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
        
            $stmt->close();
        }

        // Check if there are applications to display
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userID = $row['applicantID'];
                echo "
                <div class='card'>
                    <div class='card-body'>
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
                Swal.fire('Success', 'Request sent successfully!', 'success');
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An unexpected error occurred. Please try again.', 'error');
            });
        }
    </script>
</body>
</html>

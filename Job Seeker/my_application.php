<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <style>
    .container{
        margin: 0 auto;
        padding: 0;
        width: 80%;
        max-width: 800px;
    }

    .my-application{
        text-align: center;
    }

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

    .UnderReview {
        color: #fff;
        background-color: #ffc107; /* Yellow */
    }
    </style>
</head>
<body>
<?php 
include('jobSeeker1.php'); 
?>
    
    <div class="container">
        <h1 class="my-application">My Applications</h1>

        <?php
        // Ensure user is logged in
        if (!isset($_SESSION['userID'])) {
            exit();
        }

        $userID = $_SESSION['userID'];

        include('../database/config.php'); 

        // Use prepared statement for security
        $sql = "SELECT ja.applicationID, ja.jobPostID, jp.jobTitle, ja.applyDate, ja.applyStatus, ja.additionalDetails, ja.applicantResponse 
                FROM jobApplication ja 
                JOIN jobPost jp ON ja.jobPostID = jp.jobPostID 
                WHERE ja.applicantID = ?";
                
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class='card' style='margin: 20px;'>
                    <div class='card-body'>
                        <h3 class='card-title'><?php echo htmlspecialchars($row['jobTitle']); ?></h3>
                        <p><strong>Application ID: </strong><?php echo htmlspecialchars($row['applicationID']); ?></p>
                        <p><strong>Applied On:</strong> <?php echo htmlspecialchars($row['applyDate']); ?></p>
                        <p><strong>Status:</strong> <span id='status-<?php echo $row['applicationID']; ?>' class='status-label <?php echo htmlspecialchars($row['applyStatus']); ?>'><?php echo htmlspecialchars($row['applyStatus']); ?></span></p>
                        
                        <?php if ($row['applyStatus'] === 'Under Review' && !empty($row['additionalDetails'])): ?>
                            <div class='alert alert-warning'>
                                <strong>Additional Details from Employer:</strong> <?php echo htmlspecialchars($row['additionalDetails']); ?>
                            </div>

                            <?php if (empty($row['applicantResponse'])): ?>
                                <form id='response-form-<?php echo $row['applicationID']; ?>' class='d-flex align-items-center' onsubmit="return sendResponse(event, '<?php echo $row['applicationID']; ?>');">
                                    <textarea id='response-<?php echo $row['applicationID']; ?>' class='form-control me-2' rows='2' placeholder='Type your response here...'></textarea>
                                    <button type='submit' class='btn btn-success'>Submit</button>
                                </form>
                            <?php else: ?>
                                <div class='alert alert-info'>
                                    <strong>Your Previous Response:</strong> <?php echo htmlspecialchars($row['applicantResponse']); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class='d-flex justify-content-between mt-3'>
                            <a href='cancelJob.php?jobPostID=<?php echo $row['jobPostID']; ?>' class='btn btn-primary'>View Job</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p class='alert alert-info'>You have not applied for any jobs yet.</p>";
        }
        $stmt->close();
        $con->close();
        ?>
    </div>
    
    <script>
        function sendResponse(event, applicationID) {
            event.preventDefault();

            const responseField = document.getElementById(`response-${applicationID}`);

            const response = responseField.value.trim();

            if (!response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please type a response before submitting.'
                });
                return false;
            }
            // return true;

            // Log FormData
            const formData = new FormData();
            formData.append('applicationID', applicationID);
            formData.append('response', response);

            fetch('processResponse.php', {
                method: 'POST',
                body: formData
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message
                    });
                    responseField.value = ''; // Clear the response textarea
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An unexpected error occurred: ' + error.message
                });
            });

            return false;
        }

    </script>
</body>
</html>
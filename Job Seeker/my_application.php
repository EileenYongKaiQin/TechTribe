<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/my_application.css">
    
</head>
<body>
<?php 
include('jobSeeker1.php'); 
?>
    
<div class="container">
    <h1>My Applications</h1>

    <?php
    // Ensure user is logged in
    if (!isset($_SESSION['userID'])) {
        exit();
    }

    $applicantID = $_SESSION['userID'];
    include('../database/config.php'); 

    // Use prepared statement for security
    $sql = "SELECT ja.applicationID, ja.jobPostID, jp.jobTitle, ja.applyDate, ja.applyStatus, ja.additionalDetails, ja.applicantResponse 
            FROM jobApplication ja 
            JOIN jobPost jp ON ja.jobPostID = jp.jobPostID 
            WHERE ja.applicantID = ?";
            
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $applicantID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class='card'>
                <h3><?php echo htmlspecialchars($row['jobTitle']); ?></h3>
                <p><strong>Application ID:</strong> <?php echo htmlspecialchars($row['applicationID']); ?></p>
                <p><strong>Applied On:</strong> <?php echo htmlspecialchars($row['applyDate']); ?></p>
                <p><strong>Status:</strong> 
                    <span id='status-<?php echo htmlspecialchars($row['applicationID']); ?>' class='status-label <?php echo htmlspecialchars($row['applyStatus']); ?>'><?php echo htmlspecialchars($row['applyStatus']); ?></span>
                </p>

                <?php if ($row['applyStatus'] === 'Under Review' && !empty($row['additionalDetails'])): ?>
                    <div class='alert-warning'>
                        <strong>Additional Details from Employer:</strong> <?php echo htmlspecialchars($row['additionalDetails']); ?>
                    </div>

                    <?php if (empty($row['applicantResponse'])): ?>
                        <form id='response-form-<?php echo htmlspecialchars($row['applicationID']); ?>' onsubmit="return sendResponse(event, '<?php echo $row['applicationID']; ?>');">
                            <textarea id='response-<?php echo htmlspecialchars($row['applicationID']); ?>' placeholder='Type your response here...'></textarea>
                            <button type='submit' class='submit-btn'>Submit</button>
                        </form>
                    <?php else: ?>
                        <div class='alert-info'>
                            <strong>Your Previous Response:</strong> <?php echo htmlspecialchars($row['applicantResponse']); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <a href='cancelJob.php?jobPostID=<?php echo htmlspecialchars($row['jobPostID']); ?>' class='btn'>View Job</a>
            </div>
            <?php
        }
    } else {
        echo "<div class='no-jobs-container'><p>You have not applied for any jobs yet.</p></div>";
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

        const formData = new FormData();
        formData.append('applicationID', applicationID);
        formData.append('response', response);

        fetch('processResponse.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message
                });
                responseField.value = '';
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

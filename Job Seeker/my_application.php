<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <style>
    /* General Page Styles */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f9f9f9;
        color: #333;
    }
    h1 {
        text-align: center;
        margin: 20px 0;
    }
    .container {
        width: 90%;
        max-width: 800px;
        margin: 0 auto;
    }

    /* Card Styling */
    .card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin: 20px 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .card h3 {
        margin: 0 0 10px;
        color: #444;
    }

    .card p {
        margin: 5px 0;
        font-size: 14px;
    }

    /* Buttons and Links */
    .btn {
        display: inline-block;
        text-decoration: none;
        background-color: #007bff;
        color: #fff;
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 14px;
    }
    .btn:hover {
        background-color: #0056b3;
    }

    /* Status Labels */
    .status-label {
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 5px;
        display: inline-block;
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

    /* Additional Details and Forms */
    .alert-warning {
        background-color: #fffae6;
        border: 1px solid #ffe599;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
    }

    .alert-info{
        background-color: #d1ecf1;
        border: 1px solid #bee5eb;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
    }

    textarea {
        width: 100%;
        height: 80px;
        padding: 8px;
        margin: 5px 0;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        resize: none;
    }

    .submit-btn {
        background-color: #28a745;
        margin: 10px 0;
        color: #fff;
        border: none;
        padding: 8px 12px;
        cursor: pointer;
        border-radius: 4px;
        font-size: 14px;
    }
    .submit-btn:hover {
        background-color: #218838;
    }
    </style>
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
        echo "<p>You have not applied for any jobs yet.</p>";
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

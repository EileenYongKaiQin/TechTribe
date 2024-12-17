<?php 
    include('../database/config.php');
    include('employer1.php');

    // Check if the user is logged in
    if (!isset($_SESSION['userID'])) {
        echo "<script>alert('Session expired. Please log in again.'); window.location.href = '../login.html';</script>";
        exit();
    }
    
    // Get the reported user ID if passed
$reportedUserID = isset($_GET['reportedUserID']) ? $_GET['reportedUserID'] : null;

// Fetch reported user details if available
if ($reportedUserID) {
    $query = $con->prepare("SELECT fullName, email FROM jobseeker WHERE userID = ?");
    $query->bind_param("s", $reportedUserID);
    $query->execute();
    $result = $query->get_result();
    $userData = $result->fetch_assoc();
} else {
    $userData = ['fullName' => 'Unknown User', 'email' => 'N/A'];
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../css/report.css">
    <script type="text/javascript" src="../js/previewFile.js"></script>  
    
   
</head>
<body>

<h2>Report</h2>
<a href="employer_dashboard.php" id="back-btn">Back</a>
<div class="report-content">
    <!-- Report Form -->
    <form id="reportForm" action="submitForm.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="reported_user">Reported User</label>
        <input type="text" id="reported_user" name="reported_user" value="<?php echo htmlspecialchars($userData['fullName']); ?>" readonly>
    </div>
    <div class="form-group">
        <label for="report_reason">Reason For the Report</label>
        <select id="report_reason" name="report_reason" required>
            <option value="">-- Select --</option>
            <option value="No-show by Candidate">No-show by Candidate</option>
            <option value="Fake Qualifications">Fake Qualifications</option>
            <option value="False Information on Profile">False Information on Profile</option>
            <option value="Unprofessional Behavior">Unprofessional Behavior</option>
            <option value="Other Issues">Others</option>
        </select>
    </div>

    <!-- Description -->
     <div class="form-group">
        <label for="description">Description of the Issue</label>
        <textarea id="description" name="description" rows="5" placeholder="Write description not more than 50 words" required></textarea>
    </div>

    <!-- Evidence -->
    <div class="form-group">
        <label for="evidence">Evidence</label>
        <label class="custom-file-upload-icon" onclick="document.getElementById('evidence').click()">
            <img src="https://img.icons8.com/?size=100&id=c3Z8IwwzvmWR&format=png&color=000000" alt="Upload Icon">
            <span>Upload File / Image</span>
        </label>
        <input type="file" id="evidence" name="evidence[]" multiple onchange="previewFiles()">
        <div id="file-preview" class="file-preview"></div>
    </div>
</div>
    <input type="hidden" name="reportedUserID" value="<?php echo htmlspecialchars($reportedUserID); ?>">
    <!-- Submit Button -->
        <button type="submit" class="show-modal" id="submitBtn" disabled>Submit</button>
</form>

<!-- First Confirmation Modal -->
<div id="firstModal" class="modal">
    <div class="modal-content">
        <button class="close-btn" onclick="closeFirstModal()">&times;</button>
        <div class="modal-header">
            <h2>You’re about to submit a report</h2>
        </div>
        <p class="modal-description">You can review or edit your report details below.</p>
        <div class="modal-details">
            <h4>Report Details</h4>
            <p><strong>Why are you reporting this post?</strong> <br><span id="reportReasonText"></span></p>
        </div>
        <div class="modal-footer">
            <button id="submit-modal-btn" onclick="submitFirstModal()">Submit</button>
            <button id="cancel-modal-btn" onclick="closeFirstModal()">Cancel</button>
        </div>
    </div>
</div>

 <!-- Second Modal (Take Action/Close) -->
<div id="secondModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Thanks for reporting this post</h3>
            <p>While you wait for our decision, we'd like you to know that <br>there are other steps you can take now.</p>
        </div>
        <div class="modal-body">
            <div class="modal-actions" onclick="blockUser('<?php echo htmlspecialchars($userData['fullName']); ?>')">
                Block <?php echo htmlspecialchars($userData['fullName']); ?>
                <span>&#8250;</span> <!-- Right arrow icon -->
            </div>
            <div class="modal-actions" onclick="restrictUser('<?php echo htmlspecialchars($userData['fullName']); ?>')">
                Restrict <?php echo htmlspecialchars($userData['fullName']); ?>
                <span>&#8250;</span> <!-- Right arrow icon -->
            </div>
        </div>
        <div class="modal-footer">
            <button id="finalSubmitBtn" onclick="finalSubmit()">Done</button>
        </div>
    </div>
</div>


    <script type="text/javascript" src="../js/ModalControl.js"></script>  
    <script>
    function blockUser(username) {
        alert("You have blocked " + username);
        // Add your logic to block the user
    }

    function restrictUser(username) {
        alert("You have restricted " + username);
        // Add your logic to restrict the user
    }

    document.addEventListener('DOMContentLoaded', function () {
    // Select the button and form fields
    const submitBtn = document.getElementById('submitBtn');
    const formFields = document.querySelectorAll('#reportForm [required], #description');

    function validateForm() {
        let isValid = true;

        // Check if all required fields are filled
        formFields.forEach(field => {
            if (field.value.trim() === '') {
                isValid = false;
            }
        });

        // Enable the button if all fields are valid
        if (isValid) {
            submitBtn.classList.add('active');
            submitBtn.disabled = false;
        } else {
            submitBtn.classList.remove('active');
            submitBtn.disabled = true;
        }
    }

    // Add event listeners for all fields
    formFields.forEach(field => {
        field.addEventListener('input', validateForm);
    });
    validateForm();
    
});
    </script>
</body>
</html>

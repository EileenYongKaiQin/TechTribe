<?php 
    include('../database/config.php');
    include('job_seeker_header.php');

    $jobPostID = isset($_GET['jobPostID']) ? $_GET['jobPostID'] : null;
    
    if ($jobPostID) {
        $query = $con->prepare("
            SELECT l.username AS employerUsername
            FROM jobPost jp
            INNER JOIN login l ON jp.userID = l.userID
            WHERE jp.jobPostID = ?
        ");
        $query->bind_param("s", $jobPostID);
        $query->execute();
        $result = $query->get_result();
        $employerData = $result->fetch_assoc();
        $employerUsername = $employerData['employerUsername'] ?? 'Unknown Employer';
    } else {
        $employerUsername = 'Unknown Employer';
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
    <style>
        .required {
            color: red;
            font-style: italic;
        }

        button.show-modal {
            background: #8EFAAB;
            cursor: pointer;
            color: #FFFFFF;
        }
    </style>
    <script type="text/javascript" src="../js/previewFile.js"></script>  
</head>
<body>

<h2>Report</h2>
<a href="jobseeker_dashboard.php" id="back-btn">Back</a>
<div class="report-content">
    <form id="reportForm" action="submitForm.php" method="POST" enctype="multipart/form-data" novalidate>
        <div class="form-group">
            <label for="report_reason">Reason For the Report<span class="required"> *</span></label>
            <select id="report_reason" name="report_reason" required>
                <option value="">-- Select --</option>
                <option value="Fraud or Scam">Fraud or Scam</option>
                <option value="Share False Information">Sharing False Information</option>
                <option value="Spam">Spam</option>
                <option value="Employer Misconduct">Employer Misconduct</option>
                <option value="Others">Others</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description of the Issue<span class="required"> *</span></label>
            <textarea id="description" name="description" rows="5" placeholder="Write description not more than 50 words" required></textarea>
        </div>

        <div class="form-group">
            <label for="evidence">Evidence</label>
            <label class="custom-file-upload-icon" onclick="document.getElementById('evidence').click()">
                <img src="https://img.icons8.com/?size=100&id=c3Z8IwwzvmWR&format=png&color=000000" alt="Upload Icon">
                <span>Upload File / Image</span>
            </label>
            <input type="file" id="evidence" name="evidence[]" multiple onchange="previewFiles()" accept=".jpg, .jpeg, .png, .pdf">
            <div id="file-preview" class="file-preview"></div>
            <span class="required">* Only jpg, png, or pdf files are allowed.</span>
        </div>
</div>
        <input type="hidden" name="jobPostID" value="<?php echo htmlspecialchars($jobPostID ?? ''); ?>">
        <button type="submit" class="show-modal" id="submitBtn">Submit</button>
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

<!-- Second Modal (Action Confirmation) -->
<div id="secondModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Thanks for reporting this post</h3>
            <p>While you wait for our decision, here are additional actions you can take.</p>
        </div>
        <div class="modal-body">
            <div class="modal-actions" onclick="blockUser('<?php echo $employerUsername; ?>')">
                Block <?php echo $employerUsername; ?>
                <span>&#8250;</span>
            </div>
            <div class="modal-actions" onclick="restrictUser('<?php echo $employerUsername; ?>')">
                Restrict <?php echo $employerUsername; ?>
                <span>&#8250;</span>
            </div>
        </div>
        <div class="modal-footer">
            <button id="finalSubmitBtn" onclick="finalSubmit()">Done</button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("reportForm");
    const firstModal = document.getElementById("firstModal");
    const secondModal = document.getElementById("secondModal");
    let formValid = false;

    form.addEventListener("submit", function (event) {
        if (!form.checkValidity()) {
            form.reportValidity();
            event.preventDefault();
        } else {
            const reportReason = document.getElementById("report_reason").value;
            document.getElementById("reportReasonText").textContent = reportReason || "No reason selected";

            event.preventDefault();
            firstModal.style.display = "flex";
            formValid = true;
        }
    });

    document.getElementById("submit-modal-btn").addEventListener("click", function () {
        firstModal.style.display = "none";
        secondModal.style.display = "flex";
    });

    document.getElementById("finalSubmitBtn").addEventListener("click", function () {
        if (formValid) {
            form.submit();
        }
    });
});

function closeFirstModal() {
    document.getElementById("firstModal").style.display = "none";
}

function blockUser(username) {
    alert("You have blocked " + username);
}

function restrictUser(username) {
    alert("You have restricted " + username);
}
</script>

</body>
</html>

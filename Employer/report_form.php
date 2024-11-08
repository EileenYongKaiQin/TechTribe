<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report an Issue</title>
    <link rel="shortcut icon" href="../images/FlexMatch Logo.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../css/report.css">
    <script type="text/javascript" src="../js/previewFile.js"></script>  
</head>
<body>
<?php 
    include('../database/config.php');
    include('employer.php');
?>
<div class="content">
    <form  id="reportForm" action="submitForm.php" method="POST" enctype="multipart/form-data">
        <h2>Report</h2>

        <label for="report_reason">Reason For the Report</label>
        <select id="report_reason" name="report_reason" required>
            <option value="">-- Select --</option>
            <option value="No-show by Candidate">No-show by Candidate</option>
            <option value="Fake Qualifications">Fake Qualifications</option>
            <option value="False Information on Profile">False Information on Profile</option>
            <option value="Unprofessional Behavior">Unprofessional Behavior</option>
            <option value="Other Issues">Others</option>
        </select>

        <label for="description">Description of the Issue (Optional)</label>
        <textarea id="description" name="description" rows="5"></textarea>

        <label for="evidence">Evidence</label>
        <!-- Custom file upload icon button -->
        <label class="custom-file-upload-icon" onclick="document.getElementById('evidence').click()">
            <img src="https://img.icons8.com/?size=100&id=c3Z8IwwzvmWR&format=png&color=000000" alt="Upload Icon">
        </label>
        <input type="file" id="evidence" name="evidence[]" multiple onchange="previewFiles()">

        <div id="file-preview" class="file-preview"></div>

        <button type="submit" class="show-modal" id="submitBtn">Submit</button>
    
    </form>
</div>
<!-- First Modal (Confirmation) -->
 <div id="firstModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Report</h2>
        </div>
        <span style="font-size: 10px; color: grey;">
        <p>You're about to submit a report</p>
        <p>You can review or edit your report details below.</p></span>
        <br>
        <span style="text-align: left;">
            <h4>Report Details</h4><br>
            <p style="font-size: 14px;"><strong>Why are you reporting this post?</strong></p>
            <p style="font-size: 12px; color: grey;"><span id="reportReasonText"></span></p><br>
        </span>
        <div class="modal-footer">
            <button onclick="submitFirstModal()">Submit</button>
            <button onclick="closeFirstModal()">Cancel</button>
        </div>
    </div>
 </div>

 <!-- Second Modal (Take Action/Close) -->
<div id="secondModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="margin-top: 70px;">Thanks for reporting this post</h3>
        </div>
        <p style="font-size: 9px; color: grey;">While you wait for our decision, we'd like you to know that <br>there are other steps you can take now.</p>
        <br><br>
        <!-- <span style="text-align: left;">
        <h5>Other Steps You Can Take</h5>
        <p href="blockUser.php" style="font-size: 11px; text-decoration: none; color: #3f4656;">Block User</p>
        <p href="restrictUser.php" style="font-size: 11px; text-decoration: none; color: #3f4656;">Restrict User</p>
        </span> -->

        <div class="modal-footer">
            <button id="finalSubmitBtn" onclick="finalSubmit()">Done</button>
        </div>
    </div>
</div>

    <br><br><br>
    <?php include('../footer/footer.php'); ?>

    <script type="text/javascript" src="../js/ModalControl.js"></script>  
</body>
</html>

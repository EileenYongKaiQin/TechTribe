<?php
ob_start();
include('../database/config.php');
include('admin.php');

$targetUserID = mysqli_real_escape_string($con, $_GET['userID'] ?? $_POST['userID']);

$employerQuery = mysqli_query($con, "SELECT * FROM employer WHERE userID='$targetUserID'");
$jobSeekerQuery = mysqli_query($con, "SELECT * FROM jobseeker WHERE userID='$targetUserID'");

$userData = null;
$userType = '';
$tableName = '';

if (mysqli_num_rows($employerQuery) > 0) {
    $userData = mysqli_fetch_assoc($employerQuery);
    $userType = 'employer';
    $tableName = 'employer';
} elseif (mysqli_num_rows($jobSeekerQuery) > 0) {
    $userData = mysqli_fetch_assoc($jobSeekerQuery);
    $userType = 'jobseeker';
    $tableName = 'jobseeker';
} else {
    die("User not found");
}

// Fetch violation history
$violationQuery = mysqli_query($con, "SELECT COUNT(*) as violation_count FROM accountIssue WHERE accountIssueID='$targetUserID'");
$violationHistory = mysqli_fetch_assoc($violationQuery)['violation_count'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $suspendReason = mysqli_real_escape_string($con, $_POST['suspend-reason']);
    $suspendDuration = mysqli_real_escape_string($con, $_POST['suspend-duration']);
    
    $lastIssueQuery = mysqli_query($con, "SELECT issueID FROM accountIssue ORDER BY issueID DESC LIMIT 1");
    if ($lastIssueQuery && mysqli_num_rows($lastIssueQuery) > 0) {
        $lastIssueRow = mysqli_fetch_assoc($lastIssueQuery);
        $lastIssueID = $lastIssueRow['issueID'];
        
        // Extract the numeric part and increment it
        $lastNumericPart = intval(substr($lastIssueID, 2)); // Remove 'IS' and convert to integer
        $nextNumericPart = $lastNumericPart + 1;
        
        // Format the new ID as 'ISXXX' (padded with leading zeros)
        $issueID = 'IS' . str_pad($nextNumericPart, 3, '0', STR_PAD_LEFT);
    } else {
        // If no previous issue IDs exist, start with IS001
        $issueID = 'IS001';
    }
    
    $permanentSuspension = false;
    $expirationDate = null;
    $accountStatus = '';
    
    if ($suspendDuration === 'Temporary') {
        switch ($violationHistory) {
            case 0:
                $expirationDate = date('Y-m-d', strtotime('+6 months'));
                $accountStatus = 'Suspended-Temporary-6M';
                break;
            case 1:
                $expirationDate = date('Y-m-d', strtotime('+2 years'));
                $accountStatus = 'Suspended-Temporary-2Y';
                break;
            case 2:
                $expirationDate = date('Y-m-d', strtotime('+5 years'));
                $accountStatus = 'Suspended-Temporary-5Y';
                break;
            default:
                $permanentSuspension = true;
                $accountStatus = 'Suspended-Permanently';
                break;
        }
    } elseif ($suspendDuration === 'Permanent') {
        $permanentSuspension = true;
        $accountStatus = 'Suspended-Permanently';
    }
    
    // Update user account status
    $updateUserStatus = mysqli_query($con, "UPDATE $tableName SET accountStatus = '$accountStatus', suspensionEndDate  = " . ($expirationDate ? "'$expirationDate'" : "NULL") . " WHERE userID='$targetUserID'");
    
    // Insert account issue record
    $insertIssueQuery = mysqli_query($con, "INSERT INTO accountIssue (
        issueID, 
        issueDate,
        suspendReason, 
        suspendDuration, 
        violation, 
        accountIssueID,
        expirationDate
    ) VALUES (
        '$issueID',
        NOW(), 
        '$suspendReason', 
        '$suspendDuration', 
        " . ($violationHistory + 1) . ", 
        '$userID',
        " . ($expirationDate ? "'$expirationDate'" : "NULL") . "
    )");
    
    if ($updateUserStatus && $insertIssueQuery) {
        // Redirect with success message
        header("Location: accountIssueList.php?message=" . urlencode("Account suspended successfully"));
        ob_end_flush(); // Send the buffered output
        exit();
    } else {
        $error = "Failed to suspend account: " . mysqli_error($con);
    }
}

function reactivateExpiredAccounts($con) {
    $currentDate = date('Y-m-d');

    $employerQuery = "UPDATE employer 
        SET accountStatus = 'Active', 
            suspensionEndDate  = NULL 
        WHERE accountStatus LIKE 'Suspended-Temporary-%' 
        AND suspensionEndDate  IS NOT NULL 
        AND suspensionEndDate  <= '$currentDate'";
    
    $jobseekerQuery = "UPDATE jobseeker 
        SET accountStatus = 'Active', 
            suspensionEndDate  = NULL 
        WHERE accountStatus LIKE 'Suspended-Temporary-%' 
        AND suspensionEndDate  IS NOT NULL 
        AND suspensionEndDate  <= '$currentDate'";

    $employerResult = mysqli_query($con, $employerQuery);
    $employerReactivated = mysqli_affected_rows($con);

    $jobseekerResult = mysqli_query($con, $jobseekerQuery);
    $jobseekerReactivated = mysqli_affected_rows($con);

    // Enhanced logging
    $logMessage = "Account Reactivation - " . date('Y-m-d H:i:s') . "\n";
    $logMessage .= "Employers Reactivated: $employerReactivated\n";
    $logMessage .= "Job Seekers Reactivated: $jobseekerReactivated\n";
    
    // Log queries for debugging
    $logMessage .= "Queries Executed:\n";
    $logMessage .= "Employer Query: " . ($employerResult ? "Success" : "Failed") . "\n";
    $logMessage .= "Jobseeker Query: " . ($jobseekerResult ? "Success" : "Failed") . "\n";
    
    // Optional: Log any MySQL errors
    if (!$employerResult || !$jobseekerResult) {
        $logMessage .= "Employer Error: " . mysqli_error($con) . "\n";
        $logMessage .= "Jobseeker Error: " . mysqli_error($con) . "\n";
    }

    // Write to log file (optional but recommended)
    file_put_contents('account_reactivation.log', $logMessage, FILE_APPEND);

    return [
        'employers_reactivated' => $employerReactivated,
        'jobseekers_reactivated' => $jobseekerReactivated
    ];
}
$reactivationResults = reactivateExpiredAccounts($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suspend Account</title>
    <style>
        .suspend-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .back-btn {
            position: absolute;
            display: flex;
            justify-self: start;
            align-self: end;
            margin-top: 125px;
            margin-right: 100px;
            width: 100px;
            border-radius: 8px;
            color: white;
            background-color: black;
            cursor: pointer;
        }
        .profile-h1 {
            text-align: center;   
            font-size: 40px;
            margin: 50px 0px 30px 180px;
        }
        .suspend-form {
            position: relative;
            width: 75%;
            background: #FFFFFF;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.25);
            border-radius: 30px;
            padding: 40px;
            margin-left: 180px;
            display: flex;
            flex-direction: column;
        }
        .profile-h3 {
            text-align: center;  
            margin-bottom: 26px;
            font-size: 24px;
        }
        .form-group {
            display: flex;
            flex-direction: row;
            font-size: 18px;
            margin-bottom: 25px;
            justify-content: flex-start;
            align-items: center;
        }
        .form-group label {
            width: 30%;
            font-size: 18px;
            text-align: left;
            display: flex;
            margin:
        }
        .form-group input[type="text"] {
            width: 100%;
            font-size: 18px;
            display: flex;
            border: none;
        } 
        .form-group select {
            width: 100%;
            padding: 15px;
            display: flex;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .back-btn,
        .submit-btn,
        .confirm-btn, 
        .cancel-btn {
            font-size: 15px;
            border: 1px solid white;
            padding: 15px;
            font-weight: bold;
            display: flex;
            justify-content: center;
        }
        .submit-btn {
            background: #BEC6BF;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.25);
            border-radius: 30px;
            width: 150px;
            color: #000000;
            border: none;
            cursor: not-allowed;
            justify-self: center;
            transition: background 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
        .submit-btn:hover {
            transform: scale(1.05);
        }
        .submit-btn.active {
            background: #00e68a;
            cursor: pointer;
            color: #FFFFFF;
        }
        .submit-btn:disabled {
            cursor: not-allowed;
            background: #BEC6BF;
            color: #000000;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        /* Modal Content */
        .modal-content {
            position: absolute;
            top: 50%; /* Center content vertically */
            left: 50%; /* Center content horizontally */
            transform: translate(-50%, -50%); /* Center adjustments */
            width: 731px;
            padding: 30px 40px;
            background: #FFFFFF;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0px 4px 16px rgba(0, 0, 0, 0.2);
            animation: scaleUp 0.3s ease-in-out;
            max-width: 90%;
            box-sizing: border-box;
        }

        /* Close Button */
        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 30px;
            height: 30px;
            background: transparent;
            border: none;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            color: #333;
            transition: color 0.3s ease;
        }
        .close-btn:hover {
            color: #FF5252;
        }
        .modal-footer {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .modal-footer button {
            width: 130px;
            height: 50px;
            border-radius: 25px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .modal-footer button:hover {
            transform: scale(1.05);
        }

        #submit-modal-btn {
            background-color: #8EFAAB;
            color: #000;
        }

        #cancel-modal-btn {
            background-color: #FF5252;
            color: #FFF;
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const submitBtn = document.getElementById('submit-btn');
        const formFields = document.querySelectorAll('#suspendForm [required]');
        const modal = document.getElementById('firstModal');
        const submitModalBtn = document.getElementById('submit-modal-btn');

        // Validate form fields before enabling the submit button
        function validateForm() {
            let isValid = true;
            formFields.forEach(field => {
                if (field.value.trim() === '') {
                    isValid = false;
                }
            });

            submitBtn.disabled = !isValid;
            submitBtn.classList.toggle('active', isValid);
        }

        // Attach input listeners to form fields
        formFields.forEach(field => {
            field.addEventListener('input', validateForm);
        });
        validateForm();

        // Open the modal on form submission
        submitBtn.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default form submission
            modal.style.display = 'flex'; // Show the modal
        });

        // Close the modal
        function closeFirstModal() {
            modal.style.display = 'none'; // Hide the modal
        }

        // Handle modal confirmation
        submitModalBtn.addEventListener('click', function () {
            modal.style.display = 'none'; // Hide the modal
            document.getElementById('suspendForm').submit(); // Submit the form
        });

        // Close modal on cancel or close button
        document.getElementById('cancel-modal-btn').addEventListener('click', closeFirstModal);
        document.querySelector('.close-btn').addEventListener('click', closeFirstModal);
    });
    </script>
</head>
<body>
    <h1 class="profile-h1">Suspend Account</h1>
    <button onClick="window.location.href='accountIssueList.php'" class="back-btn">Back</button>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <div class="suspend-container">
        <div class="suspend-form">
            <form id="suspendForm" action="suspendAccount.php" method="POST">
                <h3 class="profile-h3">Suspension Form</h3>

                <!-- Violation Frequency -->
                <div class="form-group">
                    <label for="violation">Violation Frequence</label>
                    <input type="text" value="<?php 
                        if ($violationHistory == 0) {
                            echo "First violation: 6 months suspension";
                        } elseif ($violationHistory == 1) {
                            echo "Second violation: 2 years suspension";
                        } elseif ($violationHistory == 2) {
                            echo "Third violation: 5 years suspension";
                        } else {
                            echo "4 or more violations: Permanent suspension";
                        }
                        ?> " readonly>    
                </div>

                <!-- Issue Date -->
                <div class="form-group">
                    <label for="issue-date">Issue Date</label>
                    <input type="text" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly>
                </div>

                <!-- User Details -->
                <div class="form-group">
                    <label>User Details</label>
                    <input type="text" value="<?php 
                        echo htmlspecialchars($userData['fullName'] . ' (' . 
                        $userData['email'] . ') - ' . 
                        ucfirst($userType)); 
                    ?>" readonly>
                </div>

                <!-- Suspend Reason -->
                <div class="form-group">
                    <label for="suspend-reason">Reason For Suspension</label>
                    <select id="suspend-reason" name="suspend-reason" required>
                        <option value="">-- Select --</option>
                        <option value="Fraud or Scam">Fraud or Scam</option>
                        <option value="Share False Information">Sharing False Information</option>
                        <option value="Spam">Spam</option>
                        <option value="<?php echo $userType == 'employer' ? 'Employer' : 'Job Seeker'; ?> Misconduct">
                            <?php echo $userType == 'employer' ? 'Employer' : 'Job Seeker'; ?> Misconduct
                        </option>
                        <option value="Inappropriate Behavior">Inappropriate Behavior</option>
                        <option value="Others">Others</option>
                    </select>
                </div>

                <!-- Suspend Duration-->
                <div class="form-group">
                    <label for="suspend-duration">Suspend Duration</label>
                    <select id="suspend-duration" name="suspend-duration" required>
                        <option value="">-- Select --</option>
                        <option value="Temporary">Temporary (based on violation history)</option>
                        <option value="Permanent">Permanent</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button class="submit-btn" id="submit-btn" disabled>Submit</button>

                <!-- Confirmation Modal -->
                <div id="firstModal" class="modal">
                    <div class="modal-content">
                        <button class="close-btn" type="button" onclick="closeFirstModal()">&times;</button>
                        <h2 class="modal-description">Are you sure you want to suspend this account?</h2>
                        <div class="modal-footer">
                            <button class="confirm-btn" id="submit-modal-btn" onclick="submitFirstModal()">Confirm</button>
                            <button class="cancel-btn" id="cancel-modal-btn" onclick="closeFirstModal()">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>    
</body>
</html>
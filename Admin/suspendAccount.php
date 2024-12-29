<?php
ob_start();
include('../database/config.php');
include('admin.php');

function getUserDetails($con, $userID) {
    $employerQuery = mysqli_prepare($con, "SELECT * FROM employer WHERE userID = ?");
    mysqli_stmt_bind_param($employerQuery, "s", $userID);
    mysqli_stmt_execute($employerQuery);
    $employerResult = mysqli_stmt_get_result($employerQuery);

    $jobSeekerQuery = mysqli_prepare($con, "SELECT * FROM jobseeker WHERE userID = ?");
    mysqli_stmt_bind_param($jobSeekerQuery, "s", $userID);
    mysqli_stmt_execute($jobSeekerQuery);
    $jobSeekerResult = mysqli_stmt_get_result($jobSeekerQuery);

    if (mysqli_num_rows($employerResult) > 0) {
        $userData = mysqli_fetch_assoc($employerResult);
        $userData['role'] = 'employer';
        return ['data' => $userData, 'type' => 'employer', 'table' => 'employer'];
    } elseif (mysqli_num_rows($jobSeekerResult) > 0) {
        $userData = mysqli_fetch_assoc($jobSeekerResult);
        $userData['role'] = 'jobseeker';
        return ['data' => $userData, 'type' => 'jobseeker', 'table' => 'jobseeker'];
    }

    return null;
}

function getViolationHistory($con, $userID) {
    $violationQuery = mysqli_prepare($con, "SELECT COUNT(*) as violation_count FROM accountIssue WHERE accountIssueID = ?");
    mysqli_stmt_bind_param($violationQuery, "s", $userID);
    mysqli_stmt_execute($violationQuery);
    $violationResult = mysqli_stmt_get_result($violationQuery);
    $violationData = mysqli_fetch_assoc($violationResult);
    return $violationData['violation_count'] ?? 0;
}

function generateIssueID($con) {
    $lastIssueQuery = mysqli_query($con, "SELECT issueID FROM accountIssue ORDER BY issueID DESC LIMIT 1");
    
    if (mysqli_num_rows($lastIssueQuery) > 0) {
        $lastIssueRow = mysqli_fetch_assoc($lastIssueQuery);
        $lastIssueID = $lastIssueRow['issueID'];
        
        $lastNumericPart = intval(substr($lastIssueID, 2));
        $nextNumericPart = $lastNumericPart + 1;
        
        return 'IS' . str_pad($nextNumericPart, 3, '0', STR_PAD_LEFT);
    }
    
    return 'IS001';
}

function determineSuspensionDetails($violationHistory, $suspendDuration) {
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $permanentSuspension = false;
    $expirationDateTime = null;
    $accountStatus = '';
    
    if ($suspendDuration === 'Temporary') {
        switch ($violationHistory) {
            case 0:
                $expirationDateTime = date('Y-m-d H:i:s', strtotime('+6 months'));
                $accountStatus = 'Suspended-Temporary-6M';
                break;
            case 1:
                $expirationDateTime = date('Y-m-d H:i:s', strtotime('+2 years'));
                $accountStatus = 'Suspended-Temporary-2Y';
                break;
            case 2:
                $expirationDateTime = date('Y-m-d H:i:s', strtotime('+5 years'));
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
    
    return [
        'permanent_suspension' => $permanentSuspension,
        'expiration_datetime' => $expirationDateTime,
        'account_status' => $accountStatus
    ];
}

function suspendAccount($con, $userID, $userData, $suspendReason, $suspendDuration) {
    $violationHistory = getViolationHistory($con, $userID);
    
    $issueID = generateIssueID($con);

    $suspensionDetails = determineSuspensionDetails($violationHistory, $suspendDuration);
    
    // Update user status with prepared statement
    $updateUserStatus = mysqli_prepare($con, "UPDATE {$userData['table']} SET 
        accountStatus = ?, 
        suspensionEndDate = ?
        WHERE userID = ?");
    
    mysqli_stmt_bind_param($updateUserStatus, "sss", 
        $suspensionDetails['account_status'], 
        $suspensionDetails['expiration_datetime'], 
        $userID
    );
    
    $insertIssueQuery = mysqli_prepare($con, "INSERT INTO accountIssue (
        issueID, 
        issueDate,
        suspendReason, 
        suspendDuration, 
        violation, 
        accountIssueID,
        expirationDate
    ) VALUES (?, NOW(), ?, ?, ?, ?, ?)");
    
    $violationCount = $violationHistory + 1;
    mysqli_stmt_bind_param($insertIssueQuery, "sssiss", 
        $issueID, 
        $suspendReason, 
        $suspendDuration, 
        $violationCount, 
        $userID, 
        $suspensionDetails['expiration_datetime']
    );
    
    // Execute both queries
    if (mysqli_stmt_execute($updateUserStatus) && mysqli_stmt_execute($insertIssueQuery)) {
        return true;
    }
    
    return false;
}

function reactivateExpiredAccounts($con) {
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $currentDateTime = date('Y-m-d H:i:s');
    
    try {
        $employerQuery = mysqli_prepare($con, "
            UPDATE employer 
            SET 
                accountStatus = 'Inactive', 
                suspensionEndDate = NULL 
            WHERE 
                (accountStatus LIKE 'Suspended-Temporary-6M' 
                OR accountStatus LIKE 'Suspended-Temporary-2Y' 
                OR accountStatus LIKE 'Suspended-Temporary-5Y')
                AND suspensionEndDate IS NOT NULL 
                AND suspensionEndDate <= ?
        ");
        
        mysqli_stmt_bind_param($employerQuery, "s", $currentDateTime);
        $employerExecuteResult = mysqli_stmt_execute($employerQuery);
        
        $jobseekerQuery = mysqli_prepare($con, "
            UPDATE jobseeker 
            SET 
                accountStatus = 'Inactive', 
                suspensionEndDate = NULL 
            WHERE 
                (accountStatus LIKE 'Suspended-Temporary-6M' 
                OR accountStatus LIKE 'Suspended-Temporary-2Y' 
                OR accountStatus LIKE 'Suspended-Temporary-5Y')
                AND suspensionEndDate IS NOT NULL 
                AND suspensionEndDate <= ?
        ");
        
        mysqli_stmt_bind_param($jobseekerQuery, "s", $currentDateTime);
        $jobseekerExecuteResult = mysqli_stmt_execute($jobseekerQuery);

        return true;
    } catch (Exception $e) {
        return false;
    }
}
$reactivationResults = reactivateExpiredAccounts($con);

$error = '';
$userID = isset($_GET['userID']) ? $_GET['userID'] : 
          (isset($_POST['userID']) ? $_POST['userID'] : null);

if (!$userID) {
    die("No user ID provided");
}

$userData = getUserDetails($con, $userID);

if (!$userData) {
    die("User not found");
}

$violationHistory = getViolationHistory($con, $userID);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suspendReason = mysqli_real_escape_string($con, $_POST['suspend-reason']);
    $suspendDuration = mysqli_real_escape_string($con, $_POST['suspend-duration']);
    
    if (suspendAccount($con, $userID, $userData, $suspendReason, $suspendDuration)) {
        header("Location: accountIssueList.php?message=" . urlencode("Account suspended successfully"));
        ob_end_flush();
        exit();
    } else {
        $error = "Failed to suspend account: " . mysqli_error($con);
    }
}
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
            z-index: 2000px;
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

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
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
            const suspendDurationSelect = document.getElementById('suspend-duration');
            const submitBtn = document.getElementById('submit-btn');
            const formFields = document.querySelectorAll('#suspendForm [required]');
            const modal = document.getElementById('firstModal');
            const submitModalBtn = document.getElementById('submit-modal-btn');

            <?php if ($violationHistory >= 4): ?>
                suspendDurationSelect.value = 'Permanent';
                suspendDurationSelect.querySelector('option[value="Temporary"]').disabled = true;
            <?php endif; ?>
            
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

            formFields.forEach(field => {
                field.addEventListener('input', validateForm);
            });
            validateForm();

            submitBtn.addEventListener('click', function (e) {
                e.preventDefault(); 
                modal.style.display = 'flex'; 
            });

            function closeFirstModal() {
                modal.style.display = 'none';
            }

            submitModalBtn.addEventListener('click', function () {
                modal.style.display = 'none';
                document.getElementById('suspendForm').submit();
            });

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
                <input type="hidden" name="userID" value="<?php echo htmlspecialchars($userID); ?>">
                
                <h3 class="profile-h3">Suspension Form</h3>

                <!-- Violation Frequency -->
                <div class="form-group">
                    <label for="violation">Violation Frequency</label>
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
                        ?>" readonly>    
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
                        $fullName = $userData['data']['fullName'] ?? 'N/A';
                        $email = $userData['data']['email'] ?? 'N/A';
                        $userType = $userData['type'] ?? 'Unknown';
                        
                        echo htmlspecialchars("{$fullName} ({$email}) - " . ucfirst($userType)); 
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
                        <option value="<?php echo $userData['type'] == 'employer' ? 'Employer' : 'Job Seeker'; ?> Misconduct">
                            <?php echo $userData['type'] == 'employer' ? 'Employer' : 'Job Seeker'; ?> Misconduct
                        </option>
                        <option value="Inappropriate Behavior">Inappropriate Behavior</option>
                        <option value="Others">Others</option>
                    </select>
                </div>

                <!-- Suspend Duration -->
                <div class="form-group">
                    <label for="suspend-duration">Suspend Duration</label>
                    <select id="suspend-duration" name="suspend-duration" required>
                        <option value="">-- Select --</option>
                        <option value="Temporary" 
                        <?php
                        if ($violationHistory >= 3) {
                            echo 'disabled title="Not available for repeated violations"';
                        }
                        ?>
                    >Temporary (based on violation history)</option>
                    <option value="Permanent" 
                        <?php
                        if ($violationHistory >= 3) {
                            echo 'selected';
                        }
                        ?>
                    >Permanent</option>
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
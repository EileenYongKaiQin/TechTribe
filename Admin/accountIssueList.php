<?php
include('../database/config.php');
include('admin.php');

$baseQuery = "
    SELECT 
        l.userID,
        l.username,
        CASE
            WHEN l.role = 'jobSeeker' THEN 'Job Seeker'
            WHEN l.role = 'employer' THEN 'Employer'
            ELSE 'Unknown'
        END AS role,
        CASE
            WHEN l.role = 'jobSeeker' THEN js.fullName
            WHEN l.role = 'employer' THEN emp.fullName
            ELSE 'Unknown'
        END AS fullName,
        CASE
            WHEN l.role = 'jobSeeker' THEN js.accountStatus
            WHEN l.role = 'employer' THEN emp.accountStatus
            ELSE 'Active'
        END AS accountStatus,
        CASE
            WHEN l.role = 'jobSeeker' THEN js.warningHistory
            WHEN l.role = 'employer' THEN emp.warningHistory
            ELSE 0
        END AS warningFrequency,
        CASE
            WHEN l.role = 'jobSeeker' THEN js.suspensionEndDate
            WHEN l.role = 'employer' THEN emp.suspensionEndDate
            ELSE NULL
        END AS suspensionEndDate
    FROM login l
    LEFT JOIN jobSeeker js ON l.userID = js.userID
    LEFT JOIN employer emp ON l.userID = emp.userID
    WHERE 
        (js.warningHistory >= 1 OR emp.warningHistory >= 1)
";

$result = $result_count = null;
$search_criteria = $search_value = '';

if (isset($_GET['search'])) {
    $search_criteria = mysqli_real_escape_string($con, $_GET['search_criteria']);
    $search_value = mysqli_real_escape_string($con, $_GET['search_value']);

    $searchQuery = $baseQuery;
    
    switch ($search_criteria) {
        case 'userID':
            $searchQuery .= " AND l.userID = '$search_value'";
            break;
        case 'username':
            $searchQuery .= " AND l.username LIKE '%$search_value%'";
            break;
        case 'fullName':
            $searchQuery .= " AND (js.fullName LIKE '%$search_value%' OR emp.fullName LIKE '%$search_value%')";
            break;
        case 'role':
            $searchQuery .= " AND l.role = '$search_value'";
            break;
        case 'accountStatus':
            $searchQuery .= " AND (js.accountStatus LIKE '%$search_value%' OR emp.accountStatus LIKE '%$search_value%')";
            break;
    }

    $searchQuery .= " ORDER BY 
        CASE 
            WHEN l.role = 'jobSeeker' THEN js.warningHistory 
            WHEN l.role = 'employer' THEN emp.warningHistory 
        END DESC";

    $result = mysqli_query($con, $searchQuery);
} else {
    $baseQueryWithOrder = $baseQuery . " ORDER BY 
        CASE 
            WHEN l.role = 'jobSeeker' THEN js.warningHistory 
            WHEN l.role = 'employer' THEN emp.warningHistory 
        END DESC";
    $result = mysqli_query($con, $baseQueryWithOrder);
}

if (!$result) {
    error_log("Database Query Failed: " . mysqli_error($con));
    die("An error occurred while fetching data.");
}

function reactivateExpiredAccounts($con) {
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $currentDateTime = date('Y-m-d H:i:s');
    
    $employerQuery = "
        UPDATE employer e
        SET 
            e.accountStatus = 'Inactive', 
            e.suspensionEndDate = NULL
        WHERE 
            (e.accountStatus IN ('Suspended-Temporary-6M', 'Suspended-Temporary-2Y', 'Suspended-Temporary-5Y'))
            AND e.suspensionEndDate IS NOT NULL 
            AND e.suspensionEndDate <= '$currentDateTime'
    ";
    $employerResult = mysqli_query($con, $employerQuery);

    $jobseekerQuery = "
        UPDATE jobseeker js
        SET 
            js.accountStatus = 'Inactive', 
            js.suspensionEndDate = NULL
        WHERE 
            (js.accountStatus IN ('Suspended-Temporary-6M', 'Suspended-Temporary-2Y', 'Suspended-Temporary-5Y'))
            AND js.suspensionEndDate IS NOT NULL 
            AND js.suspensionEndDate <= '$currentDateTime'
    ";
    $jobseekerResult = mysqli_query($con, $jobseekerQuery);

}
$reactivationResults = reactivateExpiredAccounts($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts with High Warning Frequencies</title>
    <link rel="stylesheet" href="../css/admin_styles.css">
    <style>
        .container {
            margin-left: 250px;
            margin-right: auto;
            padding: 40px;
            max-width: 1200px;
            text-align: center;
            position: relative;
            width: 90%;
            min-height: 100vh;
            background: linear-gradient(to right bottom, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.3));
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5), 0 0 25px rgba(0, 0, 0, 0.08);
        }
        .htitle {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            color: #333333;
            margin-top: 10px;
            margin-bottom: 30px;
        }
        .search-input{
            border-radius: 5px;
            padding: 10px;
            width: 60%;
            border: 2px solid black;
            font-size: 15px;
        }
        .search-criteria{
            color: white;
            border: 2px solid black;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            background-color: black;
            margin-right: 5px;
        }
        .search-btn:hover, .reset-btn:hover{
            color: black;
            background-color: #AAE1DE;
            box-shadow: 0 0 15px 5px rgba(255,255,255,0.75);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #FFFFFF;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        th, td {
            border: 1px solid #3a3a3a;
            padding: 16px;
            text-align: center;
        }
        th {
            background-color: #AAE1DE;
            font-weight: bold;
        }
        tbody tr:hover {
            background: rgba(45, 140, 255, 0.1);
        }
        tbody td:last-child {
            text-align: center;
        }
        .warning-high {
            color: red;
            font-weight: bold;
        }
        .view-btn, .search-btn, .reset-btn {
            background-color: #000000;
            color: white;
            border: none;
            padding: 9px 16px;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }
        .search-btn {
            margin-left: 5px;
        }
        .view-btn {
            margin: 0px 5px;
        }
        .view-btn:hover {
            background-color: #AAE1DE;
            color: black;
        }
        tbody td:last-child {
            text-align: center;
            padding: 0px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="htitle">Reported Account with Issues</h1>
        <form class="search-form" action="accountIssueList.php" method="GET">
            <select name="search_criteria" class="search-criteria" required>
                <option value="" disabled selected hidden>- Select Option -</option>
                <option value="userID">User ID</option>
                <option value="username">Username</option>
                <option value="fullName">Account User</option>
                <option value="role">Role</option>
                <option value="accountStatus">Account Status</option>
            </select>
            <input class="search-input" type="text" name="search_value" required> 
            <input class="search-btn" type="submit" name="search" id="search" value="Search"/>
            <button class="reset-btn" onclick="window.location.href='accountIssueList.php'" >Reset</button>
        </form>
        <br>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Username</th>
                        <th>Account User's Name</th>
                        <th>Role</th>
                        <th>Warning Frequency</th>
                        <th>Account Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php $counter = 1;
                          while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $counter++ ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['fullName']) ?></td>
                            <td><?= htmlspecialchars($row['role']) ?></td>
                            <td class="warning-high"><?= htmlspecialchars($row['warningFrequency']) ?></td>
                            <td>
                                <?php $statusColor = '';
                                if ($row['accountStatus'] == 'Active') {
                                    $statusColor = '#44bb44';
                                } else if ($row['accountStatus'] == 'Inactive') {
                                    $statusColor = '#757575';
                                } else if ($row['accountStatus'] == 'Suspended-Temporary-6M' || $row['accountStatus'] == 'Suspended-Temporary-2Y' || $row['accountStatus'] == 'Suspended-Temporary-5Y' || $row['accountStatus'] == 'Suspended-Permanently') {
                                    $statusColor = 'red';
                                }?>
                                <span style="color: <?php echo $statusColor; ?>;"><?PHP echo $row['accountStatus'];?></span>
                            </td>
                            <td>
                                <button 
                                    class="view-btn" 
                                    onclick="viewProfile('<?= htmlspecialchars($row['userID']) ?>', '<?= htmlspecialchars($row['role']) ?>')">
                                    View Profile
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <tr>
                <td colspan="7" style="padding: 16px;">No accounts found.</td>
            </tr>
        <?php endif; ?>
    </div>

    <script>
function setModalData(userID) {
    const modalElement = document.getElementById('modalViewProfile');

    if (modalElement) {
        modalElement.setAttribute('data-userId', userID);
    }
}

function viewProfile(userID, role) {
    if (!userID) {
        const modalElement = document.getElementById('modalViewProfile');
        userID = modalElement ? modalElement.getAttribute('data-userId') : null;
    }

    if (!userID || !role) {
        alert('Invalid user data.');
        return;
    }

    let profileURL;
    switch (role) {
        case 'Job Seeker':
            profileURL = `view_jobseeker_profile.php?userID=${encodeURIComponent(userID)}`;
            break;
        case 'Employer':
            profileURL = `view_employer_profile.php?userID=${encodeURIComponent(userID)}`;
            break;
        default:
            alert('Invalid user role.');
            return;
    }

    window.location.href = profileURL;
}


    </script>
</body>
</html>

<?php
mysqli_close($con);
?>
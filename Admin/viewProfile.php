<?php
include('../database/config.php');
include('admin.php');

// Check if 'userID' is passed via URL
if (isset($_GET['userID'])) {
    $userID = $_GET['userID'];

    // Fetch user details based on the userID (for job seekers or employers)
    $sql = "
        SELECT 
            login.userID, 
            login.username, 
            login.email, 
            login.role, 
            jobSeeker.fullName AS jobSeekerName, 
            jobSeeker.contactNo AS jobSeekerContactNo,
            jobSeeker.age,
            jobSeeker.gender,
            jobSeeker.race,
            jobSeeker.workExperience,
            jobSeeker.language,
            jobSeeker.hardSkill,
            jobSeeker.softSkill,
            jobSeeker.profilePic AS jobSeekerPic, 
            employer.fullName AS employerName, 
            employer.companyName, 
            employer.companyAddress, 
            employer.contactNo AS employerContactNo,
            employer.profilePic AS employerPic,
            rp.reportID,
            rp.jobPostID
        FROM login
        LEFT JOIN jobSeeker ON login.userID = jobSeeker.userID
        LEFT JOIN employer ON login.userID = employer.userID
        LEFT JOIN reportPost rp ON rp.reportedUserID = login.userID
        WHERE login.userID = ?
    ";

    $stmt = $con->prepare($sql);
    $stmt->bind_param('s',$userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Fetch and process each report
        while ($user = $result->fetch_assoc()) {

        $reportID = $user['reportID'];
        $jobPostID = $user['jobPostID'];
        $profileData = [
            'name' => $user['role'] == 'jobSeeker' ? $user['jobSeekerName'] : $user['employerName'],
            'contact' => $user['role'] == 'jobSeeker' ? $user['jobSeekerContactNo'] : $user['jobSeekerContactNo'],
            'age' => $user['role'] == 'jobSeeker' ? $user['age'] : 'N/A',
            'gender' => $user['role'] == 'jobSeeker' ? $user['gender'] : 'N/A',
            'race' => $user['role'] == 'jobSeeker' ? $user['race'] : 'N/A',
            'workExperience' => $user['role'] == 'jobSeeker' ? $user['workExperience'] : 'N/A',
            'language' => $user['role'] == 'jobSeeker' ? $user['language'] : 'N/A',
            'hardSkill' => $user['role'] == 'jobSeeker' ? $user['hardSkill'] : 'N/A',
            'softSkill' => $user['role'] == 'jobSeeker' ? $user['softSkill'] : 'N/A',
            'profilePic' => $user['role'] == 'jobSeeker' ? 
                ($user['jobSeekerPic'] ? "../uploads/profile_pics/" . $user['jobSeekerPic'] : '../images/jobSeeker.png') : 
                ($user['employerPic'] ? "../uploads/profile_pics/" . $user['employerPic'] : '../images/employer.png'),
            'role' => ucfirst($user['role']),
            'email' => $user['email'],
            'company' => $user['role'] == 'employer' ? $user['companyName'] : 'N/A',
            'companyAddress' => $user['role'] == 'employer' ? $user['companyAddress'] : 'N/A',
            'reportID' => $reportID, 
            'jobPostID' => $jobPostID
        ];
    }

?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>View Profile</title>
            <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
            <link rel="stylesheet" href="../css/view_reporteduser_profile.css">
            <style>
                .userDetails .userName, .userDetails .basic-info,
                .userDetails .company-info, .userDetails .work-experience,
                .userDetails .skills {
                    padding: 10px;
                }
            </style>
            
        </head>
        <body>
        <a href="reviewReport.php" id="back-btn">Back</a>
            <div class="container">
                <section class="userProfile card">
                    <div class="card-body">
                        <section>
                            <div class="profile">
                                <figure><img src="<?php echo $profileData['profilePic']; ?>" alt="profile" width="250px" height="250px"></figure>
                            </div>
                        </section>
                    </div>
                </section>

                <section class="userDetails card">
                    <div class="userName">
                        <h1 class="name"><?php echo $profileData['name']; ?></h1>
                        <p><?php echo $profileData['role']; ?></p>
                    </div>

                    <hr class="separation-line">

                    <div class="basic-info">
                        <h1 class="heading">Basic Information</h1>
                        <p>Email: <?php echo $profileData['email']; ?></p>
                        <p>Contact No.: <?php echo $profileData['contact']; ?></p>
                        <?php if ($profileData['role'] == 'JobSeeker'): ?>
                            <p>Age: <?php echo $profileData['age']; ?></p>
                            <p>Gender: <?php echo $profileData['gender']; ?></p>
                            <p>Race: <?php echo $profileData['race']; ?></p>
                        <?php endif; ?>
                    </div>

                    <hr class="separation-line">

                    <div class="company-info">
                        <?php if ($profileData['role'] == 'Employer'): ?>
                            <h1 class="heading">Company Details</h1>
                            <p>Company: <?php echo $profileData['company']; ?></p>
                            <p>Address: <?php echo $profileData['companyAddress']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="work-experience">
                        <?php if ($profileData['role'] == 'JobSeeker'): ?>
                            <h1 class="heading">Work Experience</h1>
                            <p><?php echo $profileData['workExperience']; ?></p>
                        <?php endif; ?>
                    </div>

                    <hr class="separation-line">

                    <div class="skills">
                        <?php if ($profileData['role'] == 'JobSeeker'): ?>
                            <h1 class="heading">Skills</h1>
                            <p><strong>Languages:</strong> <?php echo $profileData['language']; ?></p>
                            <p><strong>Hard Skills:</strong> <?php echo $profileData['hardSkill']; ?></p>
                            <p><strong>Soft Skills:</strong> <?php echo $profileData['softSkill']; ?></p>
                        <?php endif; ?>
                    </div>
                    

                    

                    
                </section>
                <!-- Modal -->
                <div id="updateModal" class="modal hidden">
                    <div class="modal-content">
                        <span class="close" id="closeModal" onclick="closeModal('updateModal')">&times;</span>
                        <h2>Are you sure you want to issue the warning to this user?</h2>
                        <div class="comment-section">
                            <!-- Comment section (optional) -->
                            <label for="comment">Additional comment:<textarea id="warningComment" placeholder="Write comment"></textarea></label>
                        </div>
                        <div class="modal-buttons">
                            <button id="cancelButton" class="btn cancel" onclick="closeModal('updateModal')">Cancel</button>
                            <button id="updateButton" class="btn update" onclick="updateStatus()">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btns">
                        <ul>
                            <li><button class="btn warning" onclick="showWarningModal()">Issue Warning</button></li>
                        </ul>
            </div>
        <script>
            // JavaScript for modal functionality
            function showWarningModal() {
                    document.getElementById('updateModal').classList.remove('hidden');
                    document.getElementById('updateModal').classList.add('visible');
                }

                function closeModal(modalId) {
                    const modal = document.getElementById(modalId);
                    modal.classList.remove('visible');
                    modal.classList.add('hidden');
                }

                function updateStatus() {
                    const comment = document.getElementById('warningComment').value; // Get the comment text
                    const userID = '<?php echo $userID; ?>'; // Get the userID from PHP
                    const reportID = '<?php echo $profileData['reportID']; ?>'; 
                    console.log("Report id:",reportID);
                    // Update the warningHistory in the database
                    fetch('updateWarning.php', {
                        method: 'POST',
                        body: JSON.stringify({ userID, comment, reportID }),
                        headers: { 'Content-Type': 'application/json' },
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Response:', data);
                        if (data.success) {
                            alert('Warning issued successfully.');
                            closeModal('updateModal');
                            window.location.href = 'reviewReport.php';
                        } else {
                            alert('Failed to issue warning.' + data.message);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error occurred while issuing the warning.');
                    });
                }
        </script>
        </body>
        </html>

        <?php
    } else {
        echo "<p>User profile not found.</p>";
    }
    $stmt->close();
} else {
    echo "<p>Invalid request. No userID specified.</p>";
}
?>

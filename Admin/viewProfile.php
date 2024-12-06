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
            jobSeeker.contactNo, 
            jobSeeker.profilePic AS jobSeekerPic, 
            employer.fullName AS employerName, 
            employer.companyName, 
            employer.companyAddress, 
            employer.contactNo,
            employer.profilePic AS employerPic
        FROM login
        LEFT JOIN jobSeeker ON login.userID = jobSeeker.userID
        LEFT JOIN employer ON login.userID = employer.userID
        WHERE login.userID = ?
    ";

    $stmt = $con->prepare($sql);
    $stmt->bind_param('s', $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Prepare data based on the user's role (Job Seeker or Employer)
        if ($user['role'] == 'jobSeeker') {
            $profileData = [
                'name' => $user['jobSeekerName'],
                'contact' => $user['contactNo'],
                'profilePic' => $user['jobSeekerPic'] ? "../uploads/profile_pics/" . $user['jobSeekerPic'] : '../images/jobSeeker.png',
                'role' => 'Job Seeker',
                'email' => $user['email'],
                'location' => $user['location'] ?? 'N/A',
                'company' => 'N/A',
                'companyAddress' => 'N/A',
                'profileLink' => "view_jobseeker_profile.php?userID=" . $user['userID'],
            ];
        } elseif ($user['role'] == 'employer') {
            $profileData = [
                'name' => $user['employerName'],
                'contact' => $user['contactNo'],
                'profilePic' => $user['employerPic'] ? "../uploads/profile_pics/" . $user['employerPic'] : '../images/employer.png',
                'role' => 'Employer',
                'email' => $user['email'],
                'company' => $user['companyName'],
                'companyAddress' => $user['companyAddress'],
                'profileLink' => "view_employer_profile.php?userID=" . $user['userID'],
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

                    <div class="basic-info">
                        <h1 class="heading">Basic Information</h1>
                        <p>Email: <?php echo $profileData['email']; ?></p>
                        <p>Contact No.: <?php echo $profileData['contact']; ?></p>
                        <?php if ($profileData['role'] == 'jobSeeker'): ?>
                            <p>Location: <?php echo $profileData['location']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="company-info">
                        <?php if ($profileData['role'] == 'Employer'): ?>
                            <h1 class="heading">Company Details</h1>
                            <p>Company: <?php echo $profileData['company']; ?></p>
                            <p>Address: <?php echo $profileData['companyAddress']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="btns">
                        <ul>
                            <li><button class="btn warning" onclick="showWarningModal()">Issue Warning</button></li>
                        </ul>
                    </div>
                </section>
                <!-- Modal -->
                <div id="updateModal" class="modal hidden">
                    <div class="modal-content">
                        <span class="close" id="closeModal" onclick="closeModal('updateModal')">&times;</span>
                        <h2>Are you sure you want to issue the warning to this user?</h2>
                        <div class="modal-buttons">
                            <button id="cancelButton" class="btn cancel" onclick="closeModal('updateModal')">Cancel</button>
                            <button id="updateButton" class="btn update" onclick="updateStatus()">Submit</button>
                        </div>
                    </div>
                </div>
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
                    // Example: Submit the action for issuing a warning (you can add AJAX here to update status in DB)
                    alert('Warning issued to user.');
                    closeModal('updateModal');
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

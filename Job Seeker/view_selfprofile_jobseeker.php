<?php

include('../database/config.php');
include('jobSeeker1.php');

if (!isset($_SESSION['userID'])) {
    header('Location: jobseeker_dashboard.php');
    exit();
}

$userID = $_SESSION['userID'];

$sql = mysqli_query($con, "SELECT * FROM jobseeker WHERE userID='$userID'");

if (!$sql) {
    die("Query failed: " . mysqli_error($con));
}

$data = mysqli_fetch_array($sql);

if (!$data) {
    echo"<script>alert('No profile found. Please create a profile first.');
    window.location.href='create_jobseeker_profile.php';</script>";
    echo"<script>";

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>

    <link rel="stylesheet" href="../css/view_selfprofile.css">

</head>

<body>
    <div class="profile-con">
        <!-- ===== ===== Body Main-Background ===== ===== -->
        <span class="main_bg"></span>


        <!-- ===== ===== Main-Container ===== ===== -->
        <div class="container">


            <!-- ===== ===== User Main-Profile ===== ===== -->
            <section class="userProfile card">
                <div class="card-body">
                <section>
                    <div class="profile">
                        <figure>
                            <?php 
                            if (!empty($data['profilePic'])) {
                                $profilePicPath = "../uploads/profile_pictures/" . htmlspecialchars($data['profilePic']);
                                if (file_exists($profilePicPath)) {
                                    echo '<img src="' . $profilePicPath . '" alt="profile" width="250px" height="250px">';
                                } else {
                                    echo '<img src="../images/JobSeeker.png" alt="profile" width="250px" height="250px">';
                                }
                            } else {
                                echo '<img src="../images/JobSeeker.png" alt="profile" width="250px" height="250px">';
                            }
                            ?>
                        </figure>
                    </div>
                </section>
                    <div class="profilefc">
                        <button class="btn edit" onclick="location.href='update_jobseeker_profile.php'">Edit Profile</button>
                        <button class="btn delete" onclick="location.href='delete_jobseeker_profile.php'">Delete Profile</button>
                    </div>
                </div>
            </section>


            <!-- ===== ===== Work & Language Section ===== ===== -->
            <section class="work_skills card">

                <!-- ===== ===== Work Container ===== ===== -->
                <div class="work">
                    <h1 class="heading">work experience</h1>
                    <div class="primary">
                        <h1><?PHP echo $data['company'];?></h1>
                        <span><?PHP echo $data['position'];?></span>
                        <p>Working experience with <?PHP echo $data['workExperience'];?>
                        as the <?PHP echo $data['position'];?>
                        </p>
                    </div>
                    <br>
                

                <!-- ===== ===== Language Container ===== ===== -->
                <div class="languages">
                    <h1 class="heading">Proficiency</h1>
                    <?php $languages = explode(",", $data['language']); ?>
                    <h1>Proficiency Languages</h1>
                    <ul>
                    <?php if (!empty($languages)): ?>
                        <?php foreach ($languages as $index => $langs): ?>
                            <?php if ($index >= 0): ?>
                                <li><?php echo htmlspecialchars($langs); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </ul>
                </div>
            </section>


            <!-- ===== ===== User Details Sections ===== ===== -->
            <section class="userDetails card">
                <div class="userName">
                    <div class="acc-status">
                    <h1 class="name"><?PHP echo $data['fullName'];?></h1>
                        <?php $statusColor = '';
                        if ($data['accountStatus'] == 'Active') {
                            $statusColor = '#44bb44';
                        } else if ($data['accountStatus'] == 'Inactive') {
                            $statusColor = 'rgba(0, 0, 0, 0.8)';
                        } else if ($data['accountStatus'] == 'Suspended-Temporary-6M' || $data['accountStatus'] == 'Suspended-Temporary-2Y' || $data['accountStatus'] == 'Suspended-Temporary-5Y' || $data['accountStatus'] == 'Suspended-Permanently') {
                            $statusColor = 'red';
                        }?>
                        <span>(<span style="color: <?php echo $statusColor; ?>;"><?PHP echo $data['accountStatus'];?></span>)</span>
                    </div>   
                    <p>Job Seeker</p>
                </div>

                <div class="basic-info">
                <h1 class="heading">Basic Information</h1>
                <ul class="jobseeker-info">
                    <li class="age">
                        <h1 class="title-proInfo">Age: </h1>
                        <span class="info"> <?PHP echo $data['age'];?></span>
                    </li>

                    <li class="gender">
                        <h1 class="title-proInfo">Gender: </h1>
                        <span class="info"> <?PHP echo $data['gender'];?></span>
                    </li>
                    <li class="race">
                        <h1 class="title-proInfo">Race: </h1>
                        <span class="info"> <?PHP echo $data['race'];?></span>
                    </li>
                </ul>
            </div>
                
                
            </section>


            <!-- ===== ===== About & Skills Sections ===== ===== -->
            <section class="timeline_about card">
                <div class="tabs">
                    <input type="radio" name="slider" id="about" checked> 
                    <input type="radio" name="slider" id="skill">
                    <input type="radio" name="slider" id="application">
                    <nav>
                        <label for="about" class="about">About</label>
                        <label for="skill" class="skill">&nbsp;&nbsp;Skills</label>
                        <label for="application" class="application">Application</label>
                        <div class="slider"></div>
                    </nav>
                    <section class="sec-con">
                        <div class="contentInfo contentInfo-1">
                            <h1 class="heading">Contact Information</h1>
                            <ul>
                                <li class="email">
                                    <h1 class="title-proInfo">Email: </h1>
                                    <span class="info"> <?PHP echo $data['email'];?></span>
                                </li>

                                <li class="phone">
                                    <h1 class="title-proInfo">Contact No.: </h1>
                                    <span class="info"> <?PHP echo $data['contactNo'];?></span>
                                </li>

                                <li class="location">
                                    <h1 class="title-proInfo">Location: </h1>
                                    <span class="info"> <?PHP echo $data['location'];?>, <?PHP echo $data['state'];?></span>
                                </li>
                            </ul>
                        </div>

                        <div class="contentInfo contentInfo-2">
                            <h1 class="heading">Hard Skills</h1>
                            <?php $hardSkills = explode(", ", $data['hardSkill']); ?>
                            <ul>
                                <?php if (!empty($hardSkills)): ?>
                                    <?php foreach ($hardSkills as $index => $skill): ?>
                                        <?php if ($index >= 0): ?>
                                            <li class="skill"><?php echo htmlspecialchars($skill); ?></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                            <h1 class="heading">Soft Skills</h1>
                            <?php $softSkills = explode(", ", $data['softSkill']); ?>
                            <ul>
                                <?php if (!empty($softSkills)): ?>
                                    <?php foreach ($softSkills as $index => $skill): ?>
                                        <?php if ($index >= 0): ?>
                                            <li class="skill"><?php echo htmlspecialchars($skill); ?></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <div class="contentInfo contentInfo-3">
                        <h1 class="heading">Application</h1>
                            <ul>
                                <li class="position">
                                    <h1 class="title-proInfo">example</h1>
                                    <span class="info">example</span>
                                </li>

                            </ul>
                        </div>

                    </section>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
<?php 
include('../database/config.php');

$userID =  $_GET['applicantID'];

// Fetch the profile details for the given userID
$sql = mysqli_query($con, "SELECT * FROM jobseeker WHERE userID='$userID'");

if (!$sql) {
    die("Query failed: " . mysqli_error($con));
}

$data = mysqli_fetch_array($sql);

if (!$data) {
    echo "<script>
        alert('No profile found for the selected user.');
        window.location.href='response_application.php';
    </script>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>

    <link rel="stylesheet" href="../css/visit_job_seeker.css">

</head>

<body>
    <?php
        // include('employerNew.php');        
    ?>
    <div class="main-content">
        <!-- ===== ===== User Main-Profile ===== ===== -->
        <div class="userProfile">
   
            <div class="profile">
                <figure><img src="../images/JobSeeker.png" alt="profile"></figure>
            </div>

            <!-- <div class="profilefc">
                <button class="btn-edit" onclick="location.href='update_jobseeker_profile.php'">Edit Profile</button>
                <button class="btn-delete" onclick="location.href='delete_jobseeker_profile.php'">Delete Profile</button>
            </div> -->
        </div>


        <!-- ===== ===== Work & Language Section ===== ===== -->
        <div class="work-skills">

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
            </div>
            

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
        </div>


        <!-- ===== ===== User Details Sections ===== ===== -->
        <section class="userDetails card">
            <div class="userName">
                <h1 class="name"><?PHP echo $data['fullName'];?></h1>
                <p>Job Seeker</p>
                <div class="acc-status">
                    <?php $statusColor = '';
                    if ($data['accountStatus'] == 'Active') {
                        $statusColor = '#44bb44';
                    } else if ($data['accountStatus'] == 'Inactive') {
                        $statusColor = 'rgba(0, 0, 0, 0.8)';
                    } else if ($data['accountStatus'] == 'Suspended') {
                        $statusColor = 'red';
                    }?>
                    (<span style="color: <?php echo $statusColor; ?>;"><?PHP echo $data['accountStatus'];?></span>)
                </div>
            </div>

            <div class="basic-info">
                <h1 class="heading">Basic Information</h1>
                <p>Age: <?PHP echo $data['age'];?></p>
                <p>Gender: <?PHP echo $data['gender'];?></p>
                <p>Race: <?PHP echo $data['race'];?></p>
            </div>
             
            <div class="btns">
                <ul>
                    <li class="reportUser">
                        <button class="btn report">Report User</button>
                    </li>

                    <li class="sendmsg">
                        <button class="btn message">Send Message</button>
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
                    <label for="skill" class="skill">Skills</label>
                    <label for="application" class="application">Application</label>
                    <div class="slider"></div>
                </nav>
                <section class="sec-con">
                    <div class="content content-1">
                        <h1 class="heading">Contact Information</h1>
                        <ul>
                            <li class="email">
                                <h1 class="title">Email: </h1>
                                <span class="info"> <?PHP echo $data['email'];?></span>
                            </li>

                            <li class="phone">
                                <h1 class="title">Contact No.: </h1>
                                <span class="info"> <?PHP echo $data['contactNo'];?></span>
                            </li>

                            <li class="location">
                                <h1 class="title">Location: </h1>
                                <span class="info"> <?PHP echo $data['location'];?>, <?PHP echo $data['state'];?></span>
                            </li>
                        </ul>
                    </div>

                    <div class="content content-2">
                        <h1 class="heading">Hard Skills</h1>
                        <?php $hardSkills = explode(", ", $data['hardSkill']); ?>
                        <ul>
                            <?php if (!empty($hardSkills)): ?>
                                <?php foreach ($hardSkills as $index => $skill): ?>
                                    <?php if ($index >= 0): ?>
                                        <li class="skill"> -  <?php echo htmlspecialchars($skill); ?></li>
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
                                        <li class="skill">-  <?php echo htmlspecialchars($skill); ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="content content-3">
                    <h1 class="heading">Application</h1>
                        <ul>
                            <li class="position">
                                <h1 class="title">example</h1>
                                <span class="info">example</span>
                            </li>

                        </ul>
                    </div>

                </section>        
    </div>

</body>

</html>


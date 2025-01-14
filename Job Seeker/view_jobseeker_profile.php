<?php
include('../database/config.php');
include('job_seeker_header.php'); 

if (!isset($_SESSION['userID'])) {
    header('Location: jobseeker_dashboard.php');
    exit();
}

$userID = mysqli_real_escape_string($con, $_GET['userID'] ?? $_SESSION['userID']);

$sql = mysqli_query($con, "SELECT * FROM jobseeker WHERE userID='$userID'");

if (!$sql) {
    die("Query failed: " . mysqli_error($con));
}

$data = mysqli_fetch_array($sql);

if (!$data) {
    echo"<script>alert('No profile found for the selected user.');
    window.location.href='jobseeker_wall.php';</script>";
    echo"<script>";

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Profile</title>

    <link rel="stylesheet" href="../css/view_profile.css">

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
            </div>
        </section>


        <!-- ===== ===== Work & Language Section ===== ===== -->
        <section class="work_skills card">

            <!-- ===== ===== Work Container ===== ===== -->
            <div class="work">
                <h1 class="heading">work experience</h1>
                <div class="primary">
                    <div class="tag">
                        <h1><?PHP echo $data['company'];?>
                            <span><?PHP echo $data['position'];?></span>
                        </h1>
                    </div>
                    <p>Working experience with <?PHP echo $data['workExperience'];?>
                    as the <?PHP echo $data['position'];?>
                    </p>
                </div>
                <br>

            <!-- ===== ===== Language Container ===== ===== -->
            <div class="languages">
                <h1 class="heading">Proficiency</h1>
                <h2 style="font-size:18px;">Proficiency Languages</h2>
                <div class="lang-container">
                    <?php 
                    $languages = explode(",", $data['language']); 
                    if (!empty($languages[0])): 
                        foreach ($languages as $lang): 
                            $langParts = explode('|', trim($lang));
                            if (count($langParts) == 2):
                                $languageName = trim($langParts[0]);
                                $proficiency = trim($langParts[1]);
                                $percentage = ($proficiency / 10) * 100;
                    ?>
                        <div class="lang-item">
                            <span class="lang-name"><?php echo htmlspecialchars($languageName); ?></span>
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="--percentage: <?php echo $percentage; ?>%"></div>
                                <span class="progress-level"><?php echo $proficiency; ?>/10</span>
                            </div>
                        </div>
                    <?php 
                            endif;
                        endforeach; 
                    endif; 
                    ?>
                </div>
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
                        $statusColor = '#757575';
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
             
            <div class="btns">
                <ul>
                    <li class="reportUser">
                        <button style="margin-left: 3px;"class="btn report" onClick="window.location.href='suspendAccount.php?userID=<?php echo $targetUserID;?>'">Report User</button>
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
                        <br>
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
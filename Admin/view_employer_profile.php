<?php

include('../database/config.php');
include('admin.php');

if (!isset($_SESSION['userID'])) {
    header('Location: admin_dashboard.php');
    exit();
}

$targetUserID = mysqli_real_escape_string($con, $_GET['userID'] ?? $_SESSION['userID']);

$sql = mysqli_query($con, "SELECT * FROM employer WHERE userID='$targetUserID'");

if (!$sql) {
    die("Query failed: " . mysqli_error($con));
}

$data = mysqli_fetch_array($sql);

if (!$data) {
    echo"<script>alert('No profile found. Please create a profile first.');
    window.location.href='create_employer_profile.php';</script>";
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
                        <figure><img src="../images/employer.png" alt="profile" width="250px" height="250px"></figure>
                    </div>
                </section>
            </div>
        </section>


        <!-- ===== ===== Company Section ===== ===== -->
        <section class="work_skills card">
            <div class="work">
                <h1 class="heading">Company Details</h1>
                <div class="primary">
                    <h1><?PHP echo $data['companyName'];?></h1>
                    <p><?PHP echo $data['companyAddress'];?></p>
                </div>
                <br>
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
                <p>Employer</p>
            </div>

            <div class="basic-info">
                <h1 class="heading">Contact Information</h1>
                <ul class="employer-info">
                    <li class="email">
                        <h1 class="title">Email: </h1>
                        <span class="info"> <?PHP echo $data['email'];?></span>
                    </li>

                    <li class="phone">
                        <h1 class="title">Contact No.: </h1>
                        <span class="info"> <?PHP echo $data['contactNo'];?></span>
                    </li>
                </ul> 
            </div>
             
            <div class="btns">
                <ul>
                    <li class="reportUser">
                        <button class="btn report" onClick="window.location.href='suspendAccount.php?userID=<?php echo $targetUserID;?>'">Report User</button>
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
                        </ul>
                    </div>

                    <div class="content content-2">
                        
                    </div>

                    <div class="content content-3">

                    </div>

                </section>
            </div>
        </section>
    </div>
    </div>
</body>

</html>

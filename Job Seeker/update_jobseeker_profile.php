<?php
include('jobSeeker1.php');

function redirectWithStatus($status) {
    echo "<script>window.location.href='update_jobseeker_profile.php?status=" . $status . "';</script>";
    exit();
}

if (!isset($_SESSION['userID'])) {
    echo '<script>window.location.href="jobseeker_dashboard.php";</script>';
    exit();
}

$userID = $_SESSION['userID'];
$sql = mysqli_query($con, "SELECT * FROM jobseeker WHERE userID='$userID'");

if (!$sql) {
    die("Query failed: " . mysqli_error($con));
}

$data = mysqli_fetch_array($sql);

if (!$data) {
    echo "<script>alert('No profile found. Please create a profile first.');
    window.location.href='create_jobseeker_profile.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{ 
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $contactNo = $_POST['contactNo'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $race = $_POST['race'];
    $location = $_POST['location'];
    $state = $_POST['state'];
    $position = $_POST['position'];
    $company = $_POST['company'];
    $workExperience = $_POST['workExperience'];
    $languages = $_POST['language'];
    $hardSkills = isset($_POST['hardSkill']) ? 
    implode(", ", array_filter($_POST['hardSkill'])) : '';
    $softSkills = isset($_POST['softSkill']) ? 
    implode(", ", array_filter($_POST['softSkill'])) : '';

    //Validataion
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectWithStatus('invalid_email');
    }

    if (!preg_match('/^[0-9]{10,12}$/', $contactNo)) {
        redirectWithStatus('invalid_phone');
    }

    if (!preg_match('/^[0-9]{1,2}$/', $age) || $age < 18 || $age > 80) {
        redirectWithStatus('invalid_age');
    }

    $profilePicUpdate = "";
    if(isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/profile_pictures/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = $_FILES['profilePic']['name'];
        $sanitizedName = preg_replace("/[^a-zA-Z0-9.-]/", "_", $originalName);
        $targetPath = $uploadDir . $sanitizedName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['profilePic']['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            redirectWithStatus('invalid_file');
        }

        if ($_FILES['profilePic']['size'] > 5 * 1024 * 1024) {
            redirectWithStatus('file_too_large');
        }

        $counter = 1;
        $filenameParts = pathinfo($sanitizedName);
        while (file_exists($targetPath)) {
            $newFilename = $filenameParts['filename'] . '_' . $counter . '.' . $filenameParts['extension'];
            $targetPath = $uploadDir . $newFilename;
            $sanitizedName = $newFilename;
            $counter++;
        }

        if (!move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetPath)) {
            redirectWithStatus('upload_failed');
        }
        $profilePicUpdate = ", profilePic='$sanitizedName'";
    }

    $fullName = mysqli_real_escape_string($con,$fullName);
    $email = mysqli_real_escape_string($con,$email);
    $contactNo = mysqli_real_escape_string($con,$contactNo);
    $age = mysqli_real_escape_string($con,$age);
    $gender = mysqli_real_escape_string($con,$gender);
    $race = mysqli_real_escape_string($con,$race);
    $location = mysqli_real_escape_string($con,$location);
    $state = mysqli_real_escape_string($con,$state);
    $position = mysqli_real_escape_string($con,$position);
    $company = mysqli_real_escape_string($con,$company);
    $workExperience = mysqli_real_escape_string($con,$workExperience);

    $updateQuery = "UPDATE jobseeker SET 
        email='$email', 
        contactNo='$contactNo', 
        age='$age', 
        gender='$gender', 
        race='$race', 
        location='$location', 
        state='$state', 
        position='$position', 
        company='$company', 
        workExperience='$workExperience',
        language='$languages', 
        hardSkill='$hardSkills', 
        softSkill='$softSkills'
        $profilePicUpdate
        WHERE userID='$userID'";

    if (mysqli_query($con, $updateQuery)) {
        redirectWithStatus('success');
    } else {
        redirectWithStatus('fail');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Update Profile</title>

    <style>
        body {
            font-family: sans-serif;
            font-family: "Poppins", sans-serif;
        }
        .container {
            margin-left: 200px;
        }
        .profile-h1 {
            text-align: center;   
            font-size: 40px;
            margin-bottom: 30px;
            margin-top: 80px;
        }
        .profile-h3 {
            text-align: center;  
            margin-bottom: 22px;
            font-size: 22px;
        }
        form {
            display: flex;
            flex-direction: column;
            text-align: center;  
            align-items: center;
            margin-top: 15px;
            border: 1px solid #ccc;
            padding: 25px 25px 25px;
            border-radius: 10px;
            width: 80%;
            box-sizing: border-box;
            margin: auto;
            background: linear-gradient(to right bottom,rgba(255, 255, 255, 0.9),rgba(255, 255, 255, 0.7));
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5), 0 0 25px rgba(0, 0, 0, 0.08);
        }
        form .form-row {
            align-items: center;
            width: 100%;
            margin-bottom: 22px;
        }
        form .form-row label,
        form .input-row label {
            padding: 0px 5px;
            text-align: left;
            display: flex;
            font-size: 18px;
        }
        .form-row input[type="text"],
        .form-row input[type="email"], 
        .form-row select {
            width: 100%;
            padding: 15px;
            margin-top: 8px;
            display: flex;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .input-group input[type="text"],
        .input-group button {
            padding: 15px;
            margin-top: 8px;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            display: flex;
            align-items: center;
        }
        .form-row input[type="file"] {
            width: 100%;
            margin-top: 8px;
            display: flex;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            align-items: center;
            padding: 7px 9px;
        }
        input[type=file]::-webkit-file-upload-button,
        input[type=file]::file-selector-button {
            background-color: black;
            color: white;
            border: none;
            padding: 8px 32px;
            margin-right: 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: transform 0.2s ease;
            font-size: 15px;
        }
        input[type=file]::-webkit-file-upload-button:hover,
        input[type=file]::file-selector-button:hover {
            cursor: grab;
            background-color: #AAE1DE;
            color: black;
            transform: scale(1.03);
        }
        .back-btn,
        .update-btn {
            font-size: 15px;
            width: 100%;
            color: white;
            background-color: black;
            border: 1px solid white;
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .back-btn {
            margin-top: 80px;
        }
        .update-btn {
            margin-top: 20px;
        }
        .back-btn:hover,
        .update-btn:hover {
            color: black;
            background-color: #b2eff1;
            box-shadow: 0 0 15px 5px rgba(255,255,255,0.75);
        }
        form .row{
            align-items: center;
            width: 100%;
            margin-bottom: 20px;
            display: flex;
            margin: 0px;
            align-items: flex-start;
        }
        form .skill-container{
            width: 100%;
            margin-bottom: 20px;
            margin: 0px;
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }
        form .col-50-le{
            float: left;
            align-items: center;
            width: 100%;
            padding: 0px 10px 0px 0px;
        }
        form .col-50-mid{
            float: center;
            align-items: center;
            width: 100%;
            padding: 0px 10px;
        }
        form .col-50-ri{
            float: right;
            align-items: center;
            width: 100%;
            padding: 0px 0px 0px 10px;
        }
        form .col-25-ri{
            float: right;
            align-items: center;
            width: 50%;
            padding: 0px 0px 0px 10px;
        }
        form .input-row {
            display: flex;
            flex-direction: column;
            width: 90%;
        }
        form .input-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        form .input-row .input-group input[type="text"] {
            width: 90%;
        }
        .add-btn {
            background-color: #0066ff;
            color: #ffffff;
            font-weight: bold;
        }
        .remove-btn {
            background-color: #ff1a1a;
            color: #ffffff;
        }
        .lang-container {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .lang-container input[type="checkbox"],
        .lang-container label {
            display: inline-block;
            margin-top: 10px;
        }

        .lang-container div {
            display: flex;
            align-items: center;
        }
        .others-wrapper {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        input[readonly] {
            background-color: #f0f0f0;
            color: #63666A;
        }
        .lang-container .others-wrapper input[type="text"] {
            margin-top: 10px;
        }
        .popup {
            position: fixed;
            top: 100vh;
            left: 0px;
            width: 100%;
            height: 100%;
            z-index: 1000;
        }
        .popup .overlay {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            opacity: 0;
            transition: opacity 100ms ease-in-out 200ms;
        }
        .popup .popup-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(1.15);
            width: 95%;
            max-width: 600px;
            background: #ffffff;
            padding: 25px;
            opacity: 0;
            border-radius: 20px;
            box-shadow: 0px 2px 2px 5px rgba(0,0,0,0.05);
            transition: all 300ms ease-in-out;
        }
        .popup .popup-content h3{
            margin: 15px 0px;
            font-size: 25px;
            color: #111111;
            text-align: center;
        }
        .popup .popup-content p{
            margin: 20px 0px;
            font-size: 15px;
            color: #222222;
            text-align: center;
        }
        .popup .popup-content .controls{
            display: flex;
            justify-content: space-between;
            margin: 20px 0px 0px;
            float:right;
        }
        .popup .popup-content .controls button{
           padding: 10px 20px;
           border: none;
           outline: none;
           font-size: 15px;
           border-radius: 20px;
           cursor: pointer;
        }
        .popup .popup-content .controls .okay-btn{
           background: #3284ed;
           color:#ffffff;
           float: right;
           width: 100px;
        }
        .popup.active {
            top: 0px;
            transition: top 0ms ease-in-out 0ms;
        }
        .popup.active .overlay{
            opacity: 1;
            transition: all 300ms ease-in-out;
        }
        .popup.active .popup-content{
            transition: translate(-50%,-50%) scale(1);
            opacity: 1;
        }
        button.close-btn {
            width: 30px;
            font-size: 20px;
            color: #c0c5cb;
            align-self: flex-end;
            background-color: transparent;
            border: none;
            margin-bottom: 10px;
            float: right;
        }
    </style>
    
</head>
<body>

<div class="container">
    <h1 class="profile-h1">Update Profile</h1>
    <form method="POST" enctype="multipart/form-data" id="updateForm" class="section">
        <h3 class="profile-h3">Personal Information</h3>
        <div class="form-row">
            <label for="fullName">Name</label>
            <input type="text" name="fullName" placeholder="Enter Full Name" value="<?php echo htmlspecialchars($data['fullName']);?>" readonly>
        </div>
        <div class="row">    
            <div class="col-50-le">
                <div class="form-row">
                    <label for="email">Email</label>
                    <input type="text" name="email" placeholder="Enter Email" value="<?php echo htmlspecialchars($data['email']);?>" required>
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="contactNo">Contact No.</label>
                    <input type="text" name="contactNo" placeholder="Enter Contact No." value="<?php echo htmlspecialchars($data['contactNo']);?>" minlength="10" maxlength="12" required>
                </div>
            </div>
        </div>
        <div class="row">    
            <div class="col-50-le">
                <div class="form-row">
                    <label for="age">Age</label>
                    <input type="text" name="age" placeholder="Enter Age" value="<?php echo htmlspecialchars($data['age']);?>" required>
                </div>
            </div>
            <div class="col-50-mid">
                <div class="form-row">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" value="<?php echo htmlspecialchars($data['gender']);?>">
                        <option disabled selected hidden>- Select Gender -</option>
                        <option value="Male" <?php echo htmlspecialchars($data['gender']) === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo htmlspecialchars($data['gender']) === 'Female' ? 'selected' : ''; ?>>Female</option>
                    </select> 
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="race">Race</label>
                    <select name="race" id="race" value="<?php echo htmlspecialchars($data['race']);?>">
                        <option disabled selected hidden>- Select Race -</option>
                        <option value="Malay" <?php echo htmlspecialchars($data['race']) === 'Malay' ? 'selected' : ''; ?>>Malay</option>
                        <option value="Chinese" <?php echo htmlspecialchars($data['race']) === 'Chinese' ? 'selected' : ''; ?>>Chinese</option>
                        <option value="Indian" <?php echo htmlspecialchars($data['race']) === 'Indian' ? 'selected' : ''; ?>>Indian</option>
                        <option value="Others" <?php echo htmlspecialchars($data['race']) === 'Others' ? 'selected' : ''; ?>>Others</option>
                    </select> 
                </div>
            </div>
        </div>
        <div class="row">  
            <div class="col-50-le">
                <div class="form-row">
                    <label for="location">Location</label>
                    <input type="text" name="location" placeholder="Enter Location" value="<?php echo htmlspecialchars($data['location']);?>" required>
                </div>
            </div> 
            <div class="col-25-ri">
                <div class="form-row">
                    <label for="state">State</label>
                    <select name="state" id="state" value="<?php echo htmlspecialchars($data['state']);?>">
                        <option disabled selected hidden>- Choose State -</option>
                        <option value="Johor" <?php echo htmlspecialchars($data['state']) === 'Johor' ? 'selected' : ''; ?>>Johor</option>
                        <option value="Kedah" <?php echo htmlspecialchars($data['state']) === 'Kedah' ? 'selected' : ''; ?>>Kedah</option>
                        <option value="Kelantan" <?php echo htmlspecialchars($data['state']) === 'Kelantan' ? 'selected' : ''; ?>>Kelantan</option>
                        <option value="Malacca" <?php echo htmlspecialchars($data['state']) === 'Malacca' ? 'selected' : ''; ?>>Malacca</option>
                        <option value="Negeri Sembilan" <?php echo htmlspecialchars($data['state']) === 'Negeri Sembilan' ? 'selected' : ''; ?>>Negeri Sembilan</option>
                        <option value="Pahang" <?php echo htmlspecialchars($data['state']) === 'Pahang' ? 'selected' : ''; ?>>Pahang</option>
                        <option value="Penang" <?php echo htmlspecialchars($data['state']) === 'Penang' ? 'selected' : ''; ?>>Penang</option>
                        <option value="Perak" <?php echo htmlspecialchars($data['state']) === 'Perak' ? 'selected' : ''; ?>>Perak</option>
                        <option value="Perlis" <?php echo htmlspecialchars($data['state']) === 'Perlis' ? 'selected' : ''; ?>>Perlis</option>
                        <option value="Sabah" <?php echo htmlspecialchars($data['state']) === 'Sabah' ? 'selected' : ''; ?>>Sabah</option>
                        <option value="Sarawak" <?php echo htmlspecialchars($data['state']) === 'Sarawak' ? 'selected' : ''; ?>>Sarawak</option>
                        <option value="Selangor" <?php echo htmlspecialchars($data['state']) === 'Selangor' ? 'selected' : ''; ?>>Selangor</option>
                        <option value="Terengganu" <?php echo htmlspecialchars($data['state']) === 'Terengganu' ? 'selected' : ''; ?>>Terengganu</option>
                        <option value="Kuala Lumpur" <?php echo htmlspecialchars($data['state']) === 'Kuala Lumpur' ? 'selected' : ''; ?>>Kuala Lumpur</option>
                        <option value="Labuan" <?php echo htmlspecialchars($data['state']) === 'Labuan' ? 'selected' : ''; ?>>Labuan</option>
                        <option value="Putrajaya" <?php echo htmlspecialchars($data['state']) === 'Putrajaya' ? 'selected' : ''; ?>>Putrajaya</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <label for="profilePic">Profile Picture</label>
            <div class="file-upload-wrapper">
                <input type="file" name="profilePic" id="profilePic" accept="image/jpeg,image/png,image/gif">
                <small>Maximum file size: 5MB. Allowed formats: JPG, PNG, GIF</small>
            </div>
        </div>
        <br>
        <br>
        
        <h3 class="profile-h3">Work Experience</h3>
        <div class="row">
            <div class="col-50-le">
                <div class="form-row">
                    <label for="position">Job Role / Position</label>
                    <input type="text" name="position" placeholder="Enter Job Role" value="<?php echo htmlspecialchars($data['position']);?>">
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="company">Job Place / Company</label>
                    <input type="text" name="company" placeholder="Enter Job Place" value="<?php echo htmlspecialchars($data['company']);?>">
                </div>
            </div>
        </div>
        <div class="form-row">
            <label for="workExperience">Working Experience</label>
            <input type="text" name="workExperience" placeholder="Enter Working Experience" value="<?php echo htmlspecialchars($data['workExperience']);?>">
        </div>
        <br>
        <br>

        <h3 class="profile-h3">Proficiency</h3>
        <div class="row">
            <div class="form-row">
                <label for="language">Language</label>
                <input type="text" name="language" placeholder="Enter languages" value="<?php echo htmlspecialchars($data['language']);?>">
            </div>
        </div>
        <br>
        <br>

        <h3 class="profile-h3">Skills</h3>
        <div class="row">
            <div class="col-50-le">
                <div class="skill-container">
                    <div class="input-row">
                        <label for="hardskill">Hard Skills</label>
                        <div id="hardSkillsContainer">
                            <?php $hardSkills = explode(", ", $data['hardSkill']); ?>
                                <div class="input-group">
                                    <input type="text" name="hardSkill[]" value="<?php echo isset($hardSkills[0]) ? $hardSkills[0] : ''; ?>" placeholder="Enter Hard Skill">
                                    <button type="button" class="add-btn" id="addHardSkill">+</button>
                                </div>
                                <?php if (!empty($hardSkills[1])): ?>
                                    <?php foreach ($hardSkills as $index => $skill): ?>
                                        <?php if ($index > 0): ?>
                                            <div class="input-group">
                                                <input type="text" name="hardSkill[]" value="<?php echo htmlspecialchars($skill); ?>" placeholder="Enter Hard Skill">
                                                <button type="button" class="remove-btn"><i class="fa fa-trash"></i></button>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                        </div>
                    </div>
                </div>    
            </div>
            <div class="col-50-ri">
                <div class="skill-container">
                    <div class="input-row">
                        <label for="softskill">Soft Skills</label>
                        <div id="softSkillsContainer">
                            <?php $softSkills = explode(", ", $data['softSkill']); ?>
                            <div class="input-group">
                                <input type="text" name="softSkill[]" value="<?php echo isset($softSkills[0]) ? $softSkills[0] : ''; ?>" placeholder="Enter Soft Skill">
                                <button type="button" class="add-btn" id="addSoftSkill">+</button>
                            </div>
                            <?php if (!empty($softSkills[1])): ?>
                                <?php foreach ($softSkills as $index => $skill): ?>
                                    <?php if ($index > 0): ?>
                                        <div class="input-group">
                                            <input type="text" name="softSkill[]" value="<?php echo htmlspecialchars($skill); ?>" placeholder="Enter Soft Skill">   
                                            <button type="button" class="remove-btn"><i class="fa fa-trash"></i></button>    
                                        </div>        
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
        <br>
        <br>
        <input class="update-btn" id="update-btn" type="submit" value="Update">
    </form>

    <div class="popup" id="popup">
        <div class="overlay"></div>
        <div class="popup-content">
            <button class="close-btn">✖</button>
            <br>
            <h3></h3>
            <p></p>
            <div class="controls">
                <button class="okay-btn">OK</button>
            </div>
        </div>
    </div>
</div>

<br><br><br><br><br>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $(document).on("click", "#addHardSkill", function (e) {
        e.preventDefault();
        $("#hardSkillsContainer").append(`
            <div class="input-group">
                <input type="text" name="hardSkill[]" placeholder="Enter Hard Skill">
                <button type="button" class="remove-btn"><i class="fa fa-trash"></i></button>
            </div>
        `);
    });

    $(document).on("click", "#addSoftSkill", function (e) {
        e.preventDefault();
        $("#softSkillsContainer").append(`
            <div class="input-group">
                <input type="text" name="softSkill[]" placeholder="Enter Soft Skill">
                <button type="button" class="remove-btn"><i class="fa fa-trash"></i></button>
            </div>
        `);
    });

    $(document).on("click", ".remove-btn", function (e) {
        e.preventDefault();
        $(this).closest(".input-group").remove();
    });


    $("form").on("submit", function(e) {
        const inputs = $(this).find("[required]");
        let allValid = true;

        inputs.each(function() {
            if (!$(this).val()) {
                allValid = false;
                $(this).addClass("error");
            } else {
                $(this).removeClass("error");
            }
        });

        if (!allValid) {
            e.preventDefault();
            alert("Please fill out all required fields.");
            return false;
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const statusMessages = {
        'success': {
            title: 'Profile Updated Successfully!',
            message: 'Your jobseeker profile has been successfully updated.'
        },
        'fail': {
            title: 'Profile Update Failed',
            message: 'Database Error: An error occurred while updating your profile. Please try again.'
        },
        'invalid_file': {
            title: 'Invalid File Format',
            message: 'Please upload only JPG, PNG, or GIF images.'
        },
        'file_too_large': {
            title: 'File Too Large',
            message: 'The image file size must be less than 5MB.'
        },
        'upload_failed': {
            title: 'Upload Failed',
            message: 'Failed to upload profile picture. Please try again.'
        },
        'incomplete_form': {
            title: 'Incomplete Information',
            message: 'Please fill in all required fields.'
        },
        'invalid_email': {
            title: 'Invalid Email',
            message: 'Please enter a valid email address.'
        },
        'invalid_phone': {
            title: 'Invalid Phone Number',
            message: 'Please enter a valid phone number (10-12 digits only).'
        },
        'invalid_age': {
            title: 'Invalid Age',
            message: 'Please enter a valid age (18-80).'
        }
    };

    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    if (status && statusMessages[status]) {
        const popup = document.getElementById('popup');
        const statusInfo = statusMessages[status];
        
        const titleElement = popup.querySelector('h3');
        const messageElement = popup.querySelector('p');
        
        titleElement.textContent = statusInfo.title;
        messageElement.textContent = statusInfo.message;
        
        popup.classList.add('active');

        const closeElements = popup.querySelectorAll('.close-btn, .okay-btn, .overlay');
        closeElements.forEach(element => {
            element.addEventListener('click', function() {
                popup.classList.remove('active');
                
                if (status === 'success') {
                    window.location.href = 'view_selfprofile_jobseeker.php';
                } else {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('status');
                    window.history.replaceState({}, '', url);
                }
            });
        });
    }

    const form = document.getElementById('updateForm');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            const popup = document.getElementById('popup');
            const statusInfo = statusMessages['incomplete_form'];
            
            const titleElement = popup.querySelector('h3');
            const messageElement = popup.querySelector('p');
            
            titleElement.textContent = statusInfo.title;
            messageElement.textContent = statusInfo.message;
            
            popup.classList.add('active');
        }
    });

    const profilePicInput = document.getElementById('profilePic');
    profilePicInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        const maxSize = 5 * 1024 * 1024;
        
        if (!allowedTypes.includes(file.type) || file.size > maxSize) {
            e.preventDefault();
            const popup = document.getElementById('popup');
            const statusInfo = !allowedTypes.includes(file.type) ? 
                statusMessages['invalid_file'] : 
                statusMessages['file_too_large'];
            
            const titleElement = popup.querySelector('h3');
            const messageElement = popup.querySelector('p');
            
            titleElement.textContent = statusInfo.title;
            messageElement.textContent = statusInfo.message;
            
            popup.classList.add('active');
            e.target.value = '';
        }
    });
});
</script>

</body>
</html>
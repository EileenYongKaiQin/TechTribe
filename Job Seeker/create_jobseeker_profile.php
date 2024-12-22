<?php
include('../database/config.php');
include('jsprofile_header.php');

function redirectWithStatus($status) {
    echo "<script>window.location.href='create_jobseeker_profile.php?status=" . $status . "';</script>";
    exit();
}

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
} else {
    echo '<script>window.location.href="../login.html";</script>';
    exit();
}

if(!empty($_POST)) 
{   
    $userID = $_GET['userID'] ?? null;
    if (!$userID) {
        redirectWithStatus('invalid_user');
    }

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
    
    $languages = '';
    if (isset($_POST['language']) && is_array($_POST['language'])) {
        if (isset($_POST['OthersInput']) && $_POST['OthersInput'] == 'Others' && !empty($_POST['language'][count($_POST['language'])-1])) {
            $languages = implode(", ", $_POST['language']);
        } else {
            $languages = implode(", ", array_filter($_POST['language']));
        }
    }
    
    $hardSkill = $_POST['hardSkill'];
    $hardSkills = implode(", ",$hardSkill);
    $softSkill = $_POST['softSkill'];
    $softSkills = implode(", ",$softSkill);

    //Validataion
    if (!preg_match('/^[a-zA-Z ]+$/', $fullName)) {
        redirectWithStatus('invalid_name');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectWithStatus('invalid_email');
    }

    if (!preg_match('/^[0-9]{10,12}$/', $contactNo)) {
        redirectWithStatus('invalid_phone');
    }

    if (!preg_match('/^[0-9]{1,2}$/', $age) || $age < 18 || $age > 80) {
        redirectWithStatus('invalid_age');
    }

    $profilePic = null;
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
        $profilePic = $sanitizedName;
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
    
    if(mysqli_query($con,"INSERT INTO jobseeker
        (userID, fullName, email, contactNo, age, gender, race, location, state, position, company, workExperience, language, hardSkill, softSkill, profilePic)
        VALUES ('$userID', '$fullName','$email','$contactNo','$age', '$gender','$race','$location','$state','$position','$company','$workExperience','$languages','$hardSkills','$softSkills','$profilePic')")) 
    {
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
    <title>Create Profile</title>

    <style>
        body {
            font-family: sans-serif;
            font-family: "Poppins", sans-serif;
        }
        .container {
            position: relative;
            margin: auto;
            width: 90%;
            min-height: 100vh;
            padding-bottom: 50px;
            background: linear-gradient(to right bottom,rgba(255, 255, 255, 0.5),rgba(255, 255, 255, 0.3));
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5), 0 0 25px rgba(0, 0, 0, 0.08);
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
            width: 85%;
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
        .create-btn {
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
        .create-btn {
            margin-top: 20px;
        }
        .back-btn:hover,
        .create-btn:hover {
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
            margin-top: 15px;
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
            margin: 20px auto;
            font-size: 25px;
            color: #111111;
            text-align: center;
        }
        .popup .popup-content p{
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
    <h1 class="profile-h1">Create Profile</h1>
    <form class="section" action='create_jobseeker_profile.php?userID=<?PHP echo $userID; ?>' method='POST' enctype="multipart/form-data">

        <h3 class="profile-h3">Personal Information</h3>
        <div class="form-row">
            <label for="name">Name</label>
            <input type="text" name="fullName" placeholder="Enter Full Name"required>
        </div>
        <div class="row">    
            <div class="col-50-le">
                <div class="form-row">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Enter Email" required>
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="contactNo">Contact No.</label>
                    <input type="text" name="contactNo" placeholder="Enter Contact No." minlength='10' required maxlength='12' required>
                </div>
            </div>
        </div>
        <div class="row">    
            <div class="col-50-le">
                <div class="form-row">
                    <label for="age">Age</label>
                    <input type="text" name="age" placeholder="Enter Age" required>
                </div>
            </div>
            <div class="col-50-mid">
                <div class="form-row">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender">
                        <option disabled selected hidden>- Select Gender -</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select> 
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="race">Race</label>
                    <select name="race" id="race">
                        <option disabled selected hidden>- Select Race -</option>
                        <option value="Malay">Malay</option>
                        <option value="Chinese">Chinese</option>
                        <option value="Indian">Indian</option>
                        <option value="Others">Others</option>
                    </select> 
                </div>
            </div>
        </div>
        <div class="row">  
            <div class="col-50-le">
                <div class="form-row">
                    <label for="location">Location</label>
                    <input type="text" name="location" placeholder="Enter Location" required>
                </div>
            </div> 
            <div class="col-25-ri">
                <div class="form-row">
                    <label for="state">State</label>
                    <select name="state" id="state">
                        <option disabled selected hidden>- Choose State -</option>
                        <option value="Johor">Johor</option>
                        <option value="Kedah">Kedah</option>
                        <option value="Kelantan">Kelantan</option>
                        <option value="Malacca">Malacca</option>
                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                        <option value="Pahang">Pahang</option>
                        <option value="Penang">Penang</option>
                        <option value="Perak">Perak</option>
                        <option value="Perlis">Perlis</option>
                        <option value="Sabah">Sabah</option>
                        <option value="Sarawak">Sarawak</option>
                        <option value="Selangor">Selangor</option>
                        <option value="Terengganu">Terengganu</option>
                        <option value="Kuala Lumpur">Kuala Lumpur</option>
                        <option value="Labuan">Labuan</option>
                        <option value="Putrajaya">Putrajaya</option>
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
                    <input type="text" name="position" placeholder="Enter Job Role">
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="company">Job Place / Company</label>
                    <input type="text" name="company" placeholder="Enter Job Place">
                </div>
            </div>
        </div>
        <div class="form-row">
            <label for="workExperience">Working Experience</label>
            <input type="text" name="workExperience" placeholder="Enter Working Experience">
        </div>
        <br>
        <br>

        <h3 class="profile-h3">Proficiency</h3>
        <div class="row">
            <div class="form-row">
                <label for="language">Language</label>
                <div class="lang-container">
                    <div>
                        <input type="checkbox" name="language[]" value="English" id="English"><label for="English">English</label>
                    </div>
                    <div>
                        <input type="checkbox" name="language[]" value="Malay" id="Malay"><label for="Malay">Malay</label>
                    </div>
                    <div>
                        <input type="checkbox" name="language[]" value="Chinese" id="Chinese"><label for="Chinese">Chinese</label>
                    </div>
                    <div>
                        <input type="checkbox" name="language[]" value="Tamil" id="Tamil"><label for="Tamil">Tamil</label>
                    </div>
                    <div class="others-wrapper">
                        <input type="checkbox" name="OthersInput" value="Others" id="Others" onclick="toggleInput()"><label for="Others">Others</label>
                        <input type="text" name="language[]" id="otherInput" placeholder="Enter others language" disabled>
                    </div>
                </div>
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
                            <div class="input-group">
                                <input type="text" name="hardSkill[]" placeholder="Enter Hard Skill">
                                <button type="button" class="add-btn" id="addHardSkill">+</button>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
            <div class="col-50-ri">
                <div class="skill-container">
                    <div class="input-row">
                        <label for="softskill">Soft Skills</label>
                        <div id="softSkillsContainer">
                            <div class="input-group">
                                <input type="text" name="softSkill[]" placeholder="Enter Soft Skill">
                                <button type="button" class="add-btn" id="addSoftSkill">+</button>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
        <br>
        <br>
        <input class="create-btn" id="create-btn" type="submit" value="Create">

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

    </form>

</div>

<br><br><br><br><br>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
function toggleInput() {
    const otherCheckbox = document.getElementById('Others');
    const otherInput = document.getElementById('otherInput');
    otherInput.disabled = !otherCheckbox.checked;
}

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
});

document.addEventListener('DOMContentLoaded', function() {
    const statusMessages = {
        'success': {
            title: 'Profile Created Successfully!',
            message: 'Your jobseeker profile has been successfully created.'
        },
        'fail': {
            title: 'Profile Creation Failed',
            message: 'Database Error: An error occur while creating your profile. Please try again.'
        },
        'invalid_name': {
            title: 'Invalid Name',
            message: 'Please enter a valid name (letters and spaces only).'
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
            message: 'Please fill in all required fields.',
        },
        'invalid_email': {
            title: 'Invalid Email',
            message: 'Please enter a valid email address.',
        },
        'invalid_phone': {
            title: 'Invalid Phone Number',
            message: 'Please enter a valid phone number (10-12 digits only).'
        },
        'invalid_age': {
            title: 'Invalid Age',
            message: 'Please enter a valid age (18-80).'
        },
        'invalid_user': {
            title: 'Invalid User',
            message: 'User ID is missing or invalid.'
        }
    };

    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    if (status && statusMessages[status]) {
        const popup = document.getElementById('popup');
        if (popup) {
            const statusInfo = statusMessages[status];
            
            const titleElement = popup.querySelector('h3');
            const messageElement = popup.querySelector('p');
            
            if (titleElement) titleElement.textContent = statusInfo.title;
            if (messageElement) messageElement.textContent = statusInfo.message;
            
            popup.classList.add('active');
        }
    }

    function setupPopupControls() {
        const popup = document.getElementById('popup');
        if (!popup) return;

        const closeElements = popup.querySelectorAll('.close-btn, .okay-btn, .overlay');
        
        closeElements.forEach(element => {
            element.addEventListener('click', function() {
                popup.classList.remove('active');
                
                if (status === 'success') {
                    window.location.href = 'jobseeker_dashboard.php';
                } else {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('status');
                    window.history.replaceState({}, '', url);
                }
            });
        });
    }
    setupPopupControls();

    const form = document.querySelector('form');
    if (form) {
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
                
                if (titleElement) titleElement.textContent = statusInfo.title;
                if (messageElement) messageElement.textContent = statusInfo.message;
                
                popup.classList.add('active');
            }
        });
    }

    const profilePicInput = document.getElementById('profilePic');
    const popup = document.getElementById('popup');
    
    profilePicInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        const titleElement = popup.querySelector('h3');
        const messageElement = popup.querySelector('p');
        
        if (!file) return;
        
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            titleElement.textContent = 'Invalid File Format';
            messageElement.textContent = 'Please upload only JPG, PNG, or GIF images.';
            popup.classList.add('active');
            
            e.target.value = '';
        }
        
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            titleElement.textContent = 'File Too Large';
            messageElement.textContent = 'The image file size must be less than 5MB.';
            popup.classList.add('active');
            
            e.target.value = '';
        }
    });
});
</script>

</body>
</html>
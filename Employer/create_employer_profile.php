<?php
include('empprofile_header.php');

function redirectWithStatus($status) {
    echo "<script>window.location.href='create_employer_profile.php?status=" . $status . "';</script>";
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
    $companyName = $_POST['companyName'];
    $jobRole = $_POST['jobRole'];
    $companyAddress = $_POST['companyAddress'];

    //Validataion
    if (!preg_match('/^[a-zA-Z ]+$/', $fullName)) {
        redirectWithStatus('invalid_name');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectWithStatus('invalid_email');
    }

    $contactNo = preg_replace('/[^0-9]/', '', $contactNo);
    if (!preg_match('/^[0-9]{9,10}$/', $contactNo)) {
        redirectWithStatus('invalid_phone');
    }
    $contactNo = '+60' . $contactNo;

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

    $fullName = mysqli_real_escape_string($con, $fullName);
    $email = mysqli_real_escape_string($con, $email);
    $contactNo = mysqli_real_escape_string($con, $contactNo);
    $companyName = mysqli_real_escape_string($con, $companyName);
    $jobRole = mysqli_real_escape_string($con, $jobRole);
    $companyAddress = mysqli_real_escape_string($con, $companyAddress);


    if(mysqli_query($con,"INSERT INTO employer
    (userID, fullName, email, contactNo, companyName, jobRole, companyAddress, profilePic)
    VALUES ('$userID','$fullName','$email','$contactNo','$companyName', '$jobRole', '$companyAddress', '$profilePic')")) 
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
            margin-bottom: 30px;
        }
        form .form-row label {
            padding: 0px 5px;
            text-align: left;
            display: flex;
            font-size: 18px;
        }
        .form-row input[type="text"],
        .form-row input[type="email"] {
            width: 100%;
            padding: 15px;
            margin-top: 5px;
            display: flex;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .phone-input-container {
            position: relative;
            width: 100%;
            margin-top: 5px;
        }
        .phone-input-container input {
            width: 100%;
            padding: 15px 15px 15px 65px !important;
            border: 1px solid #ccc;
            border-radius: 8px !important;
            font-size: 16px;
            box-sizing: border-box;
        }
        .country-code-prefix {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            padding: 0 15px;
            color: #666;
            border-right: 1px solid #ccc;
            height: 50%;
            display: flex;
            align-items: center;
            font-size: 16px;
            pointer-events: none;
            z-index: 1;
        }
        .phone-input-container input:focus, 
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="file"]:focus {
            outline: none;
            border-color: #AAE1DE;
            border-width: 2px;
            box-shadow: 0 0 0 2px rgba(170, 225, 222, 0.2);
        }
        .error .phone-input-container input {
            border: 2px solid red !important;
        }
        .form-row input[type="file"] {
            width: 100%;
            margin-top: 5px;
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
        .create-btn {
            margin-top: 20px;
        }
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
        form .col-50-le{
            float: left;
            align-items: center;
            width: 100%;
            padding: 0px 10px 0px 0px;
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
        .required::after{
            content:" *";
            color:red;
            font-size: 20px;
            margin-left: 5px;
        }
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            margin-left: 5px;
            text-align: left;
            display: none;
            position: absolute;
        }
        .error input {
            border: 2px solid red !important;
        }

        .error .error-message {
            display: block;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="profile-h1">Create Profile</h1>
    <form class="section" action='create_employer_profile.php?userID=<?PHP echo $userID; ?>' method='POST'  enctype="multipart/form-data" id="createForm">

        <h3 class="profile-h3">Personal Information</h3>
        <div class="form-row">
            <label for="fullName" class="required">Name</label>
            <input type="text" name="fullName" placeholder="Enter Full Name" data-required="true">
            <div class="error-message">This field is required</div>
        </div>
        <div class="row">    
            <div class="col-50-le">
                <div class="form-row">
                    <label for="email" class="required">Email</label>
                    <input type="email" name="email" placeholder="Enter Email" data-required="true">
                    <div class="error-message">This field is required</div>
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="contactNo" class="required">Contact No.</label>
                    <div class="phone-input-container">
                        <span class="country-code-prefix">+60</span>
                        <input type="text" name="contactNo" id="contactNo" placeholder="Enter Contact No." minlength="9" maxlength="10"data-required="true">
                    </div>
                    <div class="error-message">This field is required</div>
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
        
        <h3 class="profile-h3">Company Details</h3>
        <div class="row">
            <div class="col-50-le">
                <div class="form-row">
                    <label for="companyName">Company Name</label>
                    <input type="text" name="companyName" placeholder="Enter Company Name">
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="jobRole">Job Role/ Position</label>
                    <input type="text" name="jobRole" placeholder="Enter Job Role/ Position">
                </div>
            </div>
        </div>
        <div class="form-row">
            <label for="companyAddress">Company Address</label>
            <input type="text" name="companyAddress" placeholder="Enter Company Address">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusMessages = {
        'success': {
            title: 'Profile Created Successfully!',
            message: 'Your employer profile has been successfully created.'
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
        'invalid_email': {
            title: 'Invalid Email',
            message: 'Please enter a valid email address.',
        },
        'invalid_phone': {
            title: 'Invalid Phone Number',
            message: 'Please enter a valid phone number (9-10 digits only).'
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
                    window.location.href = 'employer_dashboard.php';
                } else {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('status');
                    window.history.replaceState({}, '', url);
                }
            });
        });
    }
    setupPopupControls();

    const form = document.getElementById('createForm');
    
    function validateField(field) {
        const formRow = field.closest('.form-row');
        if (!field.value.trim()) {
            formRow.classList.add('error');
            return false;
        } else {
            formRow.classList.remove('error');
            return true;
        }
    }

    form.querySelectorAll('input[required]').forEach(input => {
        input.removeAttribute('required');
        
        input.addEventListener('input', function() {
            validateField(this);
        });

        input.addEventListener('blur', function() {
            validateField(this);
        });
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let isValid = true;
        
        form.querySelectorAll('input[data-required="true"]').forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });

        if (!isValid) {
            const firstError = form.querySelector('.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else {
            form.submit();
        }
    });

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
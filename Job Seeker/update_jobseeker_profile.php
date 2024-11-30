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

if($_SERVER['REQUEST_METHOD'] === 'POST') 
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

    if(mysqli_query($con,"UPDATE jobseeker SET
        email='$email', contactNo='$contactNo', age='$age', gender='$gender', race='$race', location='$location', state='$state', 
        position='$position', company='$company', workExperience='$workExperience', language='$languages', hardSkill='$hardSkills', softSkill='$softSkills' WHERE userID='$userID'")) 
        {
            echo '<script>
                    window.onload = function() { 
                    document.querySelector("#success-popup").classList.add("active"); 
                    window.location.href="view_jobseeker_profile.php";
                }</script>';
        } else {
            echo '<script>
            window.onload = function() { 
            document.querySelector("#fail-popup").classList.add("active"); 
            }</script>';
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
            margin-bottom: 20px;
        }
        form .form-row label,
        form .input-row label {
            padding: 0px 5px;
            text-align: left;
            display: flex;
            font-size: 18px;
        }
        .form-row input[type="text"], 
        .form-row select {
            width: 100%;
            padding: 15px;
            margin-top: 15px;
            display: flex;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .input-group input[type="text"],
        .input-group button {
            padding: 15px;
            margin-top: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            display: flex;
            align-items: center;
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
            max-width: 350px;
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
    <form class="section" action='update_jobseeker_profile.php' method='POST'>

        <h3 class="profile-h3">Personal Information</h3>
        <div class="form-row">
            <label for="name">Name</label>
            <input type="text" name="fullName" placeholder="Enter Full Name" value="<?PHP echo $data['fullName'];?>" readonly>
        </div>
        <div class="row">    
            <div class="col-50-le">
                <div class="form-row">
                    <label for="email">Email</label>
                    <input type="text" name="email" placeholder="Enter Email" value="<?PHP echo $data['email'];?>" required>
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="contactNo">Contact No.</label>
                    <input type="text" name="contactNo" placeholder="Enter Contact No." value="<?PHP echo $data['contactNo'];?>" minlength='10' required maxlength='12' required>
                </div>
            </div>
        </div>
        <div class="row">    
            <div class="col-50-le">
                <div class="form-row">
                    <label for="age">Age</label>
                    <input type="text" name="age" placeholder="Enter Age" value="<?PHP echo $data['age'];?>" required>
                </div>
            </div>
            <div class="col-50-mid">
                <div class="form-row">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" value="<?PHP echo $data['gender'];?>">
                        <option disabled selected hidden>- Select Gender -</option>
                        <option value="Male" <?php echo $data['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $data['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                    </select> 
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="race">Race</label>
                    <select name="race" id="race" value="<?PHP echo $data['race'];?>">
                        <option disabled selected hidden>- Select Race -</option>
                        <option value="Malay" <?php echo $data['race'] === 'Malay' ? 'selected' : ''; ?>>Malay</option>
                        <option value="Chinese" <?php echo $data['race'] === 'Chinese' ? 'selected' : ''; ?>>Chinese</option>
                        <option value="Indian" <?php echo $data['race'] === 'Indian' ? 'selected' : ''; ?>>Indian</option>
                        <option value="Others" <?php echo $data['race'] === 'Others' ? 'selected' : ''; ?>>Others</option>
                    </select> 
                </div>
            </div>
        </div>
        <div class="row">  
            <div class="col-50-le">
                <div class="form-row">
                    <label for="location">Location</label>
                    <input type="text" name="location" placeholder="Enter Location" value="<?PHP echo $data['location'];?>" required>
                </div>
            </div> 
            <div class="col-25-ri">
                <div class="form-row">
                    <label for="state">State</label>
                    <select name="state" id="state" value="<?PHP echo $data['state'];?>">
                        <option disabled selected hidden>- Choose State -</option>
                        <option value="Johor" <?php echo $data['state'] === 'Johor' ? 'selected' : ''; ?>>Johor</option>
                        <option value="Kedah" <?php echo $data['state'] === 'Kedah' ? 'selected' : ''; ?>>Kedah</option>
                        <option value="Kelantan" <?php echo $data['state'] === 'Kelantan' ? 'selected' : ''; ?>>Kelantan</option>
                        <option value="Malacca" <?php echo $data['state'] === 'Malacca' ? 'selected' : ''; ?>>Malacca</option>
                        <option value="Negeri Sembilan" <?php echo $data['state'] === 'Negeri Sembilan' ? 'selected' : ''; ?>>Negeri Sembilan</option>
                        <option value="Pahang" <?php echo $data['state'] === 'Pahang' ? 'selected' : ''; ?>>Pahang</option>
                        <option value="Penang" <?php echo $data['state'] === 'Penang' ? 'selected' : ''; ?>>Penang</option>
                        <option value="Perak" <?php echo $data['state'] === 'Perak' ? 'selected' : ''; ?>>Perak</option>
                        <option value="Perlis" <?php echo $data['state'] === 'Perlis' ? 'selected' : ''; ?>>Perlis</option>
                        <option value="Sabah" <?php echo $data['state'] === 'Sabah' ? 'selected' : ''; ?>>Sabah</option>
                        <option value="Sarawak" <?php echo $data['state'] === 'Sarawak' ? 'selected' : ''; ?>>Sarawak</option>
                        <option value="Selangor" <?php echo $data['state'] === 'Selangor' ? 'selected' : ''; ?>>Selangor</option>
                        <option value="Terengganu" <?php echo $data['state'] === 'Terengganu' ? 'selected' : ''; ?>>Terengganu</option>
                        <option value="Kuala Lumpur" <?php echo $data['state'] === 'Kuala Lumpur' ? 'selected' : ''; ?>>Kuala Lumpur</option>
                        <option value="Labuan" <?php echo $data['state'] === 'Labuan' ? 'selected' : ''; ?>>Labuan</option>
                        <option value="Putrajaya" <?php echo $data['state'] === 'Putrajaya' ? 'selected' : ''; ?>>Putrajaya</option>
                    </select>
                </div>
            </div>
        </div>
        <br>
        <br>
        
        <h3 class="profile-h3">Work Experience</h3>
        <div class="row">
            <div class="col-50-le">
                <div class="form-row">
                    <label for="position">Job Role / Position</label>
                    <input type="text" name="position" placeholder="Enter Job Role" value="<?PHP echo $data['position'];?>">
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="company">Job Place / Company</label>
                    <input type="text" name="company" placeholder="Enter Job Place" value="<?PHP echo $data['company'];?>">
                </div>
            </div>
        </div>
        <div class="form-row">
            <label for="workExperience">Working Experience</label>
            <input type="text" name="workExperience" placeholder="Enter Working Experience" value="<?PHP echo $data['workExperience'];?>">
        </div>
        <br>
        <br>

        <h3 class="profile-h3">Proficiency</h3>
        <div class="row">
            <div class="form-row">
                <label for="language">Language</label>
                <input type="text" name="language" placeholder="Enter languages" value="<?PHP echo $data['language'];?>">
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
        <input class="update-btn" id="update-btn" type="submit" value="Submit">

        <!-- Success Popup -->
        <div class="popup" id="success-popup">
            <div class="overlay"></div>
            <div class="popup-content">
                <button class="close-btn">✖</button>
                <br>
                <h3>Profile Updated</h3>
                <p>Congrates! You have successfully updated your profile.</p>
                <div class="controls">
                    <button class="okay-btn">OK</button>
                </div>
            </div>
        </div>

        <!-- Fail Popup -->
        <div class="popup" id="fail-popup">
            <div class="overlay"></div>
            <div class="popup-content">
                <button class="close-btn">✖</button>
                <br>
                <h3>Failed to Update Profile</h3>
                <p>There was an issue creating your profile. Please try again later.</p>
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

$(document).on("click", ".remove-btn", function (e) {
    e.preventDefault();
    $(this).closest(".input-group").remove();
});

function updateProfile(id){
    let popupNode = document.querySelector(id);
    let overlay = popupNode.querySelector(".overlay");
    let okayBtn = popupNode.querySelector(".okay-btn");
    function openPopup(){
        popupNode.classList.add("active");
    }
    function closePopup(){
        popupNode.classList.remove("active");
    }
    overlay.addEventListener("click", closePopup);
    okayBtn.addEventListener("click", closePopup);
    return openPopup;
}
    let successPopup = updateProfile("#success-popup");
    let failPopup = updateProfile("#fail-popup");
    document.querySelector("#update-btn").addEventListener("click", popup);

    document.querySelector("#update-btn").addEventListener("click", function (e) {
    e.preventDefault();
    const form = document.querySelector("form");
    const inputs = form.querySelectorAll("[required]");
    let allValid = true;

    inputs.forEach(input => {
        if (!input.value) {
            allValid = false;
            input.classList.add("error");
        } else {
            input.classList.remove("error");
        }
    });

    if (allValid) {
        form.submit();
    } else {
        alert("Please fill out all required fields.");
    }
});
</script>

</body>
</html>
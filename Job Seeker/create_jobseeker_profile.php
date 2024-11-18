<?php
session_start();

if(!empty($_POST)) 
{   
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $contactNo = $_POST['ContactNo'];
    $age = $_POST['Age'];
    $gender = $_POST['Gender'];
    $race = $_POST['Race'];
    $district = $_POST['District'];
    $state = $_POST['State'];
    $position = $_POST['Position'];
    $yearExperience = $_POST['YearExperience'];
    $company = $_POST['Company'];
    $language = $_POST['Language'];
    $languages = implode(", ",$language);
    $hardSkill = $_POST['HardSkill'];
    $hardSkills = implode(", ",$hardSkill);
    $softSkill = $_POST['SoftSkill'];
    $softSkills = implode(", ",$softSkill);

    include('config.php');

    $name = mysqli_real_escape_string($con,$name);
    $email = mysqli_real_escape_string($con,$email);
    $contactNo = mysqli_real_escape_string($con,$contactNo);
    $age = mysqli_real_escape_string($con,$age);
    $gender = mysqli_real_escape_string($con,$gender);
    $race = mysqli_real_escape_string($con,$race);
    $district = mysqli_real_escape_string($con,$district);
    $state = mysqli_real_escape_string($con,$state);
    $position = mysqli_real_escape_string($con,$position);
    $company = mysqli_real_escape_string($con,$company);
    $yearExperience = mysqli_real_escape_string($con,$yearExperience);

    if(mysqli_query($con,"INSERT INTO jobseeker
        (Name, Email, ContactNo, Age, Gender, Race, District, State, Position, YearExperience, Company, Language, HardSkill, SoftSkill)
        VALUES ('$name','$email','$contactNo','$age', '$gender','$race','$district','$state','$position','$yearExperience','$company','$languages','$hardSkills','$softSkills')")) 
        {
            echo '<script>
                    window.onload = function() { 
                    document.querySelector("#success-popup").classList.add("active"); 
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
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <title>Create Profile</title>
    <style>
        body {
            font-family: sans-serif;
            font-family: "Poppins", sans-serif;
        }
        .container {
            position: relative;
            margin: auto;
            width: 85%;
            min-height: 90vh;
            background: linear-gradient(to right bottom,rgba(255, 255, 255, 0.5),rgba(255, 255, 255, 0.3));
            padding: 1.5rem;
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5), 0 0 25px rgba(0, 0, 0, 0.08);
        }
        .container h1 {
            text-align: center;   
            font-size: 40px;
            margin-top: 80px;
        }
        .container h3 {
            text-align: center;  
            margin-bottom: 30px;
            font-size: 22px;
        }
        .container form {
            display: flex;
            flex-direction: column;
            text-align: center;  
            align-items: center;
            margin-top: 15px;
            border: 1px solid #ccc;
            padding: 0px 20px 20px;
            border-radius: 10px;
            width: 70%;
            box-sizing: border-box;
            margin: auto;
        }
        .container form .form-row {
            align-items: center;
            width: 100%;
            margin-bottom: 20px;
        }
        .container form .form-row label,
        .container form .input-row label {
            padding: 0px 5px;
            text-align: left;
            display: flex;
            font-size: 18px;
        }
        .container .form-row input[type="text"], 
        .container .form-row select {
            width: 100%;
            padding: 15px;
            margin-top: 15px;
            display: flex;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .container .input-group input[type="text"],
        .container .input-group button {
            padding: 15px;
            margin-top: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            display: flex;
            align-items: center;
        }
        .container .back-btn,
        .container .create-btn {
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
        .container .back-btn {
            margin-top: 80px;
        }
        .container .create-btn {
            margin-top: 20px;
        }
        .container .back-btn:hover,
        .container .create-btn:hover {
            color: black;
            background-color: #b2eff1;
            box-shadow: 0 0 15px 5px rgba(255,255,255,0.75);
        }
        .container form .row{
            align-items: center;
            width: 100%;
            margin-bottom: 20px;
            display: flex;
            margin: 0px;
            align-items: flex-start;
        }
        .container form .skill-container{
            width: 100%;
            margin-bottom: 20px;
            margin: 0px;
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }
        .container form .col-50-le{
            float: left;
            align-items: center;
            width: 100%;
            padding: 0px 10px 0px 0px;
        }
        .container form .col-50-mid{
            float: center;
            align-items: center;
            width: 100%;
            padding: 0px 10px;
        }
        .container form .col-50-ri{
            float: right;
            align-items: center;
            width: 100%;
            padding: 0px 0px 0px 10px;
        }
        .container form .col-25-ri{
            float: right;
            align-items: center;
            width: 50%;
            padding: 0px 0px 0px 10px;
        }
        .container form .input-row {
            display: flex;
            flex-direction: column;
            width: 90%;
        }
        .container form .input-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .container form .input-row .input-group input[type="text"] {
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
    <h1>Create Profile</h1>
    <form class="section" action='create_jobseeker_profile.php' method='POST'>

        <h3>Personal Information</h3>
        <div class="form-row">
            <label for="Name">Name</label>
            <input type="text" name="Name" placeholder="Enter Full Name"required>
        </div>
        <div class="row">    
            <div class="col-50-le">
                <div class="form-row">
                    <label for="Email">Email</label>
                    <input type="text" name="Email" placeholder="Enter Email" required>
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="ContactNo">Contact No.</label>
                    <input type="text" name="ContactNo" placeholder="Enter Contact No." minlength='10' required maxlength='12' required>
                </div>
            </div>
        </div>
        <div class="row">    
            <div class="col-50-le">
                <div class="form-row">
                    <label for="Age">Age</label>
                    <input type="text" name="Age" placeholder="Enter Age" required>
                </div>
            </div>
            <div class="col-50-mid">
                <div class="form-row">
                    <label for="Gender">Gender</label>
                    <select name="Gender" id="Gender">
                        <option disabled selected hidden>- Select Gender -</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select> 
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="Race">Race</label>
                    <select name="Race" id="Race">
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
                    <label for="District">District</label>
                    <input type="text" name="District" placeholder="Enter District" required>
                </div>
            </div> 
            <div class="col-25-ri">
                <div class="form-row">
                    <label for="State">State</label>
                    <select name="State" id="State">
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
        <br>
        <br>
        
        <h3>Work Experience</h3>
        <div class="row">
            <div class="col-50-le">
                <div class="form-row">
                    <label for="position">Job Role / Position</label>
                    <input type="text" name="Position" placeholder="Enter Job Role">
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="exp-year">Year of Experience</label>
                    <input type="text" name="YearExperience" placeholder="Enter Number of Year Experience">
                </div>
            </div>
        </div>
        <div class="form-row">
            <label for="company">Job Place / Company</label>
            <input type="text" name="Company" placeholder="Enter Job Place">
        </div>
        <br>
        <br>

        <h3>Proficiency</h3>
        <div class="row">
            <div class="form-row">
                <label for="language">Language</label>
                <div class="lang-container">
                    <div>
                        <input type="checkbox" name="Language[]" value="English" id="English"><label for="English">English</label>
                    </div>
                    <div>
                        <input type="checkbox" name="Language[]" value="Malay" id="Malay"><label for="Malay">Malay</label>
                    </div>
                    <div>
                        <input type="checkbox" name="Language[]" value="Chinese" id="Chinese"><label for="Chinese">Chinese</label>
                    </div>
                    <div>
                        <input type="checkbox" name="Language[]" value="Tamil" id="Tamil"><label for="Tamil">Tamil</label>
                    </div>
                    <div class="others-wrapper">
                        <input type="checkbox" name="OthersInput" value="Others" id="Others" onclick="toggleInput()"><label for="Others">Others</label>
                        <input type="text" name="Language[]" id="otherInput" placeholder="Enter others language" disabled>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>

        <h3>Skills</h3>
        <div class="row">
            <div class="col-50-le">
                <div class="skill-container">
                    <div class="input-row">
                        <label for="hardskill">Hard Skills</label>
                        <div id="hardSkillsContainer">
                            <div class="input-group">
                                <input type="text" name="HardSkill[]" placeholder="Enter Hard Skill">
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
                                <input type="text" name="SoftSkill[]" placeholder="Enter Soft Skill">
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

        <!-- Success Popup -->
        <div class="popup" id="success-popup">
            <div class="overlay"></div>
            <div class="popup-content">
                <button class="close-btn">✖</button>
                <br>
                <h3>Profile Created</h3>
                <p>Congrates! You have successfully created your profile.</p>
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
                <h3>Failed to Create Profile</h3>
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
            <input type="text" name="HardSkill[]" placeholder="Enter Hard Skill">
            <button type="button" class="remove-btn"><i class="fa-regular fa-trash-can"></i></button>
        </div>
    `);
});

$(document).on("click", "#addSoftSkill", function (e) {
    e.preventDefault();
    $("#softSkillsContainer").append(`
        <div class="input-group">
            <input type="text" name="SoftSkill[]" placeholder="Enter Soft Skill">
            <button type="button" class="remove-btn"><i class="fa-regular fa-trash-can"></i></button>
        </div>
    `);
});

$(document).on("click", ".remove-btn", function (e) {
    e.preventDefault();
    $(this).closest(".input-group").remove();
});

function createProfile(id){
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
    let successPopup = createProfile("#success-popup");
    let failPopup = createProfile("#fail-popup");
    document.querySelector("#create-btn").addEventListener("click", popup);

    document.querySelector("#create-btn").addEventListener("click", function (e) {
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
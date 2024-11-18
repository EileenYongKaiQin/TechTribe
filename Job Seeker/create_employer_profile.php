<?php
session_start();

if(!empty($_POST)) 
{   
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $contactNo = $_POST['ContactNo'];
    $companyName = $_POST['CompanyName'];
    $companyAddress = $_POST['CompanyAddress'];

    include('config.php');

    $name = mysqli_real_escape_string($con,$name);
    $email = mysqli_real_escape_string($con,$email);
    $contactNo = mysqli_real_escape_string($con,$contactNo);
    $companyName = mysqli_real_escape_string($con,$companyName);
    $companyAddress = mysqli_real_escape_string($con,$companyAddress);

    if(mysqli_query($con,"INSERT INTO employer
        (Name, Email, ContactNo, CompanyName, CompanyAddress)
        VALUES ('$name','$email','$contactNo','$companyName', '$companyAddress')")) 
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
        .container form .form-row label {
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
        .popup {
            position: fixed;
            top: 100vh;
            left: 0px;
            width: 100%;
            height: 100%;
            z-index: 1;
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
        .popup .popup-content h2{
            margin: 10px 0px;
            font-size: 25px;
            color: #111111;
            text-align: center;
        }
        .popup .popup-content p{
            margin: 15px 0px;
            font-size: 16px;
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
    <form class="section" action='create_employer_profile.php' method='POST'>

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
                    <label for="companyName">Company Name</label>
                    <input type="text" name="CompanyName" placeholder="Enter Company Name">
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="companyAddress">Company Address</label>
                    <input type="text" name="CompanyAddress" placeholder="Enter Company Address">
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

<script src="jquery-3.7.1.min.js"></script>
<script>
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
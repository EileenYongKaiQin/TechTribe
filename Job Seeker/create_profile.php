<?php
session_start();
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
            width: 100%;
            padding: 0px 5px;
            margin-right: 10px;
            text-align: left;
            display: block;
            font-size: 18px;
        }
        .container .form-row input[type="text"]{
            width: 100%;
            padding: 15px;
            margin-top: 15px;
            display: inline-block;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .container .back-btn,
        .container .update-btn {
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
        .container .update-btn {
            margin-top: 20px;
        }
        .container .back-btn:hover,
        .container .update-btn:hover {
            color: black;
            background-color: #b2eff1;
            box-shadow: 0 0 15px 5px rgba(255,255,255,0.75);
        }
        .container form .row{
            align-items: center;
            width: 100%;
            margin-bottom: 20px;
            display: flex;
            margin: 0 -16px;
        }
        .container form .col-50-le{
            float: left;
            align-items: center;
            width: 100%;
            padding: 0px 10px 0px 0px;
        }
        .container form .col-50-ri{
            float: right;
            align-items: center;
            width: 100%;
            padding: 0px 0px 0px 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Create Profile</h1>
    <form class="section" action='create_profile.php' method='POST'>
        <h3>Personal Information</h3>
        <div class="row">
            <div class="col-50-le">
                <div class="form-row">
                    <label for="Name">Name</label>
                    <input type="text" name="Name" required>
                </div>
                <div class="form-row">
                    <label for="Email">Email</label>
                    <input type="text" name="Email" required>
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="contactno">Contact No.</label>
                    <input type="text" name="ContactNo" required minlength='10' required maxlength='12' required>
                </div>

                <div class="form-row">
                    <label for="location">Location</label>
                    <input type="text" name="Location" required>
                </div>
            </div>
        </div>
        <hr>
        <h3>Education</h3>
        <div class="row">
            <div class="col-50-le">
                <div class="form-row">
                    <label for="study">Field of Study</label>
                    <input type="text" name="StudyField" required>
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="gra-year">Year of Graduation</label>
                    <input type="text" name="GraduateYear" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <label for="institution">Institution</label>
            <input type="text" name="Institution" required>
        </div>
        <hr>
        <h3>Work Experience</h3>
        <div class="row">
            <div class="col-50-le">
                <div class="form-row">
                    <label for="position">Position</label>
                    <input type="text" name="Position" required>
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="exp-year">Year of Experience</label>
                    <input type="text" name="ExperienceYear" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <label for="company">Company</label>
            <input type="text" name="Company" required>
        </div>
        <br>
        <h3>Skills</h3>
        <div class="row">
            <div class="col-50-le">
                <div class="form-row">
                    <label for="hardskill">Hard Skills</label>
                    <input type="text" name="HardSkill1" required>
                    <input type="text" name="HardSkill2">
                </div>
            </div>
            <div class="col-50-ri">
                <div class="form-row">
                    <label for="softskill">Soft Skills</label>
                    <input type="text" name="SoftSkill1" required>
                    <input type="text" name="SoftSkill2">
                </div>
            </div>
        </div>

        <input class="update-btn" type="submit" value="Update">
    </form>
</div>
<br><br><br><br><br>

</body>
</html>
<?php
include('jobSeeker.php');

if(!empty($_POST)) 
{
    $name=$_POST['Name'];
    $email=$_POST['Email'];
    $contactNo=$_POST['ContactNo'];
    $location=$_POST['Location'];
    $study=$_POST['StudyField'];
    $graduateYear=$_POST['GraduateYear'];
    $institution=$_POST['Institution'];
    $position=$_POST['Position'];
    $company=$_POST['Company'];
    $experienceYear=$_POST['ExperienceYear'];
    $hardski1=$_POST['HardSkill1'];
    $hardski2=$_POST['HardSkill2'];
    $softski1=$_POST['SoftSkill1'];
    $softski2=$_POST['SoftSkill2'];

    include('../database/config.php');

    $name = mysqli_real_escape_string($con,$name);
    $email = mysqli_real_escape_string($con,$email);
    $contactNo = mysqli_real_escape_string($con,$contactNo);
    $location = mysqli_real_escape_string($con,$location);
    $study = mysqli_real_escape_string($con,$study);
    $graduateYear = mysqli_real_escape_string($con,$graduateYear);
    $institution = mysqli_real_escape_string($con,$institution);
    $position = mysqli_real_escape_string($con,$position);
    $company = mysqli_real_escape_string($con,$company);
    $experienceYear = mysqli_real_escape_string($con,$experienceYear);
    $hardski1 = mysqli_real_escape_string($con,$hardski1);
    $hardski2 = mysqli_real_escape_string($con,$hardski2);
    $softski1 = mysqli_real_escape_string($con,$softski1);
    $softski2 = mysqli_real_escape_string($con,$softski2);

    if(mysqli_query($con,"INSERT INTO jobseeker
        (Name, Email, ContactNo, Location, StudyField, GraduateYear, Institution, Position, Company, ExperienceYear, HardSkill1, HardSkill2, SoftSkill1, SoftSkill2)
        VALUES ('$name','$email','$contactNo','$location','$study ','$graduateYear','$institution', '$position', '$company', '$experienceYear', '$hardski1', 
        '$hardski2', '$softski1', '$softski2')")) 
        {
        echo "<script>
        alert('Tahniah! Anda telah berjaya didaftar! Sila tunggu pengaktifan daripada Admin')
        window.location.href='create_profile.php';
        </script>";
        }

    else {
        echo "<script>
        alert('Maaf, pendaftaran gagal! Sila hubungi Admin')
        window.history.back();
        </script>";
    } 

    include('../footer/footer.php');
}?>
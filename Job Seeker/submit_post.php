<?php
    include('../database/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = "Ali";
    $skillCategory = $_POST['skills'];
    $skillDetails = $_POST['skillDetails'];
    $availability = json_encode([
        'monday' => [$_POST['mondayStart'], $_POST['mondayEnd']],
        'tuesday' => [$_POST['tuesdayStart'], $_POST['tuesdayEnd']],
        'wednesday' => [$_POST['wednesdayStart'], $_POST['wednesdayEnd']],
        'thursday' => [$_POST['thursdayStart'], $_POST['thursdayEnd']],
        'friday' => [$_POST['fridayStart'], $_POST['fridayEnd']],
        'saturday' => [$_POST['saturdayStart'], $_POST['saturdayEnd']],
        'sunday' => [$_POST['sundayStart'], $_POST['sundayEnd']]
    ]);
    $state = $_POST['state'];
    $district = $_POST['district'];
    $jobPreferences = $_POST['jobPreferences'];
    $contactEmail = $_POST['contactEmail'];
    $contactPhone = $_POST['contactPhone'];

    $stmt = $con->prepare("INSERT INTO wall_posts (user_name, skill_category, skill_details, availability, state, district, job_preferences, contact_email, contact_phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $username, $skillCategory, $skillDetails, $availability, $state, $district, $jobPreferences, $contactEmail, $contactPhone);    
    $stmt->execute();
    $stmt->close();
    $con->close();

    header("Location: job_seeker_wall.php");
    exit();
}


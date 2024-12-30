<?php
    include('../database/config.php');
    include('jobSeeker1.php');

    $historyID = $_GET['historyID']?? null;
    $userID = $_SESSION['userID']?? null;
    if(!$historyID || !$userID) {
        $_SESSION['flash_message'] = "Invalid request";
        header('Location: job_history.php');
        exit();
    }

    $sql = "SELECT jp.jobTitle, jp.venue
            FROM jobHistory h
            JOIN jobPost jp ON h.jobPostID = jp.jobPostID
            WHERE h.historyID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $historyID);
    $stmt->execute();
    $result = $stmt->get_result();
    $job = $result->fetch_assoc();

    if(!$job) {
        $_SESSION['flash_message'] = "Job not found";
        header('Location: job_history.php');
        exit();
    } 

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rating = $_POST['rating'] ?? null;
        $feedback = $_POST['feedback'] ?? null;

        if(!$rating) {
            $_SESSION['flash message'] = "Please provide a valid rating between 1 and 5.";
        } else {
            $sql = "INSERT INTO jobRating (historyID, userID, rating, feedback) VALUES (?, ?, ?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("isis", $historyID, $userID, $rating, $feedback);

            if($stmt->execute()) {
                echo "<script>
                alert('Thank you for your feedback!');
                window.location.href = 'rate_complete.php';
                </script>";
                exit();
            } else {
                $_SESSION['flash_message'] = "Failed to submit your feedback. Please try again.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Job Experience</title>
    <link rel="stylesheet" href="../css/rate_job.css">
    <style>
        button {
            background: #8EFAAB; /* Green color */
            cursor: pointer;
            color: #FFFFFF; /* Make text white */
            margin-left: 40%;
        }

        .required {
            color: red;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="rectangle">
                <div class="job-header">
                    <p class="job-ask">What do you think of this job?</p>
                    <h3><?= htmlspecialchars($job['jobTitle']) ?> (<?= htmlspecialchars($job['venue']) ?>)</h3>
                </div>
                <form action="" id="ratingForm" method="post" novalidate>
                    <div class="rating-section">
                        <h3>Your Rating<span class="required"> *</span></h3>
                        <div class="rating-stars">
                            <img src="../images/rating-star-before.png" alt="Star 1" data-value = "1">
                            <img src="../images/rating-star-before.png" alt="Star 2" data-value = "2">
                            <img src="../images/rating-star-before.png" alt="Star 3" data-value = "3">
                            <img src="../images/rating-star-before.png" alt="Star 4" data-value = "4">
                            <img src="../images/rating-star-before.png" alt="Star 5" data-value = "5">
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" required>
                    </div>

                    <div class="feedback-section">
                        <h3>Tell others more about the job experience</h3>
                        <textarea name="feedback" placeholder="To make reviews more useful, you can talk about the company's features and other details."></textarea>
                    </div>
            </div>
                    <button type="submit" id="submit-btn" class="submit-btn">Submit</button>
                </form>
            
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
    const stars = document.querySelectorAll(".rating-stars img");
    const ratingInput = document.getElementById("ratingInput");
    const submitBtn = document.getElementById("submit-btn");
    const form = document.getElementById("ratingForm");

    // Add click event listener to each star
    stars.forEach((star, index) => {
        star.addEventListener("click", () => {
            const rating = index + 1; // Determine the selected rating
            updateStars(rating); // Update the stars' appearance
            ratingInput.value = rating; // Update the hidden input value
        });
    });

    // Update the stars' appearance based on the rating
    function updateStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.src = "../images/rating-star-after.png"; // Highlight selected stars
            } else {
                star.src = "../images/rating-star-before.png"; // Reset unselected stars
            }
        });
    }

    form.addEventListener("submit", (event) => {
        const rating = ratingInput.value;
        if (!rating) {
            alert("Please provide a rating.");
            event.preventDefault(); // Prevent form submission
        }
    });
});
    </script>
</body>
</html>
<?php
include('../database/config.php');

// Get the post_id from the query string
if (!isset($_GET['postID'])) {
    die('Post ID is missing.');
}
$post_id = $_GET['postID'];

// Fetch the post data from the database
$query = "SELECT * FROM wallPost WHERE postID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Post not found.');
}

$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Wall Post</title>
    <link rel="stylesheet" href="../css/create_wall_post.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include('JobSeeker1.php'); ?>

    <div class="main-content">
        <div class="header-bar">
            <a href="my_posts.php" class="close-button">&times;</a>
            <h2>Edit Wall Post</h2>            
        </div>
        <form id="editPostForm" onsubmit="confirmSubmit(event)" action="update_wall_post.php" method="POST">
            <!-- Hidden field to store the post ID -->
            <input type="hidden" name="postID" value="<?php echo $post['postID']; ?>">

            <label for="skillCategory">Skill:</label>
            <select id="skillCategory" name="skillCategory" required>
                <option value="" disabled>Select a category</option>
                <?php
                $skillCategories = [
                    "Customer Service", "Technology", "Photographic", "Creative Skills", "Teaching and Tutoring",
                    "Writing and Editing", "Sales and Marketing", "Retail Support", "Event Staff",
                    "Food and Beverage Service", "Delivery and Logistics", "Administrative Support", "Data Entry",
                    "Social Media Management", "Warehouse Assistance", "Cleaning and Housekeeping", "Childcare",
                    "Pet Care", "Fitness and Coaching", "Personal Assistance", "Others"
                ];
                // Use the correct column name: 'skillCategory'
                foreach ($skillCategories as $skillCategory) {
                    $selected = isset($post['skillCategory']) && trim($post['skillCategory']) === $skillCategory ? 'selected' : '';
                    echo "<option value='$skillCategory' $selected>$skillCategory</option>";
                }
                ?>
            </select><br>

            <label for="skillDetails">Skill Details:</label><br>
            <textarea id="skillDetails" name="skillDetails" rows="4" maxlength="250" required><?php echo htmlspecialchars($post['skillDetails']); ?></textarea><br>

            <label>Available Time:</label>
            <div class="availability-slots">
                <?php
                $availableTime = json_decode($post['availableTime'], true) ?? [];
                foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day) {
                    $start = isset($availableTime[$day][0]) ? htmlspecialchars($availableTime[$day][0]) : '';
                    $end = isset($availableTime[$day][1]) ? htmlspecialchars($availableTime[$day][1]) : '';
                    echo "
                        <div class='availability-slot'>
                            <label>" . ucfirst($day) . "</label>
                            <input type='time' name='{$day}Start' value='$start' placeholder='Start Time'>
                            <input type='time' name='{$day}End' value='$end' placeholder='End Time'>
                        </div>
                    ";
                }
                
                ?>
            </div>

            <div class="location-section">
                <label for="state">Location:</label>
                <div class="location-selects">
                    <select id="state" name="state" required onchange="updateDistricts()">
                        <option value="">Select a State</option>
                        <?php
                        $states = [
                            "Johor", "Kedah", "Kelantan", "Kuala Lumpur", "Melaka",
                            "Negeri Sembilan", "Pahang", "Penang", "Perak", "Perlis",
                            "Sabah", "Sarawak", "Selangor", "Terengganu"
                        ];
                        foreach ($states as $state) {
                            $selected = $post['state'] === $state ? 'selected' : '';
                            echo "<option value='$state' $selected>$state</option>";
                        }
                        ?>
                    </select>

                    <select id="district" name="district" required>
                        <option value="">Select a District</option>
                        <option value="<?php echo $post['district']; ?>" selected><?php echo $post['district']; ?></option>
                    </select>
                </div>
            </div>

            <label for="jobPreferences">Job Preferences (max 50 words):</label>
            <textarea id="jobPreferences" name="jobPreferences" rows="3" maxlength="250"><?php echo htmlspecialchars($post['jobPreferences']); ?></textarea><br>

            <button type="submit">Save</button>
        </form>
    </div>

    <script>
        const districtsByState = {
            "Johor": ["Johor Bahru", "Batu Pahat", "Kluang", "Kota Tinggi", "Muar", "Segamat", "Pontian", "Mersing"],
            "Kedah": ["Alor Setar", "Kulim", "Langkawi", "Baling", "Pendang", "Sik"],
            "Kelantan": ["Kota Bharu", "Bachok", "Pasir Mas", "Tanah Merah", "Machang", "Tumpat"],
            // Add all states and their districts here
        };

        function updateDistricts() {
            const stateSelect = document.getElementById("state");
            const districtSelect = document.getElementById("district");
            const selectedState = stateSelect.value;

            districtSelect.innerHTML = '<option value="">Select a District</option>';

            if (districtsByState[selectedState]) {
                districtsByState[selectedState].forEach(district => {
                    const option = document.createElement("option");
                    option.value = district;
                    option.textContent = district;
                    districtSelect.appendChild(option);
                });
            }
        }

        function confirmSubmit(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to update this post?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById("editPostForm").submit();
                }
            });
        }
    </script>

    <?php include('../footer/footer.php'); ?>
</body>
</html>

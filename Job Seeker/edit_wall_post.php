<?php
include('../database/config.php');

// Get the post_id from the query string
if (!isset($_GET['post_id'])) {
    die('Post ID is missing.');
}
$post_id = $_GET['post_id'];

// Fetch the post data from the database
$query = "SELECT * FROM wall_posts WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $post_id);
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
    <?php include('header.php'); ?>

    <div class="full-page-form">
        <a href="my_posts.php" class="close-button">&times;</a>
        <h2>Edit Wall Post</h2>
        <form id="editPostForm" onsubmit="confirmSubmit(event)" action="update_post.php" method="POST">
            <!-- Hidden field to store the post ID -->
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">

            <label for="skills">Skill:</label>
            <select id="skills" name="skills" required>
                <option value="" disabled>Select a category</option>
                <?php
                $skills = [
                    "Customer Service", "Technology", "Photographic", "Creative Skills", "Teaching and Tutoring",
                    "Writing and Editing", "Sales and Marketing", "Retail Support", "Event Staff",
                    "Food and Beverage Service", "Delivery and Logistics", "Administrative Support", "Data Entry",
                    "Social Media Management", "Warehouse Assistance", "Cleaning and Housekeeping", "Childcare",
                    "Pet Care", "Fitness and Coaching", "Personal Assistance", "Others"
                ];
                foreach ($skills as $skill) {
                    $selected = $post['skills'] === $skill ? 'selected' : '';
                    echo "<option value='$skill' $selected>$skill</option>";
                }
                ?>
            </select><br>

            <label for="skillDetails">Skill Details:</label><br>
            <textarea id="skillDetails" name="skillDetails" rows="4" maxlength="250" required><?php echo htmlspecialchars($post['skill_details']); ?></textarea><br>

            <label>Availability:</label>
            <div class="availability-slots">
                <?php
                $availability = json_decode($post['availability'], true);
                foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
                    $start = $availability[$day][0] ?? '';
                    $end = $availability[$day][1] ?? '';
                    echo "
                        <div class='availability-slot'>
                            <label>" . ucfirst($day) . "</label>
                            <input type='time' name='{$day}Start' value='" . htmlspecialchars($start) . "' placeholder='Start Time'>
                            <input type='time' name='{$day}End' value='" . htmlspecialchars($end) . "' placeholder='End Time'>
                        </div>
                    ";
                }
                ?>
            </div>


            <label for="state">State:</label>
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

            <label for="district">District:</label>
            <select id="district" name="district" required>
                <option value="">Select a District</option>
                <option value="<?php echo $post['district']; ?>" selected><?php echo $post['district']; ?></option>
            </select>

            <label for="jobPreferences">Job Preferences (max 50 words):</label>
            <textarea id="jobPreferences" name="jobPreferences" rows="3" maxlength="250"><?php echo htmlspecialchars($post['job_preferences']); ?></textarea><br>

            <label for="contactEmail">Email:</label>
            <input type="email" id="contactEmail" name="contactEmail" style="height:30px" value="<?php echo htmlspecialchars($post['contact_email']); ?>" required><br>

            <label for="contactPhone">Phone Number:</label>
            <input type="tel" id="contactPhone" name="contactPhone" style="height:30px" value="<?php echo htmlspecialchars($post['contact_phone']); ?>" required
                   pattern="\d{10,11}" maxlength="11" minlength="10"><br>

            <button type="submit">Update Post</button>
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

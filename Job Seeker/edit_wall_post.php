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

            <label for="skills"><b>Skill</b><span class="required">*</span></label>
            <select id="skillCategory" name="skillCategory" required>
                <option value="" disabled>Select a category</option>
                <?php
                $skillCategories = [
                    "Customer Service", "Technology", "Photography", "Accounting", 
                    "Creative Skills", "Teaching and Tutoring", "Writing and Editing", 
                    "Sales and Marketing", "Retail Support", "Event Staff", "Food and Beverage Service", 
                    "Delivery and Logistics", "Administrative Support", "Data Entry", "Social Media Management", 
                    "Warehouse Assistance", "Cleaning and Housekeeping", "Childcare", "Pet Care", 
                    "Fitness and Coaching", "Personal Assistance", "Others"
                ];
                // Use the correct column name: 'skillCategory'
                foreach ($skillCategories as $skillCategory) {
                    $selected = isset($post['skillCategory']) && trim($post['skillCategory']) === $skillCategory ? 'selected' : '';
                    echo "<option value='$skillCategory' $selected>$skillCategory</option>";
                }
                ?>
            </select><br>

            <label for="skillDetails"><b>Skill Details</b><span class="required">*</span></label>
            <textarea id="skillDetails" name="skillDetails" rows="4" maxlength="100" required><?php echo htmlspecialchars($post['skillDetails']); ?></textarea><br>

            <label><b>Available Time</b> <span class="remark" style="font-size:10px;">(Time Interval: 10 minutes)</span><span class="required">*</span></label>
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
            <label for="state"><b>Location</b><span class="required">*</span></label><br>
                <div class="location-selects">
                    <select id="state" name="state" required onchange="updateDistricts()">
                        <option value="">Select a State</option>
                        <?php
                        $states = [
                            "Johor", "Kedah", "Kelantan", "Kuala Lumpur", "Melaka",
                            "Sembilan", "Pahang", "Penang", "Perak", "Perlis",
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

            <label for="jobPreferences"><b>Job Preferences (Optional):</b></label>
            <textarea id="jobPreferences" name="jobPreferences" rows="4" maxlength="100"><?php echo htmlspecialchars($post['jobPreferences']); ?></textarea><br>

            <button type="submit">Save</button>
        </form>
    </div>

    <script>
        const districtsByState = {
            "Johor": ["Johor Bahru", "Batu Pahat", "Kluang", "Kota Tinggi", "Muar", "Segamat", "Pontian", "Mersing", "Tangkak", "Segamat", "Ledang"],
            "Kedah": ["Alor Setar", "Kulim", "Langkawi", "Baling", "Pendang", "Sik", "Kubang Pasu", "Padang Terap", "Kota Setar", "Bandar Baharu"],
            "Kelantan": ["Kota Bharu", "Bachok", "Pasir Mas", "Tanah Merah", "Machang", "Tumpat", "Gua Musang", "Jeli", "Kuala Krai"],
            "Kuala Lumpur": ["Bukit Bintang", "Cheras", "Kepong", "Lembah Pantai", "Segambut", "Wangsa Maju", "Setiawangsa", "Titiwangsa", "Bandar Tun Razak"],
            "Melaka": ["Alor Gajah", "Melaka Tengah", "Jasin"],
            "Negeri Sembilan": ["Seremban", "Port Dickson", "Jempol", "Rembau", "Tampin", "Kuala Pilah", "Seremban", "Nilai"],
            "Pahang": ["Kuantan", "Bentong", "Cameron Highlands", "Jerantut", "Raub", "Pekan", "Lipis", "Bera"],
            "Penang": ["George Town", "Balik Pulau", "Butterworth", "Batu Ferringhi", "Seberang Perai", "Prai", "Nibong Tebal"],
            "Perak": ["Ipoh", "Taiping", "Teluk Intan", "Batu Gajah", "Sungai Siput", "Manjung", "Parit Buntar", "Kuala Kangsar", "Bidor"],
            "Perlis": ["Kangar", "Arau", "Padang Besar"],
            "Sabah": ["Kota Kinabalu", "Sandakan", "Tawau", "Lahad Datu", "Keningau", "Beaufort", "Ranau", "Papar"],
            "Sarawak": ["Kuching", "Sibu", "Miri", "Bintulu", "Samarahan", "Sri Aman", "Mukah", "Kapit", "Betong"],
            "Selangor": ["Shah Alam", "Petaling Jaya", "Klang", "Ampang", "Gombak", "Subang Jaya", "Kajang", "Sepang", "Hulu Langat", "Kuala Selangor"],
            "Terengganu": ["Kuala Terengganu", "Kemaman", "Besut", "Dungun", "Setiu", "Marang", "Hulu Terengganu"],
            "Putrajaya": ["Putrajaya", "Cyberjaya"], // Add Putrajaya districts
            "Labuan": ["Labuan"] // Add Labuan district
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

        // Confirm form submission
        function confirmSubmit(event) {
            event.preventDefault(); // Prevent form from submitting immediately


            // Get all the availability inputs for start and end times
            const availabilitySlots = document.querySelectorAll('.availability-slot');
            let notAllEmpty = false;
            let isValid = true;

            // Loop through each availability slot to check if both start and end times are selected
            availabilitySlots.forEach(slot => {
                const startTime = slot.querySelector('input[name$="Start"]').value;
                const endTime = slot.querySelector('input[name$="End"]').value;

                // Check if both start and end time are filled
                if (startTime && endTime) {
                    notAllEmpty = true;
                }
                if ((startTime && !endTime) || (!startTime && endTime)){
                    isValid = false;
                }
            });


            // If no valid pair of times is found, show the alert
            if (!notAllEmpty) {
                alert("Please ensure at least one day has valid start and end times.");
                return; // Stop further processing
            }
            if (!isValid) {
                alert("Please ensure no empty start/end time");
                return; // Stop further processing
            }
            // If valid time is selected, show the SweetAlert confirmation
            Swal.fire({
                title: 'Are you sure you want to update this post?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'custom-swal',
                    confirmButton: 'swal-confirm-btn',
                    cancelButton: 'swal-cancel-btn'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if user confirmed
                    document.getElementById("editPostForm").submit();
                }
            });
        }

        document.querySelector('.swal-confirm-btn').style.backgroundColor = '#f00';
        document.querySelector('.swal-cancel-btn').style.backgroundColor = '#ccc';

        function validateTime(input) {
            const slot = input.closest('.availability-slot');
            const startInput = slot.querySelector('input[name$="Start"]');
            const endInput = slot.querySelector('input[name$="End"]');
            
            // Check if both start and end times are filled
            if (startInput.value && endInput.value) {
                const startTime = new Date(`1970-01-01T${startInput.value}Z`);
                const endTime = new Date(`1970-01-01T${endInput.value}Z`);

                // Check if end time is earlier or equal to start time
                if (endTime <= startTime) {
                    alert("End time must be after start time.");
                    input.value = ""; // Clear invalid value
                }
            }
        }
    </script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job-Seeker Wall Post</title>
    <link rel="stylesheet" href="../css/create_wall_post.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
</head>
<body>
    <?php   
        include('JobSeeker1.php');
    ?>
    <div class="main-content">
        
        <div class="header-bar">
            <a href="job_seeker_wall.php" class="close-button">&times;</a>
            <h2>Create Wall Post</h2>            
        </div>

        <form id="createPostForm"  onsubmit="confirmSubmit(event)" action="submit_post.php" method="POST">
            <label for="skills"><b>Skill:</b></label>
            <select id="skills" name="skills" required>
                <option value="" disabled selected>Select a category</option>
                <option value="Customer Service">Customer Service</option>
                <option value="Technology">Technology</option>
                <option value="Photographic">Photographic</option>
                <option value="Creative Skills">Creative Skills</option>
                <option value="Teaching and Tutoring">Teaching and Tutoring</option>
                <option value="Writing and Editing">Writing and Editing</option>
                <option value="Sales and Marketing">Sales and Marketing</option>
                <option value="Retail Support">Retail Support</option>
                <option value="Event Staff">Event Staff</option>
                <option value="Food and Beverage Service">Food and Beverage Service</option>
                <option value="Delivery and Logistics">Delivery and Logistics</option>
                <option value="Administrative Support">Administrative Support</option>
                <option value="Data Entry">Data Entry</option>
                <option value="Social Media Management">Social Media Management</option>
                <option value="Warehouse Assistance">Warehouse Assistance</option>
                <option value="Cleaning and Housekeeping">Cleaning and Housekeeping</option>
                <option value="Childcare">Childcare</option>
                <option value="Pet Care">Pet Care</option>
                <option value="Fitness and Coaching">Fitness and Coaching</option>
                <option value="Personal Assistance">Personal Assistance</option>
                <option value="Others">Other</option> <!-- General option for anything not listed -->
            </select><br>
            

            <label for="skillDetails"><b>Skill Details:</b></label>
            <textarea id="skillDetails" name="skillDetails" rows="4" maxlength="250" placeholder="Describe your skills in this category (max 50 words)" required></textarea><br>

            <label><b>Available Time:</b></label>
            <div class="availability-slots">
                <div class="availability-slot">
                    <label>Monday</label>
                    <input type="time" name="mondayStart" placeholder="Start Time" step="600" onchange="validateTime(this)">
                    <input type="time" name="mondayEnd" placeholder="End Time" step="600" onchange="validateTime(this)">
                </div>
                <div class="availability-slot">
                    <label>Tuesday</label>
                    <input type="time" name="tuesdayStart" placeholder="Start Time" step="600" onchange="validateTime(this)">
                    <input type="time" name="tuesdayEnd" placeholder="End Time" step="600" onchange="validateTime(this)">
                </div>
                <div class="availability-slot">
                    <label>Wednesday</label>
                    <input type="time" name="wednesdayStart" placeholder="Start Time" step="600" onchange="validateTime(this)">
                    <input type="time" name="wednesdayEnd" placeholder="End Time" step="600" onchange="validateTime(this)">
                </div>
                <div class="availability-slot">
                    <label>Thursday</label>
                    <input type="time" name="thursdayStart" placeholder="Start Time" step="600" onchange="validateTime(this)">
                    <input type="time" name="thursdayEnd" placeholder="End Time" step="600" onchange="validateTime(this)">
                </div>
                <div class="availability-slot">
                    <label>Friday</label>
                    <input type="time" name="fridayStart" placeholder="Start Time" step="600" onchange="validateTime(this)">
                    <input type="time" name="fridayEnd" placeholder="End Time" step="600" onchange="validateTime(this)">
                </div>
                <div class="availability-slot">
                    <label>Saturday</label>
                    <input type="time" name="saturdayStart" placeholder="Start Time" step="600" onchange="validateTime(this)">
                    <input type="time" name="saturdayEnd" placeholder="End Time" step="600" onchange="validateTime(this)">
                </div>
                <div class="availability-slot">
                    <label>Sunday</label>
                    <input type="time" name="sundayStart" placeholder="Start Time" step="600" onchange="validateTime(this)">
                    <input type="time" name="sundayEnd" placeholder="End Time" step="600" onchange="validateTime(this)">
                </div>
            </div>


        <!-- Location section -->
        <div class="location-section">
            <label for="state"><b>Location:</b></label><br>
            <div class="location-selects">
                <select id="state" name="state" required onchange="updateDistricts()">
                    <option value="">Select a State</option>
                    <option value="Johor">Johor</option>
                    <option value="Kedah">Kedah</option>
                    <option value="Kelantan">Kelantan</option>
                    <option value="Kuala Lumpur">Kuala Lumpur</option>
                    <option value="Melaka">Melaka</option>
                    <option value="Negeri Sembilan">Negeri Sembilan</option>
                    <option value="Pahang">Pahang</option>
                    <option value="Penang">Penang</option>
                    <option value="Perak">Perak</option>
                    <option value="Perlis">Perlis</option>
                    <option value="Sabah">Sabah</option>
                    <option value="Sarawak">Sarawak</option>
                    <option value="Selangor">Selangor</option>
                    <option value="Terengganu">Terengganu</option>
                    <option value="Putrajaya">Putrajaya</option>
                    <option value="Labuan">Labuan</option>
                </select>

                <select id="district" name="district" required>
                    <option value="">Select a District</option>
                </select>
            </div>
        </div>


            <label for="jobPreferences"><b>Job Preferences (max 50 words):</b></label>
            <textarea id="jobPreferences" name="jobPreferences" rows="3" maxlength="250"></textarea><br>

            <button type="submit">Publish</button>
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

            // Clear existing districts
            districtSelect.innerHTML = '<option value="">Select a District</option>';

            if (selectedState && districtsByState[selectedState]) {
                districtsByState[selectedState].forEach(district => {
                    const option = document.createElement("option");
                    option.value = district;
                    option.textContent = district;
                    districtSelect.appendChild(option);
                });
            }
        }

         // Confirm form submission
        function confirmSubmit() {
            event.preventDefault(); // Prevent form from submitting immediately

            Swal.fire({
                title: 'Are you sure you want to submit this post?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if user confirmed
                    document.getElementById("createPostForm").submit();
                }
            });
        }

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

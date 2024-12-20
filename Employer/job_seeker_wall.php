<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job-Seeker Wall</title>
    <link rel="stylesheet" href="../css/job_seeker_wall.css">
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
</head>
<body>

    <?php 
        include('../database/config.php');
        include('employer1.php');  // Include header and side menu here
    ?>

<div class="main-content">
        <h1>Job Seeker Wall</h1>
        <!-- Search Bar -->
        <div class="search-bar-container">
                <form method="GET" action="job_seeker_wall.php" class="search-bar">
                    <img src="../images/Search.png" alt="Search Icon" class="search-icon" />
                    <input type="text" name="keyword" class="search-input" placeholder="Search post" value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" />
                    <button type="submit" class="search-btn">Search</button>
                    <?php if (!empty($_GET['keyword'])): ?>
                        <button 
                            type="button" 
                            class="clear-search-btn" 
                            onclick="window.location.href='job_seeker_wall.php';">
                            ✖
                        </button>
                    <?php endif; ?>
                </form>
                    <button class="filter-btn" onclick="toggleFilterForm()">Filter <span class="dropdown-icon">▼</span></button>
                    


        </div>
        <div class="filter-section">
        <form id="filter-form" method="GET" action="job_seeker_wall.php" class="filter-dropdown">
            <!-- Hidden field to retain search keyword -->
            <input 
                type="hidden" 
                id="filter-keyword" 
                name="keyword" 
                value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" 
            />
        
            <!-- Skill Category -->
            <div class="filter-group">
                <h4>Skill</h4>
                <div id="skill-list" style="max-height: calc(4 * 2.5em); overflow: hidden;">
                <label><input type="checkbox" name="skillCategory[]" value="Customer Service" 
                <?php echo !empty($_GET['skillCategory']) && in_array('Customer Service', $_GET['skillCategory']) ? 'checked' : ''; ?>> Customer Service</label>
                <label><input type="checkbox" name="skillCategory[]" value="Technology"
                <?php echo !empty($_GET['skillCategory']) && in_array('Technology', $_GET['skillCategory']) ? 'checked' : ''; ?>> Technology</label>
                <label><input type="checkbox" name="skillCategory[]" value="Photography"
                <?php echo !empty($_GET['skillCategory']) && in_array('Photography', $_GET['skillCategory']) ? 'checked' : ''; ?>> Photography</label>
                <label><input type="checkbox" name="skillCategory[]" value="Accounting"
                <?php echo !empty($_GET['skillCategory']) && in_array('Accounting', $_GET['skillCategory']) ? 'checked' : ''; ?>> Accounting</label>
                <label><input type="checkbox" name="skillCategory[]" value="Creative Skills"
                <?php echo !empty($_GET['skillCategory']) && in_array('Creative Skills', $_GET['skillCategory']) ? 'checked' : ''; ?>> Creative Skills</label>
                <label><input type="checkbox" name="skillCategory[]" value="Teaching and Tutoring"
                <?php echo !empty($_GET['skillCategory']) && in_array('Teaching and Tutoring', $_GET['skillCategory']) ? 'checked' : ''; ?>> Teaching and Tutoring</label>
                <label><input type="checkbox" name="skillCategory[]" value="Writing and Editing"
                <?php echo !empty($_GET['skillCategory']) && in_array('Writing and Editing', $_GET['skillCategory']) ? 'checked' : ''; ?>> Writing and Editing</label>
                <label><input type="checkbox" name="skillCategory[]" value="Sales and Marketing"
                <?php echo !empty($_GET['skillCategory']) && in_array('Sales and Marketing', $_GET['skillCategory']) ? 'checked' : ''; ?>> Sales and Marketing</label>
                <label><input type="checkbox" name="skillCategory[]" value="Retail Support"
                <?php echo !empty($_GET['skillCategory']) && in_array('Retail Support', $_GET['skillCategory']) ? 'checked' : ''; ?>> Retail Support</label>
                <label><input type="checkbox" name="skillCategory[]" value="Event Staff"
                <?php echo !empty($_GET['skillCategory']) && in_array('Event Staff', $_GET['skillCategory']) ? 'checked' : ''; ?>> Event Staff</label>
                <label><input type="checkbox" name="skillCategory[]" value="Food and Beverage Service"
                <?php echo !empty($_GET['skillCategory']) && in_array('Food and Beverage Service', $_GET['skillCategory']) ? 'checked' : ''; ?>> Food and Beverage Service</label>
                <label><input type="checkbox" name="skillCategory[]" value="Delivery and Logistics"
                <?php echo !empty($_GET['skillCategory']) && in_array('Delivery and Logistics', $_GET['skillCategory']) ? 'checked' : ''; ?>> Delivery and Logistics</label>
                <label><input type="checkbox" name="skillCategory[]" value="Administrative Support"
                <?php echo !empty($_GET['skillCategory']) && in_array('Administrative Support', $_GET['skillCategory']) ? 'checked' : ''; ?>> Administrative Support</label>
                <label><input type="checkbox" name="skillCategory[]" value="Data Entry"
                <?php echo !empty($_GET['skillCategory']) && in_array('Data Entry', $_GET['skillCategory']) ? 'checked' : ''; ?>> Data Entry</label>
                <label><input type="checkbox" name="skillCategory[]" value="Social Media Management"
                <?php echo !empty($_GET['skillCategory']) && in_array('Social Media Management', $_GET['skillCategory']) ? 'checked' : ''; ?>> Social Media Management</label>
                <label><input type="checkbox" name="skillCategory[]" value="Warehouse Assistance"
                <?php echo !empty($_GET['skillCategory']) && in_array('Warehouse Assistance', $_GET['skillCategory']) ? 'checked' : ''; ?>> Warehouse Assistance</label>
                <label><input type="checkbox" name="skillCategory[]" value="Cleaning and Housekeeping"
                <?php echo !empty($_GET['skillCategory']) && in_array('Cleaning and Housekeeping', $_GET['skillCategory']) ? 'checked' : ''; ?>> Cleaning and Housekeeping</label>
                <label><input type="checkbox" name="skillCategory[]" value="Childcare"
                <?php echo !empty($_GET['skillCategory']) && in_array('Childcare', $_GET['skillCategory']) ? 'checked' : ''; ?>> Childcare</label>
                <label><input type="checkbox" name="skillCategory[]" value="Pet Care"
                <?php echo !empty($_GET['skillCategory']) && in_array('Pet Care', $_GET['skillCategory']) ? 'checked' : ''; ?>> Pet Care</label>
                <label><input type="checkbox" name="skillCategory[]" value="Fitness and Coaching"
                <?php echo !empty($_GET['skillCategory']) && in_array('Fitness and Coaching', $_GET['skillCategory']) ? 'checked' : ''; ?>> Fitness and Coaching</label>
                <label><input type="checkbox" name="skillCategory[]" value="Personal Assistance"
                <?php echo !empty($_GET['skillCategory']) && in_array('Personal Assistance', $_GET['skillCategory']) ? 'checked' : ''; ?>> Personal Assistance</label>

                </div>
                <button type="button" id="toggle-skill-visibility" class="toggle-skill-btn">Show More</button>
            </div>
            <hr>
            <!-- Location -->
            <div class="filter-group">
                <h4>Location</h4>
                <label><input type="checkbox" name="location[]" value="Johor" 
                <?php echo !empty($_GET['location']) && in_array('Johor', $_GET['location']) ? 'checked' : ''; ?>> Johor</label>
                <label><input type="checkbox" name="location[]" value="Melaka" 
                <?php echo !empty($_GET['location']) && in_array('Melaka', $_GET['location']) ? 'checked' : ''; ?>> Melaka</label>
                <label><input type="checkbox" name="location[]" value="Selangor" 
                <?php echo !empty($_GET['location']) && in_array('Selangor', $_GET['location']) ? 'checked' : ''; ?>> Selangor</label>
                <label><input type="checkbox" name="location[]" value="Penang" 
                <?php echo !empty($_GET['location']) && in_array('Penang', $_GET['location']) ? 'checked' : ''; ?>> Penang</label>
                <label><input type="checkbox" name="location[]" value="Kedah" 
                <?php echo !empty($_GET['location']) && in_array('Kedah', $_GET['location']) ? 'checked' : ''; ?>> Kedah</label>
                <label><input type="checkbox" name="location[]" value="Perak" 
                <?php echo !empty($_GET['location']) && in_array('Perak', $_GET['location']) ? 'checked' : ''; ?>> Perak</label>
                <label><input type="checkbox" name="location[]" value="Sembilan" 
                <?php echo !empty($_GET['location']) && in_array('Sembilan', $_GET['location']) ? 'checked' : ''; ?>> Sembilan</label>
                <label><input type="checkbox" name="location[]" value="Pahang" 
                <?php echo !empty($_GET['location']) && in_array('Pahang', $_GET['location']) ? 'checked' : ''; ?>> Pahang</label>
                <label><input type="checkbox" name="location[]" value="Kelantan" 
                <?php echo !empty($_GET['location']) && in_array('Kelantan', $_GET['location']) ? 'checked' : ''; ?>> Kelantan</label>
                <label><input type="checkbox" name="location[]" value="Terengganu" 
                <?php echo !empty($_GET['location']) && in_array('Terengganu', $_GET['location']) ? 'checked' : ''; ?>> Terengganu</label>
                <label><input type="checkbox" name="location[]" value="Sabah" 
                <?php echo !empty($_GET['location']) && in_array('Sabah', $_GET['location']) ? 'checked' : ''; ?>> Sabah</label>
                <label><input type="checkbox" name="location[]" value="Sarawak" 
                <?php echo !empty($_GET['location']) && in_array('Sarawak', $_GET['location']) ? 'checked' : ''; ?>> Sarawak</label>
                <label><input type="checkbox" name="location[]" value="Kuala Lumpur" 
                <?php echo !empty($_GET['location']) && in_array('Kuala Lumpur', $_GET['location']) ? 'checked' : ''; ?>> Kuala Lumpur</label>
                <label><input type="checkbox" name="location[]" value="Putrajaya" 
                <?php echo !empty($_GET['location']) && in_array('Putrajaya', $_GET['location']) ? 'checked' : ''; ?>> Putrajaya</label>
                <label><input type="checkbox" name="location[]" value="Labuan" 
                <?php echo !empty($_GET['location']) && in_array('Labuan', $_GET['location']) ? 'checked' : ''; ?>> Labuan</label>
            </div>

            <hr>
            <!-- Available Time -->
            <div class="filter-group">
                <h4>Available Time</h4>
                <label><input type="checkbox" name="availableTime[]" value="Monday"
                <?php echo !empty($_GET['availableTime']) && in_array('Monday', $_GET['availableTime']) ? 'checked' : ''; ?>> Monday</label>
                <label><input type="checkbox" name="availableTime[]" value="Tuesday"
                <?php echo !empty($_GET['availableTime']) && in_array('Tuesday', $_GET['availableTime']) ? 'checked' : ''; ?>> Tuesday</label>
                <label><input type="checkbox" name="availableTime[]" value="Wednesday"
                <?php echo !empty($_GET['availableTime']) && in_array('Wednesday', $_GET['availableTime']) ? 'checked' : ''; ?>> Wednesday</label>
                <label><input type="checkbox" name="availableTime[]" value="Thursday"
                <?php echo !empty($_GET['availableTime']) && in_array('Thursday', $_GET['availableTime']) ? 'checked' : ''; ?>> Thursday</label>
                <label><input type="checkbox" name="availableTime[]" value="Friday"
                <?php echo !empty($_GET['availableTime']) && in_array('Friday', $_GET['availableTime']) ? 'checked' : ''; ?>> Friday</label>
                <label><input type="checkbox" name="availableTime[]" value="Saturday"
                <?php echo !empty($_GET['availableTime']) && in_array('Saturday', $_GET['availableTime']) ? 'checked' : ''; ?>> Saturday</label>
                <label><input type="checkbox" name="availableTime[]" value="Sunday"
                <?php echo !empty($_GET['availableTime']) && in_array('Sunday', $_GET['availableTime']) ? 'checked' : ''; ?>> Sunday</label>
            </div>


            <!-- Buttons -->
            <div class="filter-action">
                <button type="submit" class="apply-filter-btn">Apply Filter</button>
                <button type="button" id="clear-filter-btn" class="clear-filter-btn">Clear Filter</button>       
            </div>

        </form>
    </div>


        <!-- Display posts -->
        <div class="job-seeker-wall">
            <?php include 'display_wall_post.php'; ?>
        </div>
    </div>

<script>
    function toggleFilterForm() {
        const filterForm = document.getElementById('filter-form');
        filterForm.classList.toggle('active');
    }

    document.getElementById('toggle-skill-visibility').addEventListener('click', function () {
    const skillList = document.getElementById('skill-list');
    const button = document.getElementById('toggle-skill-visibility');
    const isExpanded = skillList.classList.contains('expanded');

    if (isExpanded) {
        skillList.style.maxHeight = 'calc(4 * 2.5em)';
        button.textContent = 'Show More';
    } else {
        skillList.style.maxHeight = 'none';
        button.textContent = 'Show Less';
    }

    skillList.classList.toggle('expanded');
    });

    document.addEventListener('DOMContentLoaded', function() {
    // Load saved filter states
    const savedFilters = JSON.parse(localStorage.getItem('filters')) || {};
    for (const [name, values] of Object.entries(savedFilters)) {
        document.querySelectorAll(`input[name="${name}[]"]`).forEach(input => {
            if (values.includes(input.value)) {
                input.checked = true;
            }
        });
    }

    // Sync search box value to hidden filter field
    const searchKeyword = document.getElementById('search-keyword');
    const filterKeyword = document.getElementById('filter-keyword');

    if (searchKeyword && filterKeyword) {
        filterKeyword.value = searchKeyword.value; // Sync initial value
    }

        // Clear search (resets all filters and search keyword)
        const clearSearchButton = document.querySelector('.clear-search-btn');
    if (clearSearchButton) {
        clearSearchButton.addEventListener('click', function () {
            window.location.href = 'job_seeker_wall.php'; // Resets the page
        });
    }

    const clearFilterButton = document.getElementById('clear-filter-btn');
    
    if (clearFilterButton) {
        clearFilterButton.addEventListener('click', function () {
            // Clear all checkboxes
            document.querySelectorAll('#filter-form input[type="checkbox"]').forEach(input => {
                input.checked = false; // Uncheck each checkbox
            });

            // Re-sync the search keyword (if needed)
            const searchKeyword = document.getElementById('search-keyword');
            const filterKeyword = document.getElementById('filter-keyword');

            if (searchKeyword && filterKeyword) {
                filterKeyword.value = searchKeyword.value; // Retain search keyword
            }

            // Submit the form after clearing filters
            document.getElementById('filter-form').submit();
        });
    } else {
        console.error('Clear Filter Button not found in the DOM.');
    }
});

</script>
</body>
</html>

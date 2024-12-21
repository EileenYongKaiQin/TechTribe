function toggleDropdown(element) {
    const dropdownMenu = element.nextElementSibling;
    dropdownMenu.classList.toggle('show');
}

function navigateToReport(jobPostID) {
    window.location.href = `report_form.php?jobPostID=${jobPostID}`;
}

function hidePost(element) {
    const jobCard = element.closest('.job-card');
    const jobContainer = jobCard.parentElement;

    const placeholder = document.createElement('div');
    placeholder.classList.add('job-placeholder');
    placeholder.dataset.hiddenJobCardId = jobCard.dataset.id;

    placeholder.style.height = `${jobCard.offsetHeight}px`;
    placeholder.style.width = `${jobCard.offsetWidth}px`;
    placeholder.style.margin = window.getComputedStyle(jobCard).margin;

    const modal = document.createElement('div');
    modal.classList.add('hide-post-modal');
    modal.innerHTML = `
        <span class="hide-post-text">Hiding posts helps us find the suitable jobs for you</span>
        <button class="undo-btn" onclick="showPost('${jobCard.dataset.id}')">Undo</button>
    `;
    placeholder.appendChild(modal);

    jobCard.style.display = 'none';
    jobContainer.insertBefore(placeholder, jobCard);
}

function showPost(hiddenJobCardId) {
    const placeholder = document.querySelector(`.job-placeholder[data-hidden-job-card-id='${hiddenJobCardId}']`);

    if (placeholder) {
        const jobCard = document.querySelector(`.job-card[data-id='${hiddenJobCardId}']`);
        if (jobCard) {
            jobCard.style.display = 'block';
        }
        placeholder.remove();
    }
}

document.addEventListener('click', function (event) {
    const dropdowns = document.querySelectorAll('.dropdown-menu');
    dropdowns.forEach((dropdown) => {
        if (!dropdown.contains(event.target) && !event.target.matches('.more-options-icon')) {
            dropdown.classList.remove('show');
        }
    });
});

// Set the initial icon state when the page loads
document.addEventListener("DOMContentLoaded", function () {
    const jobCards = document.querySelectorAll(".job-card");

    // Collect all jobPostIDs
    const jobPostIDs = Array.from(jobCards).map(card => card.getAttribute("data-id"));

    fetch("get_saved_jobs.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ jobPostIDs, userID }),
    })

    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const savedJobs = data.savedJobs;
            jobCards.forEach(card => {
                const jobPostID = card.getAttribute("data-id");
                const iconElement = card.querySelector(".bookmark-icon");
                iconElement.src = savedJobs.includes(jobPostID)
                    ? "../images/Saved.png"
                    : "../images/BookmarkSimple.png";
            });
        } else {
            console.error("Failed to fetch saved jobs:", data.error);
        }
    })
    .catch(error => console.error("Error:", error));

    // Handle 'Enter' keypress in search inputs
    const searchInputs = document.querySelectorAll('.search-input');
    searchInputs.forEach(input => {
        input.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                document.querySelector('.search-btn').click(); 
            }
        });
    });
});

// Function to format time to 12-hour format
function formatTimeTo12Hour(time24) {
    const [hours, minutes] = time24.split(':');
    const period = +hours >= 12 ? 'PM' : 'AM';
    const hours12 = (+hours % 12) || 12; // Convert 0 to 12 for midnight
    return `${hours12}:${minutes} ${period}`;
    }

//Save or unsave function
function saveJob(jobPostID, iconElement) {
    // Determine the current state: Saved (saved) or BookmarkSimple (unsaved)
    const isSaved = iconElement.src.includes("Saved.png");

    // Set the request URL and updated icon
    const url = isSaved ? "unsave_job.php" : "save_job.php";
    const newIcon = isSaved ? "../images/BookmarkSimple.png" : "../images/Saved.png";

    fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ jobPostID, userID }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            iconElement.src = newIcon;
        } else { }
    })
    .catch(error => {
        console.error("Error:", error);
        showNotification("Error occurred while processing the request.");
    });
}

function clearSearch(inputName) {
    const input = document.querySelector(`input[name="${inputName}"]`);
    if (input) {
        input.value = '';
        input.focus(); 
    }
}

// Search Function
document.addEventListener("DOMContentLoaded", function () {
    const searchInputs = document.querySelectorAll('.search-input');

    // press enter to search
    searchInputs.forEach(input => {
        input.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                document.querySelector('.search-btn').click();
            }
        });
    });

    // fetch search results dynamically and update the DOM
    document.querySelector('.search-btn').addEventListener('click', function () {
        const jobTitle = document.querySelector('input[name="jobTitle"]').value.trim();
        const location = document.querySelector('input[name="location"]').value.trim();

        fetch('search_jobs.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ jobTitle, location })
        })
        .then(response => response.json())
        .then(jobs => {
            const rectangle = document.querySelector('.rectangle');
            rectangle.innerHTML = '';   // Clear existing job cards

            if (jobs.length === 0) {
                rectangle.innerHTML = "<p class='no-jobs-container'>No jobs found.</p>";
                return;
            }

            const jobPostIDs = jobs.map(job => job.jobPostID);

            fetch('get_saved_jobs.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ jobPostIDs, userID })
            })
            .then(response => response.json())
            .then(savedData => {
                if (!savedData.success) {
                    console.error('Error fetching saved jobs:', savedData.error);
                    return;
                }

                const savedJobs = savedData.savedJobs;

                jobs.forEach(job => {
                    const jobCard = document.createElement('div');
                    jobCard.classList.add('job-card');
                    jobCard.dataset.id = job.jobPostID;

                    const isSaved = savedJobs.includes(job.jobPostID);

                    jobCard.innerHTML = `
                        <div class="top-right-icons">
                            <img src="../images/${isSaved ? 'Saved.png' : 'BookmarkSimple.png'}" alt="Bookmark" class="bookmark-icon ${isSaved ? 'saved' : ''}" onclick="saveJob('${job.jobPostID}', this)">
                            <img src="../images/more_vert.png" alt="More Options" class="more-options-icon" onclick="toggleDropdown(this)">
                            <div class="dropdown-menu">
                                <div class="dropdown-item" onclick="navigateToReport('${job.jobPostID}')">
                                    <img src="../images/sms_failed.png" alt="Report Icon">
                                    <span>Report Post</span>
                                </div>
                                <div class="dropdown-item" onclick="hidePost(this)">
                                    <img src="../images/cancel_presentation.png" alt="Hide Icon">
                                    <span>Hide Post</span>
                                </div>
                            </div>
                        </div>
                        <div class="job-header">
                            <h3 class="job-title">${job.jobTitle}</h3>
                        </div>
                        <div class="job-details">
                            <p class="time">
                                <span class="time-label ${job.workingHour === 'Day Shift' ? 'day' : 'night'}">
                                    ${job.workingHour}
                                </span>
                                ${formatTimeTo12Hour(job.workingTimeStart)} - ${formatTimeTo12Hour(job.workingTimeEnd)}
                            </p>
                            <p class="date">${job.startDate} - ${job.endDate}</p>
                            <p class="location"><img src="../images/MapPin.png" alt="Location Icon" class="map-pin"> ${job.venue}, ${job.location}</p>
                            <div class="salary-line"></div>
                            <p class="salary">Salary: RM ${parseFloat(job.salary).toFixed(2)} / hour</p>
                            <div class="job-details-buttons">
                                <button class="view-details-btn" onclick="location.href='manageJob.php?jobPostID=${job.jobPostID}'">View Details</button>
                            </div>
                        </div>
                    `;
                    rectangle.appendChild(jobCard);
                });
            })
            .catch(error => console.error('Error fetching saved jobs:', error));
        })
        .catch(error => console.error('Error fetching jobs:', error));
    });
});

// Filter Function
document.addEventListener("DOMContentLoaded", function () {
    const filterButton = document.getElementById("filterBtn");
    const filterPanel = document.getElementById("filterPanel");

    // Toggle filter panel visibility
    filterButton.addEventListener("click", function () {
        filterPanel.classList.toggle("visible");
    });

    // Close filter panel when clicking outside
    document.addEventListener("click", function (event) {
        if (!filterPanel.contains(event.target) && !filterButton.contains(event.target)) {
            filterPanel.classList.remove("visible");
        }
    });
});

function applyFilters() {
const minSalary = document.getElementById("minSalary").value;
const maxSalary = document.getElementById("maxSalary").value;

// Collect selected locations
const selectedLocations = Array.from(
document.querySelectorAll('.checkbox-container input[type="checkbox"]:checked')
)
.filter(checkbox => !["Day Shift", "Night Shift"].includes(checkbox.value))
.map(checkbox => checkbox.value);

// Collect selected working hours
const selectedWorkingHours = Array.from(
document.querySelectorAll('.checkbox-container input[type="checkbox"]:checked')
)
.filter(checkbox => ["Day Shift", "Night Shift"].includes(checkbox.value))
.map(checkbox => checkbox.value);

// Fetch filtered jobs from the backend
fetch("filter_jobs.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
        minSalary,
        maxSalary,
        locations: selectedLocations,
        workingHours: selectedWorkingHours,
    }),
})
.then(response => response.json())
        .then(jobs => {
            const rectangle = document.querySelector('.rectangle');
            rectangle.innerHTML = '';   // Clear existing job cards

            if (jobs.length === 0) {
                rectangle.innerHTML = "<p class='no-jobs-container'>No jobs found.</p>";
                return;
            }

            const jobPostIDs = jobs.map(job => job.jobPostID);

            fetch('get_saved_jobs.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ jobPostIDs, userID })
            })
            .then(response => response.json())
            .then(savedData => {
                if (!savedData.success) {
                    console.error('Error fetching saved jobs:', savedData.error);
                    return;
                }

                const savedJobs = savedData.savedJobs;

                jobs.forEach(job => {
                    const jobCard = document.createElement('div');
                    jobCard.classList.add('job-card');
                    jobCard.dataset.id = job.jobPostID;

                    const isSaved = savedJobs.includes(job.jobPostID);

                    jobCard.innerHTML = `
                        <div class="top-right-icons">
                            <img src="../images/${isSaved ? 'Saved.png' : 'BookmarkSimple.png'}" alt="Bookmark" class="bookmark-icon ${isSaved ? 'saved' : ''}" onclick="saveJob('${job.jobPostID}', this)">
                            <img src="../images/more_vert.png" alt="More Options" class="more-options-icon" onclick="toggleDropdown(this)">
                            <div class="dropdown-menu">
                                <div class="dropdown-item" onclick="navigateToReport('${job.jobPostID}')">
                                    <img src="../images/sms_failed.png" alt="Report Icon">
                                    <span>Report Post</span>
                                </div>
                                <div class="dropdown-item" onclick="hidePost(this)">
                                    <img src="../images/cancel_presentation.png" alt="Hide Icon">
                                    <span>Hide Post</span>
                                </div>
                            </div>
                        </div>
                        <div class="job-header">
                            <h3 class="job-title">${job.jobTitle}</h3>
                        </div>
                        <div class="job-details">
                            <p class="time">
                                <span class="time-label ${job.workingHour === 'Day Shift' ? 'day' : 'night'}">
                                    ${job.workingHour}
                                </span>
                                ${formatTimeTo12Hour(job.workingTimeStart)} - ${formatTimeTo12Hour(job.workingTimeEnd)}
                            </p>
                            <p class="date">${job.startDate} - ${job.endDate}</p>
                            <p class="location"><img src="../images/MapPin.png" alt="Location Icon" class="map-pin"> ${job.venue}, ${job.location}</p>
                            <div class="salary-line"></div>
                            <p class="salary">Salary: RM ${parseFloat(job.salary).toFixed(2)} / hour</p>
                            <div class="job-details-buttons">
                                <button class="view-details-btn" onclick="location.href='manageJob.php?jobPostID=${job.jobPostID}'">View Details</button>
                            </div>
                        </div>
                    `;
                    rectangle.appendChild(jobCard);
                });
            })
            .catch(error => console.error('Error fetching saved jobs:', error));
        })
        .catch(error => console.error('Error fetching jobs:', error));
}
<?php
// Include necessary files
include('admin.php');
include('../database/config.php');

// Fetch all reports with reporterName
$sql = "
    SELECT 
        reportPost.reportID,
        reportPost.reason,
        reportPost.description,
        reportPost.evidence,
        reportPost.createTime AS creationDate,
        reportPost.reportStatus,
        reportPost.jobPostID,
        login.userID,
        CASE
            WHEN login.role = 'jobSeeker' THEN jobSeeker.fullName
            WHEN login.role = 'employer' THEN employer.fullName
            ELSE 'N/A'
        END AS reporterName
    FROM 
        reportPost
    JOIN 
        login ON reportPost.userID = login.userID
    LEFT JOIN 
        jobSeeker ON login.userID = jobSeeker.userID
    LEFT JOIN 
        employer ON login.userID = employer.userID
";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Report</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>Report</h1>
    <div class="search-and-actions">
        <div class="search-bar">
            <input type="text" placeholder="Find Report" id="search-input">
            <button id="search-btn">Search</button>
        </div>
        <div class="action-buttons">
            <button class="btn btn-under-review" onclick="showUnderReviewModal()">Under Review</button>
            <button class="btn btn-resolve-issue">Resolve Issue</button>
        </div>
    </div>
    <div class="container">    
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>No.</th>
                    <th>Reporter Name</th>
                    <th>Reason</th>
                    <th>Creation Date</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php 
                        $counter = 1;
                        while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="checkbox" name="select-report" value="<?= htmlspecialchars($row['reportID']) ?>"></td>
                            <td><?= $counter ?></td>
                            <td><?= htmlspecialchars($row['reporterName']) ?></td>
                            <td><?= htmlspecialchars($row['reason']) ?></td>
                            <td><?= htmlspecialchars($row['creationDate']) ?></td>
                            <td class="status <?= strtolower(str_replace(' ', '-', $row['reportStatus'])) ?>" data-id="<?= htmlspecialchars($row['reportID']) ?>">
                                <?= htmlspecialchars($row['reportStatus']) ?>
                            </td>
                            <td>
                                <button class="btn btn-view" onclick="showDetailsModal('<?= $row['reportID'] ?>')">View</button>
                            </td>
                        </tr>
                        <?php $counter++; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-table-message">No reports found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="pagination">
        <button class="page-btn">«</button>
        <button class="page-btn active">1</button>
        <button class="page-btn">»</button>
    </div>

    <!-- Modal -->
    <div id="updateModal" class="modal hidden">
        <div class="modal-content">
            <span class="close" id="closeModal" onclick="closeModal('updateModal')">&times;</span>
            <h2>Are you sure you want to update the review status to "Under Review"?</h2>
            <p id="selectedCount">There are X issue statuses that will be updated.</p>
            <div class="modal-buttons">
                <button id="cancelButton" class="btn cancel" onclick="closeModal('updateModal')">Cancel</button>
                <button id="updateButton" class="btn update" onclick="updateStatus()">Update</button>
            </div>
        </div>
    </div>

    <!-- Reporter Details Modal -->
    <div id="detailsModal" class="modal hidden">
        <div class="modal-content">
            <span class="close" id="closeDetailsModal" onclick="closeModal('detailsModal')">&times;</span>
            <h2>Reporter Information</h2>
            <table id="reporter-table">
                <tr><th>Reporter Name</th><td id="modalReporterName"></td></tr>
                <tr><th>Submission Date</th><td id="modalSubmissionDate"></td></tr>
                <tr><th>Job Post</th><td><button class="btn btn-view">View</button></td></tr>
                <tr><th>Reason</th><td id="modalReason"></td></tr>
                <tr><th>Description</th><td id="modalDescription"></td></tr>
                <tr><th>Evidence</th><td><a href="#" id="modalEvidence" class="btn btn-view" target="_blank" style="text-decoration: none;">View</a></td></tr>
                <tr><th>Reported User</th><td id="modalReportedUser"></td></tr>
                <tr><th>View Profile</th><td><button id="modalViewProfile" class="btn btn-view" onclick="viewProfile()">View</button></td></tr>
            </table>
        </div>
    </div>

    <script>
        function showUnderReviewModal() {
            const selectedCount = document.querySelectorAll('input[name="select-report"]:checked').length;
            if (selectedCount > 0) {
                const modal = document.getElementById('updateModal');
                modal.classList.remove('hidden');
                modal.classList.add('visible');
                document.getElementById('selectedCount').innerText = `There are ${selectedCount} issue status that will be updated.`;
            } else {
                alert("Please select at least one report to update.");
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('visible');
            modal.classList.add('hidden');
        }

        document.getElementById('closeModal').addEventListener('click', () => closeModal('updateModal'));
        document.getElementById('cancelButton').addEventListener('click', () => closeModal('updateModal'));
        document.getElementById('closeDetailsModal').addEventListener('click', () => closeModal('detailsModal'));
        
        
        function updateStatus() {
    const selectedIds = Array.from(document.querySelectorAll('input[name="select-report"]:checked')).map(cb => cb.value);
    const status = 'Under Review';

    // Debugging: Check if the selectedIds and status are correctly set
    console.log("Selected IDs:", selectedIds);
    console.log("Status:", status);

    // Prepare form data
    const formData = new FormData();
    formData.append('ids', selectedIds.join(',')); // Send the IDs as comma-separated string
    formData.append('status', status); // Send status

    // Send POST request to update status
    fetch('updateStatus.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Expecting plain text response
    .then(data => {
        console.log(data); // Debugging: Check the response from the server
        
        if (data.trim() === 'success') {
            // Update the UI to show the new status immediately
            selectedIds.forEach(id => {
                const statusElement = document.querySelector(`.status[data-id="${id}"]`);
                if (statusElement) {
                    statusElement.innerText = 'Under Review';
                    statusElement.classList.remove('pending');
                    statusElement.classList.add('under-review');
                }
            });

            // Close the modal automatically
            closeModal('updateModal');
        } else {
            alert('Failed to update status.');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('An error occurred while updating the status.');
    });
}

        document.getElementById('updateButton').addEventListener('click', updateStatus);

        function showDetailsModal(reportId) {
            // Send request to get report details
            fetch(`getReportDetails.php?id=${reportId}`)
                .then(response => response.json())
                .then(data => {
                    // Populate the modal with the fetched data
                    document.getElementById('modalReporterName').innerText = data.reporterName;
                    document.getElementById('modalSubmissionDate').innerText = data.submissionDate;
                    document.getElementById('modalReason').innerText = data.reason;
                    document.getElementById('modalDescription').innerText = data.description;
                    document.getElementById('modalEvidence').href = data.evidenceLink;
                    document.getElementById('modalReportedUser').innerText = data.reportedUser;
                    document.getElementById('modalViewProfile').dataset.userId = data.reportedUserID;
                    // Show the modal
                    const modal = document.getElementById('detailsModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('visible');
                })
                .catch(err => {
                    console.error('Error fetching report details:', err);
                    alert('An error occurred while fetching report details.');
                });
        }

        document.getElementById('closeDetailsModal').addEventListener('click', () => closeModal('detailsModal'));

        function viewProfile() {
        // Get the userID stored in the button's data-userId attribute
        const userID = document.getElementById('modalViewProfile').dataset.userId;

        // Example: Open the reporter's profile page using userID
        const profileURL = `viewProfile.php?userID=${encodeURIComponent(userID)}`;
        window.location.href = profileURL;
    }
        
    </script>
</body>
</html>

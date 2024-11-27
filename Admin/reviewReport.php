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
                    <th>Post ID</th>
                    <th>Reporter Name</th>
                    <th>Reason</th>
                    <th>Creation Date</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="checkbox" name="select-report" value="<?= htmlspecialchars($row['reportID']) ?>"></td>
                            <td><?= htmlspecialchars($row['jobPostID']) ?></td>
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
            <button class="close" id="closeModal" onclick="closeModal('updateModal')">&times;</button>
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
            <span class="close" id="closeDetailsModal">&times;</span>
            <h2>Reporter Information</h2>
            <table>
                <tr><th>Reporter Name</th><td id="modalReporterName"></td></tr>
                <tr><th>Submission Date</th><td id="modalSubmissionDate"></td></tr>
                <tr><th>Job Post</th><td><button class="btn btn-view">View</button></td></tr>
                <tr><th>Reason</th><td id="modalReason"></td></tr>
                <tr><th>Description</th><td id="modalDescription"></td></tr>
                <tr><th>Evidence</th><td><a href="#" id="modalEvidence" target="_blank">View</a></td></tr>
                <tr><th>Reported User</th><td id="modalReportedUser"></td></tr>
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

        function updateStatus() {
            const selectedIds = Array.from(document.querySelectorAll('input[name="select-report"]:checked')).map(cb => cb.value);
            fetch('updateStatus.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids: selectedIds, status: 'Under Review' }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    selectedIds.forEach(id => {
                        const statusElement = document.querySelector(`.status[data-id="${id}"]`);
                        statusElement.innerText = 'Under Review';
                        statusElement.classList.remove('pending');
                        statusElement.classList.add('under-review');
                    });
                    closeModal('updateModal');
                } else {
                    alert('Failed to update status.');
                }
            })
            .catch(console.error);
        }

        document.getElementById('updateButton').addEventListener('click', updateStatus);

        function showDetailsModal(reportId) {
            fetch(`getReportDetails.php?id=${reportId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalReporterName').innerText = data.reporterName;
                    document.getElementById('modalSubmissionDate').innerText = data.submissionDate;
                    document.getElementById('modalReason').innerText = data.reason;
                    document.getElementById('modalDescription').innerText = data.description;
                    document.getElementById('modalEvidence').href = data.evidenceLink;
                    document.getElementById('modalReportedUser').innerText = data.reportedUser;
                    document.getElementById('detailsModal').classList.remove('hidden');
                });
        }

        document.getElementById('closeDetailsModal').addEventListener('click', () => closeModal('detailsModal'));
    </script>
</body>
</html>

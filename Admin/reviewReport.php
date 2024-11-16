<?php
// Include database configuration
include('../database/config.php');

// Fetch all reports from the database
$sql = "SELECT * FROM reportPost";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Review Reports</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>Admin Panel - Review Reports</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Report ID</th>
                <th>Reason</th>
                <th>Description</th>
                <th>Evidence</th>
                <th>User ID</th>
                <th>Job Post ID</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['reportID'] ?></td>
                        <td><?= $row['reason'] ?></td>
                        <td><?= $row['description'] ?: 'N/A' ?></td>
                        <td>
                            <?php if ($row['evidence']): ?>
                                <?php foreach (explode(",", $row['evidence']) as $file): ?>
                                    <a href="<?= $file ?>" target="_blank">View Evidence</a><br>
                                <?php endforeach; ?>
                            <?php else: ?>
                                No Evidence
                            <?php endif; ?>
                        </td>
                        <td><?= $row['userID'] ?></td>
                        <td><?= $row['jobPostID'] ?></td>
                        <td><?= $row['reportStatus'] ?></td>
                        <td>
                            <?php if ($row['reportStatus'] === 'Pending'): ?>
                                <form action="update_report_status.php" method="POST">
                                    <input type="hidden" name="reportID" value="<?= $row['reportID'] ?>">
                                    <button type="submit" name="action" value="Reviewed">Mark as Reviewed</button>
                                    <button type="submit" name="action" value="Resolved">Mark as Resolved</button>
                                </form>
                            <?php else: ?>
                                <em><?= $row['reportStatus'] ?></em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No reports found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

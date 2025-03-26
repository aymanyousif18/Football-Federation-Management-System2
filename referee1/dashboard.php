<?php
require __DIR__ . '/../../includes1/config.php';
requireRole('referee');

// Get referee stats
$assigned_matches = $conn->query("
    SELECT COUNT(*) FROM matches
    WHERE match_date > NOW()
")->fetch_row()[0];

$completed_matches = $conn->query("
    SELECT COUNT(*) FROM matches
    WHERE match_date <= NOW()
")->fetch_row()[0];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Referee Dashboard</title>
    <link href="../../css1/style.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../includes1/header.php'; ?>
    
    <div class="content">
        <h2>Referee Dashboard</h2>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Upcoming Matches</h3>
                <p><?php echo $assigned_matches; ?></p>
            </div>
            <div class="stat-card">
                <h3>Completed Matches</h3>
                <p><?php echo $completed_matches; ?></p>
            </div>
        </div>
        
        <div class="quick-actions">
            <a href="matches.php" class="btn">View All Matches</a>
        </div>
        
        <h3>Recent Matches</h3>
        <?php
        $recent_matches = $conn->query("
            SELECT m.*, t1.name as team1, t2.name as team2 
            FROM matches m
            JOIN teams t1 ON m.team1_id = t1.id
            JOIN teams t2 ON m.team2_id = t2.id
            ORDER BY m.match_date DESC
            LIMIT 5
        ");
        
        if ($recent_matches->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Teams</th>
                    <th>Date</th>
                    <th>Venue</th>
                </tr>
                <?php while ($match = $recent_matches->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $match['team1']; ?> vs <?php echo $match['team2']; ?></td>
                        <td><?php echo date('M j, Y', strtotime($match['match_date'])); ?></td>
                        <td><?php echo $match['venue']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="alert">No match history found.</p>
        <?php endif; ?>
    </div>
    
    <?php include __DIR__ . '/../../includes1/footer.php'; ?>
</body>
</html>
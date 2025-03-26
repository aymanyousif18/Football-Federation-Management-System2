<?php
// Use absolute path to config.php (most reliable)
require 'C:/xampp/htdocs/FFMS1/includes1/config.php';

// Verify manager access
if (!isLoggedIn() || getUserRole() != 'team_manager') {
    header('Location: ../login1.php');
    exit();
}

// Get manager's team
$team = $conn->query("
    SELECT t.* 
    FROM teams t
    WHERE t.manager_id = {$_SESSION['user_id']}
")->fetch_assoc();

// Get player count
$player_count = 0;
if ($team) {
    $player_count = $conn->query("
        SELECT COUNT(*) 
        FROM players 
        WHERE team_id = {$team['id']}
    ")->fetch_row()[0];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Team Dashboard</title>
    <link href="../css1/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'C:/xampp/htdocs/FFMS1/includes1/header.php'; ?>
    
    <div class="content">
        <h2>Team Manager Dashboard</h2>
        
        <?php if ($team): ?>
            <div class="team-info">
                <h3><?= htmlspecialchars($team['name']) ?></h3>
                <p>Total Players: <?= $player_count ?></p>
            </div>
            
            <div class="actions">
                <a href="players.php" class="btn">Manage Players</a>
                <a href="../logout1.php" class="btn">Logout</a>
            </div>
        <?php else: ?>
            <div class="alert">
                You are not assigned to any team yet.
            </div>
        <?php endif; ?>
    </div>

    <?php include 'C:/xampp/htdocs/FFMS1/includes1/footer.php'; ?>
</body>
</html>
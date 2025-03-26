<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Federation System</title>
    <link href="../css1/style.css" rel="stylesheet">
</head>
<body>
    <div class="header">
        <div class="logo">FFMS</div>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <a href="../admin1/dashboard.php">Dashboard</a>
                    <a href="../admin1/teams.php">Teams</a>
                    <a href="../admin1/matches.php">Matches</a>
                <?php elseif ($_SESSION['role'] == 'team_manager'): ?>
                    <a href="../manager1/dashboard.php">Dashboard</a>
                    <a href="../manager1/players.php">My Team</a>
                <?php elseif ($_SESSION['role'] == 'referee'): ?>
                    <a href="../referee1/dashboard.php">My Matches</a>
                <?php else: ?>
                    <a href="../spectator1/dashboard.php">View Matches</a>
                <?php endif; ?>
                <span class="user-info">
                    Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 
                    (<?php echo ucfirst($_SESSION['role']); ?>)
                </span>
                <a href="../logout1.php" class="logout-btn">Logout</a>
            <?php else: ?>
                <a href="../login1.php">Login</a>
                <a href="../register1.php">Register</a>
            <?php endif; ?>
        </nav>
    </div>
    <div class="main-content">
<?php
// Use absolute path for config.php (most reliable)
require 'C:/xampp/htdocs/FFMS1/includes1/config.php';

// Verify admin access
if (!isLoggedIn() || getUserRole() != 'admin') {
    header('Location: ../login1.php');
    exit();
}

// Get stats
$teams_count = $conn->query("SELECT COUNT(*) FROM teams")->fetch_row()[0];
$matches_count = $conn->query("SELECT COUNT(*) FROM matches")->fetch_row()[0];
$users_count = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="../css1/style.css" rel="stylesheet">
</head>
<body>
    <?php 
    // Header inclusion with error handling
    $header_path = __DIR__ . '/../includes1/header.php';
    if (file_exists($header_path)) {
        include $header_path;
    } else {
        die("Error: Missing header file at $header_path");
    }
    ?>
    
    <div class="content">
        <h2>Admin Dashboard</h2>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Teams</h3>
                <p><?php echo $teams_count; ?></p>
            </div>
            <div class="stat-card">
                <h3>Matches</h3>
                <p><?php echo $matches_count; ?></p>
            </div>
            <div class="stat-card">
                <h3>Users</h3>
                <p><?php echo $users_count; ?></p>
            </div>
        </div>
        
        <div class="quick-actions">
            <a href="teams.php" class="btn">Manage Teams</a>
            <a href="matches.php" class="btn">Manage Matches</a>
        </div>
    </div>

    <?php 
    // Footer inclusion with error handling
    $footer_path = __DIR__ . '/../includes1/footer.php';
    if (file_exists($footer_path)) {
        include $footer_path;
    } else {
        die("Error: Missing footer file at $footer_path");
    }
    ?>
</body>
</html>
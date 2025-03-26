<?php
// OPTION 1: Absolute path (most reliable - adjust if your XAMPP is installed elsewhere)
require 'C:/xampp/htdocs/FFMS1/includes1/config.php';

// OPTION 2: Relative path alternative (use only one option)
// require __DIR__ . '/../includes1/config.php';

// Security check
if (!isLoggedIn() || getUserRole() != 'admin') {
    header('Location: ../login1.php');
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_match'])) {
    $team1_id = (int)$_POST['team1_id'];
    $team2_id = (int)$_POST['team2_id'];
    $match_date = $conn->real_escape_string($_POST['match_date']);
    $venue = $conn->real_escape_string($_POST['venue']);
    
    $conn->query("INSERT INTO matches (team1_id, team2_id, match_date, venue) 
                VALUES ($team1_id, $team2_id, '$match_date', '$venue')");
}

// Handle deletions
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM matches WHERE id = $id");
}

// Get data
$matches = $conn->query("
    SELECT m.*, t1.name as team1_name, t2.name as team2_name 
    FROM matches m
    JOIN teams t1 ON m.team1_id = t1.id
    JOIN teams t2 ON m.team2_id = t2.id
    ORDER BY m.match_date DESC
");

$teams = $conn->query("SELECT * FROM teams ORDER BY name");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Matches</title>
    <link href="../css1/style.css" rel="stylesheet">
</head>
<body>
    <?php 
    // Header inclusion with absolute path
    include 'C:/xampp/htdocs/FFMS1/includes1/header.php'; 
    ?>
    
    <div class="content">
        <h2>Manage Matches</h2>
        
        <div class="card">
            <h3>Schedule New Match</h3>
            <form method="post">
                <div class="form-group">
                    <label>Team 1:</label>
                    <select name="team1_id" required>
                        <?php while ($team = $teams->fetch_assoc()): ?>
                            <option value="<?= $team['id'] ?>"><?= htmlspecialchars($team['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Team 2:</label>
                    <select name="team2_id" required>
                        <?php 
                        $teams->data_seek(0); // Reset pointer
                        while ($team = $teams->fetch_assoc()): ?>
                            <option value="<?= $team['id'] ?>"><?= htmlspecialchars($team['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Date & Time:</label>
                    <input type="datetime-local" name="match_date" required>
                </div>
                
                <div class="form-group">
                    <label>Venue:</label>
                    <input type="text" name="venue" required>
                </div>
                
                <button type="submit" name="add_match" class="btn">Schedule Match</button>
            </form>
        </div>
        
        <div class="card">
            <h3>Upcoming Matches</h3>
            <table>
                <tr>
                    <th>Match</th>
                    <th>Date & Time</th>
                    <th>Venue</th>
                    <th>Actions</th>
                </tr>
                <?php while ($match = $matches->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($match['team1_name']) ?> vs <?= htmlspecialchars($match['team2_name']) ?></td>
                        <td><?= date('M j, Y H:i', strtotime($match['match_date'])) ?></td>
                        <td><?= htmlspecialchars($match['venue']) ?></td>
                        <td>
                            <a href="edit_match.php?id=<?= $match['id'] ?>" class="btn">Edit</a>
                            <a href="?delete=<?= $match['id'] ?>" class="btn delete" onclick="return confirm('Delete this match?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <?php 
    // Footer inclusion with absolute path
    include 'C:/xampp/htdocs/FFMS1/includes1/footer.php'; 
    ?>
</body>
</html>
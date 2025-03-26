<?php
// Option 1: Absolute path (most reliable)
require 'C:/xampp/htdocs/FFMS1/includes1/config.php';

// Option 2: Relative path alternative (use only one)
// require __DIR__ . '/../includes1/config.php';

// Security check
if (!isLoggedIn() || getUserRole() != 'admin') {
    header('Location: ../login1.php');
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_team'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $manager_id = isset($_POST['manager_id']) ? (int)$_POST['manager_id'] : 'NULL';
        $conn->query("INSERT INTO teams (name, manager_id) VALUES ('$name', $manager_id)");
    } elseif (isset($_GET['delete'])) {
        $id = (int)$_GET['delete'];
        $conn->query("DELETE FROM teams WHERE id = $id");
    }
}

// Get data
$teams = $conn->query("SELECT t.*, u.username as manager_name FROM teams t LEFT JOIN users u ON t.manager_id = u.id");
$managers = $conn->query("SELECT * FROM users WHERE role = 'team_manager'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Teams</title>
    <link href="../css1/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'C:/xampp/htdocs/FFMS1/includes1/header.php'; ?>
    
    <div class="content">
        <h2>Manage Teams</h2>
        
        <div class="card">
            <h3>Add New Team</h3>
            <form method="post">
                <div class="form-group">
                    <label>Team Name:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Manager:</label>
                    <select name="manager_id">
                        <option value="">-- No Manager --</option>
                        <?php while ($manager = $managers->fetch_assoc()): ?>
                            <option value="<?= $manager['id'] ?>"><?= htmlspecialchars($manager['username']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" name="add_team" class="btn">Add Team</button>
            </form>
        </div>
        
        <div class="card">
            <h3>Team List</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Manager</th>
                    <th>Actions</th>
                </tr>
                <?php while ($team = $teams->fetch_assoc()): ?>
                    <tr>
                        <td><?= $team['id'] ?></td>
                        <td><?= htmlspecialchars($team['name']) ?></td>
                        <td><?= $team['manager_name'] ?? 'None' ?></td>
                        <td>
                            <a href="edit_team.php?id=<?= $team['id'] ?>" class="btn">Edit</a>
                            <a href="?delete=<?= $team['id'] ?>" class="btn delete" onclick="return confirm('Delete this team?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <?php include 'C:/xampp/htdocs/FFMS1/includes1/footer.php'; ?>
</body>
</html>
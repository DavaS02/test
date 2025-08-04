<?php
session_start();

// ✅ Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "db.php";
$result = $conn->query("SELECT * FROM users");
?>

<h2>Welcome, <?= htmlspecialchars($_SESSION["username"]) ?>! 👋</h2>
<a href="logout.php">🚪 Logout</a>
<h2>User List</h2>
<a href="create_user.php">➕ Create new users</a>
<table border="1" cellpadding="5">
    <tr><th>ID</th><th>Username</th><th>Actions</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr> 
        <td><?= $row['id']?></td>
        <td><?= htmlspecialchars($row['username'])?></td>
        <td>
            <a href="update_user.php?id=<?= $row['id']?>">✏️ Edit</a>
            <a href="delete_user.php?id=<?= $row['id']?>" onclick="return confirm('Delete this user?')">🗑️ Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

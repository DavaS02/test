<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"] ?? '';

    if (!is_numeric($id)) {
        die("Invalid ID");
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php");
    exit;
}
?>

<h2>Delete User</h2>
<form method="POST">
  Id: <input type="text" name="id"><br>
  <button type="submit">Delete</button>
</form>

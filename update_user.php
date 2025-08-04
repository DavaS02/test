<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"] ?? '';
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $password, $id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}
?>
<h2>Update User</h2>
<form method="POST">
  Id: <input type="text" name="id"><br>
  Username: <input type="text" name="username"><br>
  Password: <input type="password" name="password"><br>
  <button type="submit">Update</button>
</form>


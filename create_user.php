<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    // ✅ This hashes the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        echo "✅ User created successfully.";
    } else {
        echo "❌ Error: " . $stmt->error;
    }
}
?>

<form method="POST">
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    <input type="submit" value="Create User">
</form>

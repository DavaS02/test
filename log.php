<?php
session_start();
require_once "db.php"; // include DB connection

if (isset($_GET['reset'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION["attempts"])) $_SESSION["attempts"] = 0;
if (!isset($_SESSION["lockout_time"])) $_SESSION["lockout_time"] = 0;

$now = time();

if ($_SESSION["lockout_time"] > $now) {
    $remaining = $_SESSION["lockout_time"] - $now;
    echo "⛔ Blocked: Try again in $remaining seconds.<br>";
    echo "<a href='?reset'>🔁 Reset session</a><br>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    // Prepare and check user from DB
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($db_password);
        $stmt->fetch();

        if ($password === $db_password) { // plain text for now
            echo "✅ Login successful.<br>";
            $_SESSION["attempts"] = 0;
            $_SESSION["lockout_time"] = 0;
        } else {
            // handle lockout
            $_SESSION["attempts"]++;
            $delays = [3 => 30, 4 => 60, 5 => 300, 6 => 1800];
            $delay = $delays[$_SESSION["attempts"]] ?? $delays[6];
            if ($_SESSION["attempts"] >= 3) {
                $_SESSION["lockout_time"] = $now + $delay;
                echo "❌ Wrong password. Locked for $delay seconds.<br>";
                echo "<a href='?reset'>🔁 Reset session</a>";
                exit;
            } else {
                echo "❌ Wrong password.<br>";
            }
        }
    } else {
        echo "❌ User not found.<br>";
    }
    $stmt->close();
}
?>

<form method="POST">
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    <input type="submit" value="Login">
</form>
<br>
<a href="?reset">🔁 Reset session</a>
<?php echo "<br>(Attempts: {$_SESSION["attempts"]})"; ?>

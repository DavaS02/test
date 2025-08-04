<?php
session_start();

// RESET logic
if (isset($_GET['reset'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Initialize
if (!isset($_SESSION["attempts"])) $_SESSION["attempts"] = 0;
if (!isset($_SESSION["lockout_time"])) $_SESSION["lockout_time"] = 0;

$now = time();

// BLOCK CHECK
if ($_SESSION["lockout_time"] > $now) {
    $remaining = $_SESSION["lockout_time"] - $now;
    echo "⛔ Blocked: Try again in $remaining seconds.<br>";
    echo "<a href='?reset'>🔁 Reset session</a><br>";
    echo "(attempts: {$_SESSION["attempts"]})";
    exit;
}

// LOGIN HANDLER
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    if ($username === "admin" && $password === "1234") {
        echo "✅ Login successful.<br>";
        $_SESSION["attempts"] = 0;
        $_SESSION["lockout_time"] = 0;
    } else {
        $_SESSION["attempts"]++;

        // Progressive lockout logic
        $delays = [
            3 => 30,
            4 => 60,
            5 => 300,
            6 => 1800
        ];

        $delay = $delays[$_SESSION["attempts"]] ?? $delays[6];
        if ($_SESSION["attempts"] >= 3) {
            $_SESSION["lockout_time"] = $now + $delay;
            echo "❌ Invalid. Locked for $delay seconds.<br>";
            echo "<a href='?reset'>🔁 Reset session</a>";
            exit;
        } else {
            echo "❌ Invalid credentials.<br>";
        }
    }
}
?>

<!-- LOGIN FORM -->
<form method="POST">
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    <input type="submit" value="Login">
</form>
<br>
<a href="?reset">🔁 Reset session</a>
<?php echo "<br>(Attempts: {$_SESSION["attempts"]})"; ?>

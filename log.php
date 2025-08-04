<?php
session_start();
require_once "db.php";

if (isset($_GET['reset'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$now = time();
$_SESSION["attempts"] = $_SESSION["attempts"] ?? 0;
$_SESSION["lockout_time"] = $_SESSION["lockout_time"] ?? 0;

if ($_SESSION["lockout_time"] > $now) {
    $remaining = $_SESSION["lockout_time"] - $now;
    echo "⛔ Blocked: Try again in $remaining seconds.<br>";
    echo "<a href='?reset'>🔁 Reset session</a><br>";
    exit;
}

// Track login status
$login_success = false;

if($login_success){
        header("Location: launch.php");
        exit;

}

// Handle login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $db_password);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            $_SESSION["attempts"] = 0;
            $_SESSION["lockout_time"] = 0;

            $login_success = true; // mark login success



        } else {
            $_SESSION["attempts"]++;
            $delay = [3 => 30, 4 => 60, 5 => 300, 6 => 1800][$_SESSION["attempts"]] ?? 1800;
            if ($_SESSION["attempts"] >= 3) {
                $_SESSION["lockout_time"] = $now + $delay;
                echo "❌ Wrong password. Locked for $delay seconds.<br><a href='?reset'>🔁 Reset session</a>";
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

<!-- HTML -->
<form method="POST" id="loginForm">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>

<a href="?reset">🔁 Reset session</a>
<?php echo "<br>(Attempts: {$_SESSION['attempts']})"; ?>

<?php if ($login_success): ?>
<script>
    // ✅ THIS WILL WORK IN ALL BROWSERS!
    const newTab = window.open('launch.php', '_blank');
    if (!newTab) {
        alert('⚠️ Please allow pop-ups!');
    } else {
        // Optional: redirect current tab somewhere
        window.location.href = 'welcome.php'; // or leave it
    }
</script>
<?php endif; ?>

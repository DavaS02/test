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

ob_start();

echo '<div class="login-box">';

if ($_SESSION["lockout_time"] > $now) {
    $remaining = $_SESSION["lockout_time"] - $now;
    echo "<div class='message'>⛔ Blocked: Try again in $remaining seconds.</div>";
    echo "<a class='reset-link' href='?reset'>🔁 Reset session</a>";
} else {
    $login_success = false;

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

                header("Location: welcome.php");
                exit;
            } else {
                $_SESSION["attempts"]++;
                $delay = [3 => 30, 4 => 60, 5 => 300, 6 => 1800][$_SESSION["attempts"]] ?? 1800;
                if ($_SESSION["attempts"] >= 3) {
                    $_SESSION["lockout_time"] = $now + $delay;
                    echo "<div class='message'>❌ Wrong password. Locked for $delay seconds.</div>";
                    echo "<a class='reset-link' href='?reset'>🔁 Reset session</a>";
                } else {
                    echo "<div class='message'>❌ Wrong password.</div>";
                }
            }
        } else {
            echo "<div class='message'>❌ User not found.</div>";
        }

        $stmt->close();
    }

    if (!isset($_SESSION["user_id"])) {
        echo "<h2>🔐 Login</h2>";
        ?>
        <form method="POST" id="loginForm">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <input type="submit" value="Login">
        </form>
        <a class="reset-link" href="?reset">🔁 Reset session</a>
        <?php
        echo "<br>(Attempts: {$_SESSION['attempts']})";
    }
}

echo '</div>'; // end of .login-box

$content = ob_get_clean();
include 'layout.php';
?>


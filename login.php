<?php
session_start();
require_once "db.php";

// --- RESET FEATURE ---
if (isset($_GET['reset'])) {
    session_destroy(); // destroy session data
    header("Location: login.php");
    exit;
}

// --- INIT SESSION VARS ---
$_SESSION["attempts"] = $_SESSION["attempts"] ?? 0;
$_SESSION["lockout_time"] = $_SESSION["lockout_time"] ?? 0;

// --- DELAY RULES ---
$delays = [
    3 => 30,        // 30 seconds
    4 => 60,        // 1 minute
    5 => 300,       // 5 minutes
    6 => 1800       // 30 minutes
];

$now = time();
$delay = 0;

if ($_SESSION["attempts"] >= 3) {
    $delay = $delays[$_SESSION["attempts"]] ?? $delays[6];
}

// --- BLOCKING CHECK ---
if ($_SESSION["lockout_time"] > $now) {
    $remaining = $_SESSION["lockout_time"] - $now;
    echo "⛔ Too many failed attempts. Try again in $remaining seconds.<br>";
    echo '<a href="?reset">🔁 Reset Session</a>';
    exit;
}

// --- PROCESS LOGIN ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    $stmt = $conn->prepare ("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param ("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])){
        echo "✅ Login successful. Welcome,". htmlspecialchars($user["username"]);
        $_SESSION["attempts"]=0;
        $_SESSION["lockout-time"] = 0;
        $_SESSION["user_id"]= $user["id"];
        $_SESSION["username"]= $user["username"];


    } else{
        $_SESSION["attempts"]++;
        $_SESSION["attempts"]= min($_SESSION["attempts"],6);
        $delay= $delays[$_SESSION["attempts"]]?? $delays[6];
        $_SESSION["lockout-time"]= $now + $delay;
        echo "❌ Invalid credentials. Locked for $delay seconds.";
    }
  
}

// --- SHOW FORM IF NOT LOCKED ---
if ($_SESSION["lockout_time"] <= $now):
?>
    <form method="POST">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
    <br>
    <a href="?reset">🔁 Reset Session</a>
<?php endif; ?>

<?php
$user ="admin";
$pass = "1234";
if ($_SERVER ["REQUEST_METHOD"]== "POST"){
    $inputUser = $_POST["username"] ?? '';
    $inputPass = $_POST["password"] ?? '';



    if ($inputUser === $user && $inputPass === $pass){
        echo "Login Successful";
    } else {
        echo "Invalid Credentials";
    }

}
?>
<form method="POST" action="try.php">
  <label>Username:</label><br>
  <input type="text" name="username"><br><br>

  <label>Password:</label><br>
  <input type="password" name="password"><br><br>

  <input type="submit" value="Login">
</form>
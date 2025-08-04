<?php
require_once "db.php";

    if ($_SERVER["REQUEST_METHOD"]=== "POST"){
        $username=$_POST ["username"]?? '';
        $password= password_hash($_POST ["password"]?? '', PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users(username, password)VALUES (?,?)");
        $stmt->bind_param("ss",$username, $password);
        if ($stmt->execute()){
            header("Location: dashboard.php");
            exit;
        } else{
            echo "⚠️ Error: Username might already exist.";
        }

    }
?>
<h2>Create User</h2>
<form method="POST">
  Username: <input type="text" name="username"><br>
  Password: <input type="password" name="password"><br>
  <button type="submit">Create</button>
</form>
<!DOCTYPE html>
<html>
<head>
    <title>My Site</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }

        .navbar {
            background-color: #333;
            padding: 12px;
            text-align: center;
        }

        .navbar a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }

        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 50px);
            padding: 20px;
        }

        .login-box {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #333;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        .message {
            margin-bottom: 15px;
            color: red;
        }

        a.reset-link {
            display: block;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="index.php">🏠 Home</a>
    <a href="login.php">🔐 Login</a>
    <a href="about.php">📄 About</a>
</div>

<div class="content">
    <?php echo $content; ?>
</div>

</body>
</html>

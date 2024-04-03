<?php
    require_once '../db_connect.php';

    require_once '../db_connect.php';
    session_start();
    if (isset($_SESSION['user'])) {
        header("Location: dashboard.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: dashboard.php");
        } else {
            echo "Invalid username or password.";
        }

    // Close the statement
    $stmt->close();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
<nav class="navbar">
        <div class="container1">
            <div class="navbar-left">
                <a href="../index.php" class="logo"></a>
            </div>
            <div class="navbar-right">
                <a href="login.php" class="login-btn">Inloggen</a>
                <a href="register.php" class="register-btn">Registreren</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Login</h2>
        <form action="#" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

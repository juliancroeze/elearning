<?php 
    require_once '../db_connect.php';

    $registerError = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Check if passwords match
        if ($password != $confirm_password) {
            $registerError = "Wachtwoorden komen niet overeen";
        } else {
            // Check if the username already exists in the database
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            if ($stmt->fetch()) {
                $registerError = "Deze gebruikersnaam is al in gebruik.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert the user into the database
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);
        
                try {
                    $stmt->execute();
                    header("Location: login.php");
                    exit(); // Terminate script execution after redirection
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        }
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
        <h2>Registreren</h2>
        <?php if (!empty($registerError)): ?>
            <p class="registerError"><?php echo $registerError ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Registreren</button>
        </form>
    </div>
</body>
</html>

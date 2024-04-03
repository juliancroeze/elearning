<?php
// Database connection parameters
$host = 'localhost'; // Your MySQL host
$dbname = 'elearning'; // Your MySQL database name
$username = 'root'; // Your MySQL username
$password = 'your_password'; // Your MySQL password

try {
    // Establish database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

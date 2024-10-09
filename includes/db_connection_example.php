<?php
// Database connection parameters
$host = "localhost";
$dbname = "your_database_name";
$username = "your_username";
$password = "your_password";

// Create a new PDO instance
try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);

    // Connection successful
    // echo "Connected successfully";
} catch(PDOException $e) {
    // Log the error instead of displaying it
    error_log("Connection failed: " . $e->getMessage());
    die("A database error was encountered. Please try again later.");
}

// Function to get the PDO connection
function getConnection() {
    global $pdo;
    return $pdo;
}
?>

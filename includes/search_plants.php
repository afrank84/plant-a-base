<?php
session_start();
require_once '../includes/db_connection.php';  // Database connection logic

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Retrieve the search query
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Check for empty search term
if (empty($query)) {
    echo json_encode([]);
    exit();
}

// Escape the query to prevent SQL injection
$searchTerm = $db_connection->real_escape_string($query);

// Query the database for plants
$sql = "SELECT plant_id, parent_name, variety_name 
        FROM plants 
        WHERE (parent_name LIKE '%$searchTerm%' OR variety_name LIKE '%$searchTerm%')
        LIMIT 10";
$result = $db_connection->query($sql);

$plants = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $plants[] = $row;
    }
}

// Return the matching plants as JSON
echo json_encode($plants);
?>

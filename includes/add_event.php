<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Define the user-specific JSON file path
$jsonFilePath = "../data/{$user_id}.json";

// Initialize an error message
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $plant_id = $_POST['plant_id'] ?? null;
    $parent_name = $_POST['parent_name'] ?? null;
    $variety_name = $_POST['variety_name'] ?? null;
    $event_title = $_POST['event_title'] ?? null;
    $event_date = $_POST['event_date'] ?? null;
    $event_notes = $_POST['event_notes'] ?? '';

    // Validate required fields
    if ($plant_id && $event_title && $event_date) {
        // Prepare event data
        $eventData = [
            'plant_id' => $plant_id,
            'parent_name' => $parent_name,
            'variety_name' => $variety_name,
            'event_title' => $event_title,
            'event_date' => $event_date,
            'event_notes' => $event_notes
        ];

        // Read existing JSON data
        $events = [];
        if (file_exists($jsonFilePath)) {
            $events = json_decode(file_get_contents($jsonFilePath), true);
        }

        // Add new event
        $events[] = $eventData;

        // Save back to JSON
        file_put_contents($jsonFilePath, json_encode($events));

        header("Location: ../pages/view_events.php");
        exit();
    } else {
        $error = "Please fill in all required fields.";
        
    }
}


// Display error message if any
if (!empty($error)) {
    echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . "</div>";
}
?>

<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Define the JSON file path
$jsonFilePath = '../data/plant_events.json';

// Initialize an error message
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $plant_id = $_POST['plant_id'] ?? null;
    $event_title = $_POST['event_title'] ?? null;
    $event_date = $_POST['event_date'] ?? null;
    $event_notes = $_POST['event_notes'] ?? '';

    // Validate required fields
    if ($plant_id && $event_title && $event_date) {
        // Prepare event data
        $eventData = [
            'plant_id' => $plant_id,
            'event_title' => $event_title,
            'event_date' => $event_date,
            'event_notes' => $event_notes
        ];

        // Read existing JSON data
        $events = [];
        if (file_exists($jsonFilePath)) {
            $jsonContent = file_get_contents($jsonFilePath);
            $events = json_decode($jsonContent, true) ?? [];
        }

        // Add the new event to the array
        $events[] = $eventData;

        // Save back to the JSON file
        if (file_put_contents($jsonFilePath, json_encode($events, JSON_PRETTY_PRINT))) {
            header("Location: ../includes/view_events.php"); // Redirect to a success page or confirmation message
            exit();
        } else {
            $error = "Failed to save event to JSON file.";
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}

// Display error message if any
if (!empty($error)) {
    echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . "</div>";
}
?>

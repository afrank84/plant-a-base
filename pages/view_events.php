<?php
// Define the JSON file path
$jsonFilePath = '../data/plant_events.json';

// Initialize variables
$events = [];
$error = '';

// Read the JSON file
if (file_exists($jsonFilePath)) {
    $jsonContent = file_get_contents($jsonFilePath);
    $events = json_decode($jsonContent, true);
    if ($events === null) {
        $error = "Failed to decode JSON. Please check the file format.";
    }
} else {
    $error = "No event records found.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Events - View Records</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/menu.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Plant Events</h1>

        <?php
        // Show error message if any
        if (!empty($error)) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . "</div>";
        } elseif (!empty($events)) {
            echo '<table class="table table-bordered table-striped">';
            echo '<thead>
                    <tr>
                        <th>#</th>
                        <th>Plant ID</th>
                        <th>Event Title</th>
                        <th>Event Date</th>
                        <th>Event Notes</th>
                    </tr>
                </thead>
                <tbody>';
            foreach ($events as $index => $event) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($index + 1) . '</td>';
                echo '<td>' . htmlspecialchars($event['plant_id']) . '</td>';
                echo '<td>' . htmlspecialchars($event['event_title']) . '</td>';
                echo '<td>' . htmlspecialchars($event['event_date']) . '</td>';
                echo '<td>' . htmlspecialchars($event['event_notes']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo "<p class='text-center'>No events found.</p>";
        }
        ?>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Define the JSON file path
$jsonFilePath = '../data/plant_events.json';

// Initialize variables
$events = [];
$error = '';
$successMessage = '';

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

// Handle update, delete, and add requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_index'])) {
        $index = (int)$_POST['delete_index'];

        // Delete the event from the array
        if (isset($events[$index])) {
            array_splice($events, $index, 1);

            // Save back to the JSON file
            if (file_put_contents($jsonFilePath, json_encode($events, JSON_PRETTY_PRINT))) {
                $successMessage = "Event was successfully deleted.";
            } else {
                $error = "Failed to delete event.";
            }
        } else {
            $error = "Event not found.";
        }
    } elseif (isset($_POST['update_index'])) {
        $index = (int)$_POST['update_index'];
        $updatedEvent = [
            'plant_id' => $_POST['plant_id'],
            'event_title' => $_POST['event_title'],
            'event_date' => $_POST['event_date'],
            'event_notes' => $_POST['event_notes']
        ];

        // Update the event in the array
        if (isset($events[$index])) {
            $events[$index] = $updatedEvent;

            // Save back to the JSON file
            if (file_put_contents($jsonFilePath, json_encode($events, JSON_PRETTY_PRINT))) {
                $successMessage = "Event updated successfully.";
            } else {
                $error = "Failed to save updated event.";
            }
        } else {
            $error = "Event not found.";
        }
    } elseif (isset($_POST['add_event'])) {
        $newEvent = [
            'plant_id' => $_POST['plant_id'],
            'event_title' => $_POST['event_title'],
            'event_date' => $_POST['event_date'],
            'event_notes' => $_POST['event_notes']
        ];

        // Add the new event to the array
        $events[] = $newEvent;

        // Save back to the JSON file
        if (file_put_contents($jsonFilePath, json_encode($events, JSON_PRETTY_PRINT))) {
            $successMessage = "New event added successfully.";
        } else {
            $error = "Failed to add new event.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Events - View Records</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script>
        function hideAlert() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 3000);
            });
        }
    </script>
</head>
<body onload="hideAlert()">
    <?php include '../includes/menu.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Plant Events</h1>

        <?php
        // Show success or error messages
        if (!empty($successMessage)) {
            echo "<div class='alert alert-success'>" . htmlspecialchars($successMessage) . "</div>";
        }
        if (!empty($error)) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . "</div>";
        }
        ?>

        <!-- Add New Event Form -->
        <div class="card mb-4">
            <div class="card-header">Add New Event</div>
            <div class="card-body">
                <form method="POST" action="view_events.php">
                    <div class="row mb-3">
                        <div class="col">
                            <input type="text" class="form-control" name="plant_id" placeholder="Plant ID" required>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="event_title" placeholder="Event Title" required>
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" name="event_date" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <textarea class="form-control" name="event_notes" placeholder="Event Notes"></textarea>
                        </div>
                    </div>
                    <button type="submit" name="add_event" class="btn btn-primary">Add Event</button>
                </form>
            </div>
        </div>

        <?php
        if (!empty($events)) {
            echo '<table class="table table-bordered table-striped">';
            echo '<thead>
                    <tr>
                        <th>#</th>
                        <th>Plant ID</th>
                        <th>Event Title</th>
                        <th>Event Date</th>
                        <th>Event Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';
            foreach ($events as $index => $event) {
                echo '<tr id="row-' . $index . '">';
                echo '<form method="POST" action="view_events.php">';
                echo '<td>' . htmlspecialchars($index + 1) . '</td>';
                echo '<td><input type="text" class="form-control" name="plant_id" value="' . htmlspecialchars($event['plant_id']) . '" required></td>';
                echo '<td><input type="text" class="form-control" name="event_title" value="' . htmlspecialchars($event['event_title']) . '" required></td>';
                echo '<td><input type="date" class="form-control" name="event_date" value="' . htmlspecialchars($event['event_date']) . '" required></td>';
                echo '<td><input type="text" class="form-control" name="event_notes" value="' . htmlspecialchars($event['event_notes']) . '"></td>';
                echo '<td>';
                echo '<input type="hidden" name="update_index" value="' . $index . '">';
                echo '<button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i></button> ';
                echo '<button type="submit" name="delete_index" value="' . $index . '" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button> ';
                echo '<a href="view_events.php" class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i></a>';
                echo '</td>';
                echo '</form>';
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>

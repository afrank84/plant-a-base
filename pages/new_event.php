<?php
require_once '../includes/db_connection.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$plants = [];

try {
    $pdo = getConnection();

    // Fetch plants from the database
    $stmt = $pdo->prepare("SELECT plant_id, parent_name, variety_name FROM plants ORDER BY parent_name ASC, variety_name ASC");
    $stmt->execute();
    $plants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "A database error occurred. Please try again later.";
    error_log("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event to Plant</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/menu.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center">Add Event to a Plant</h2>
        
        <?php
        if (!empty($error)) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . "</div>";
        }
        ?>

        <form action="../includes/add_event.php" method="POST" class="needs-validation" novalidate>
            <!-- Select Plant -->
            <div class="mb-3">
                <label for="plant" class="form-label">Select Plant</label>
                <select id="plant" name="plant_id" class="form-control">
                    <option value="">-- Select a Plant --</option>
                    <?php foreach ($plants as $plant): ?>
                        <option value="<?= htmlspecialchars($plant['plant_id']) ?>"
                            data-parent-name="<?= htmlspecialchars($plant['parent_name']) ?>"
                            data-variety-name="<?= htmlspecialchars($plant['variety_name']) ?>">
                            <?= htmlspecialchars($plant['parent_name'] . ' - ' . $plant['variety_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" id="parent_name" name="parent_name">
            <input type="hidden" id="variety_name" name="variety_name">

            <!-- Event Title -->
            <div class="mb-3">
                <label for="eventTitle" class="form-label">Event Title</label>
                <input type="text" class="form-control" id="eventTitle" name="event_title" placeholder="Enter event title" required>
                <div class="invalid-feedback">Please provide an event title.</div>
            </div>

            <!-- Event Date -->
            <div class="mb-3">
                <label for="eventDate" class="form-label">Event Date</label>
                <input type="date" class="form-control" id="eventDate" name="event_date" required>
                <div class="invalid-feedback">Please provide a valid date.</div>
            </div>

            <!-- Event Notes -->
            <div class="mb-3">
                <label for="eventNotes" class="form-label">Event Notes</label>
                <textarea class="form-control" id="eventNotes" name="event_notes" rows="4" placeholder="Add notes about the event"></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Add Event</button>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Form validation script -->
    <script>
        (function () {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
    <!-- Include jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Include Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">

    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function() {
            $('#plant').select2({
                placeholder: "Search for a plant...",
                allowClear: true
            });
        });
    </script>
    <script>
        // Populate hidden fields with selected plant data
        document.getElementById('plant').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('parent_name').value = selectedOption.dataset.parentName || '';
            document.getElementById('variety_name').value = selectedOption.dataset.varietyName || '';
        });
    </script>

</body>
</html>

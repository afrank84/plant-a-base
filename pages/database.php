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
$plantsWithRecords = [];

// Get the user-specific JSON file
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $jsonFilePath = "../data/{$user_id}.json";

    if (file_exists($jsonFilePath)) {
        $jsonContent = file_get_contents($jsonFilePath);
        $allRecords = json_decode($jsonContent, true) ?? [];

        // Extract unique plant IDs from records
        foreach ($allRecords as $record) {
            $plantsWithRecords[] = $record['plant_id'];
        }
        $plantsWithRecords = array_unique($plantsWithRecords);
    }
}

try {
    $pdo = getConnection();

    // Prepare and execute the query to get plants from the database
    $stmt = $pdo->prepare("SELECT plant_id, parent_name, variety_name, type FROM plants ORDER BY parent_name ASC, variety_name ASC");
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
    <title>Plants List - Plant-a-base</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/menu.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-5">Plants List</h1>

        <?php
        if (!empty($error)) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . "</div>";
        }
        ?>

        <!-- Add search bar -->
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search plants...">
        </div>

        <?php if (!empty($plants)): ?>
            <table class="table table-striped" id="plantsTable">
                <thead>
                    <tr>
                        <th>Plant ID</th>
                        <th>Parent</th>
                        <th>Variety</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plants as $plant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($plant['plant_id']); ?></td>
                            <td><?php echo htmlspecialchars($plant['parent_name']); ?></td>
                            <td><?php echo htmlspecialchars($plant['variety_name']); ?></td>
                            <td><?php echo htmlspecialchars($plant['type']); ?></td>
                            <td>
                                <a href="plant.php?id=<?php echo urlencode($plant['plant_id']); ?>" class="btn btn-primary btn-sm">
                                    View
                                    <?php if (in_array($plant['plant_id'], $plantsWithRecords)): ?>
                                        <i class="fas fa-clipboard"></i>
                                    <?php endif; ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">No plants found.</p>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <!-- Add JavaScript for search functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const table = document.getElementById('plantsTable');
            const rows = table.getElementsByTagName('tr');

            searchInput.addEventListener('keyup', function() {
                const searchTerm = searchInput.value.toLowerCase();

                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    let found = false;

                    for (let j = 0; j < cells.length; j++) {
                        const cellText = cells[j].textContent.toLowerCase();
                        if (cellText.includes(searchTerm)) {
                            found = true;
                            break;
                        }
                    }

                    row.style.display = found ? '' : 'none';
                }
            });
        });
    </script>
</body>
</html>

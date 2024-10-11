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

    // Prepare and execute the query to get plants from the database
    $stmt = $pdo->prepare("SELECT plant_id, Parent, Variety, type FROM Plants ORDER BY Parent ASC, Variety ASC");
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
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search plants...">
            </div>
            <div class="col-md-6">
                <div class="btn-group" role="group" aria-label="Filter options">
                    <button type="button" class="btn btn-outline-primary active" data-filter="all">All</button>
                    <?php foreach ($types as $type): ?>
                        <button type="button" class="btn btn-outline-primary" data-filter="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php if (!empty($plants)): ?>
            <table class="table table-striped" id="plantsTable">
                <thead>
                    <tr>
                        <th>Plant ID</th>
                        <th>Parent</th>
                        <th>Variety</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plants as $plant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($plant['plant_id']); ?></td>
                            <td><?php echo htmlspecialchars($plant['Parent']); ?></td>
                            <td><?php echo htmlspecialchars($plant['Variety']); ?></td>
                            <td><?php echo htmlspecialchars($plant['type']); ?></td>
                            <td>
                                <a href="plant.php?id=<?php echo urlencode($plant['plant_id']); ?>" class="btn btn-primary btn-sm">View</a>
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
            const filterButtons = document.querySelectorAll('.btn-group button');

            let currentFilter = 'all';

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();

                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    const type = row.getAttribute('data-type');
                    let found = false;

                    if (currentFilter === 'all' || type === currentFilter) {
                        for (let j = 0; j < cells.length; j++) {
                            const cellText = cells[j].textContent.toLowerCase();
                            if (cellText.includes(searchTerm)) {
                                found = true;
                                break;
                            }
                        }
                    }

                    row.style.display = found ? '' : 'none';
                }
            }

            searchInput.addEventListener('keyup', filterTable);

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.getAttribute('data-filter');
                    filterTable();
                });
            });
        });
    </script>
</body>
</html>

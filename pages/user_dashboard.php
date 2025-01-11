<?php
// Start the session (if not already started)
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Placeholder data for charts (replace with actual data when you have the corresponding tables)
$plantsPlantedData = [12, 19, 3, 5, 2, 3];
$harvestCountData = [5, 10, 15, 20, 25, 30];
$bestPlantsData = [50, 40, 30, 20, 10];
$popularPlantsData = [30, 25, 20, 15, 10];

// Placeholder data for plant records
$plantRecords = [
    ['date' => '2024-05-01', 'event' => 'Seed Sowing', 'location' => 'Indoor Tray', 'notes' => 'Used organic potting mix'],
    ['date' => '2024-05-15', 'event' => 'Germination', 'location' => 'Indoor Tray', 'notes' => '80% germination rate observed'],
    ['date' => '2024-06-01', 'event' => 'Transplanting', 'location' => 'Garden Bed A', 'notes' => 'Transplanted strongest seedlings'],
    ['date' => '2024-07-15', 'event' => 'First Flower', 'location' => 'Garden Bed A', 'notes' => 'Multiple flower buds forming'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .action-btn {
            cursor: pointer;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <?php include '../includes/menu.php'; ?>

    <div class="container mt-5">
        <h1 id="plant-dashboard" class="text-center mb-5">Plant Dashboard</h1>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Plants Planted</h5>
                        <div class="chart-container">
                            <canvas id="plantsPlantedChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Harvest Count</h5>
                        <div class="chart-container">
                            <canvas id="harvestCountChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Best Plants by Yield</h5>
                        <div class="chart-container">
                            <canvas id="bestPlantsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Most Popular Plants</h5>
                        <div class="chart-container">
                            <canvas id="popularPlantsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Plant Records</h5>
                        <table class="table" id="plant-records-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Event</th>
                                    <th>Location</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($plantRecords as $record): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($record['date']); ?></td>
                                    <td><?php echo htmlspecialchars($record['event']); ?></td>
                                    <td><?php echo htmlspecialchars($record['location']); ?></td>
                                    <td><?php echo htmlspecialchars($record['notes']); ?></td>
                                    <td>
                                        <i class="bi bi-pencil-square text-primary action-btn edit-row"></i>
                                        <i class="bi bi-trash text-danger action-btn delete-row"></i>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button id="add-row" class="btn btn-success mt-3">Add New Record</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Plants Planted Chart
            new Chart(document.getElementById('plantsPlantedChart'), {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Plants Planted',
                        data: <?php echo json_encode($plantsPlantedData); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Harvest Count Chart
            new Chart(document.getElementById('harvestCountChart'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Harvest Count',
                        data: <?php echo json_encode($harvestCountData); ?>,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Best Plants by Yield Chart
            new Chart(document.getElementById('bestPlantsChart'), {
                type: 'bar',
                data: {
                    labels: ['Tomatoes', 'Cucumbers', 'Peppers', 'Lettuce', 'Carrots'],
                    datasets: [{
                        label: 'Yield (kg)',
                        data: <?php echo json_encode($bestPlantsData); ?>,
                        backgroundColor: 'rgba(153, 102, 255, 0.6)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Most Popular Plants Chart
            new Chart(document.getElementById('popularPlantsChart'), {
                type: 'pie',
                data: {
                    labels: ['Tomatoes', 'Cucumbers', 'Peppers', 'Lettuce', 'Carrots'],
                    datasets: [{
                        data: <?php echo json_encode($popularPlantsData); ?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });
        });
    </script>

    <script>
        // Simple CRUD operations for the plant records table
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('plant-records-table');
            const addRowBtn = document.getElementById('add-row');

            addRowBtn.addEventListener('click', function() {
                const newRow = table.insertRow(-1);
                newRow.innerHTML = `
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td>
                        <i class="bi bi-pencil-square text-primary action-btn edit-row"></i>
                        <i class="bi bi-trash text-danger action-btn delete-row"></i>
                    </td>
                `;
                addEventListeners(newRow);
            });

            function addEventListeners(row) {
                const editBtn = row.querySelector('.edit-row');
                const deleteBtn = row.querySelector('.delete-row');

                editBtn.addEventListener('click', function() {
                    const cells = row.querySelectorAll('td:not(:last-child)');
                    cells.forEach(cell => {
                        cell.contentEditable = cell.contentEditable === 'true' ? 'false' : 'true';
                    });
                });

                deleteBtn.addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this record?')) {
                        row.remove();
                    }
                });
            }

            // Add event listeners to existing rows
            table.querySelectorAll('tbody tr').forEach(addEventListeners);
        });
    </script>
</body>
</html>

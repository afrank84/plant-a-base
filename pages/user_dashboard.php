<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once 'db_connection.php';

// Fetch user-specific data for charts (example queries)
$user_id = $_SESSION['user_id'];

// Plants Planted Chart Data
$plants_planted_query = "SELECT MONTH(planting_date) as month, COUNT(*) as count FROM plantings WHERE user_id = ? GROUP BY MONTH(planting_date)";
$stmt = $conn->prepare($plants_planted_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$plants_planted_result = $stmt->get_result();
$plants_planted_data = [];
while ($row = $plants_planted_result->fetch_assoc()) {
    $plants_planted_data[$row['month']] = $row['count'];
}

// Harvest Count Chart Data
$harvest_count_query = "SELECT MONTH(harvest_date) as month, COUNT(*) as count FROM harvests WHERE user_id = ? GROUP BY MONTH(harvest_date)";
$stmt = $conn->prepare($harvest_count_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$harvest_count_result = $stmt->get_result();
$harvest_count_data = [];
while ($row = $harvest_count_result->fetch_assoc()) {
    $harvest_count_data[$row['month']] = $row['count'];
}

// Best Plants by Yield Chart Data
$best_plants_query = "SELECT plant_name, SUM(yield_amount) as total_yield FROM harvests WHERE user_id = ? GROUP BY plant_name ORDER BY total_yield DESC LIMIT 5";
$stmt = $conn->prepare($best_plants_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$best_plants_result = $stmt->get_result();
$best_plants_data = [];
while ($row = $best_plants_result->fetch_assoc()) {
    $best_plants_data[$row['plant_name']] = $row['total_yield'];
}

// Most Popular Plants Chart Data
$popular_plants_query = "SELECT plant_name, COUNT(*) as count FROM plantings WHERE user_id = ? GROUP BY plant_name ORDER BY count DESC LIMIT 5";
$stmt = $conn->prepare($popular_plants_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$popular_plants_result = $stmt->get_result();
$popular_plants_data = [];
while ($row = $popular_plants_result->fetch_assoc()) {
    $popular_plants_data[$row['plant_name']] = $row['count'];
}

// Fetch plant records
$plant_records_query = "SELECT * FROM plant_records WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($plant_records_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$plant_records_result = $stmt->get_result();

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
    <?php include 'menu.php'; ?>
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
                                <?php while ($row = $plant_records_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['event']); ?></td>
                                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                                    <td><?php echo htmlspecialchars($row['notes']); ?></td>
                                    <td>
                                        <i class="bi bi-pencil-square text-primary action-btn edit-row" data-id="<?php echo $row['id']; ?>"></i>
                                        <i class="bi bi-trash text-danger action-btn delete-row" data-id="<?php echo $row['id']; ?>"></i>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <button id="add-row" class="btn btn-success mt-3">Add New Record</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/crud_events.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Plants Planted Chart
            new Chart(document.getElementById('plantsPlantedChart'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_keys($plants_planted_data)); ?>,
                    datasets: [{
                        label: 'Plants Planted',
                        data: <?php echo json_encode(array_values($plants_planted_data)); ?>,
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
                    labels: <?php echo json_encode(array_keys($harvest_count_data)); ?>,
                    datasets: [{
                        label: 'Harvest Count',
                        data: <?php echo json_encode(array_values($harvest_count_data)); ?>,
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
                    labels: <?php echo json_encode(array_keys($best_plants_data)); ?>,
                    datasets: [{
                        label: 'Yield (kg)',
                        data: <?php echo json_encode(array_values($best_plants_data)); ?>,
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
                    labels: <?php echo json_encode(array_keys($popular_plants_data)); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_values($popular_plants_data)); ?>,
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
    <?php include 'footer.php'; ?>
</body>
</html>

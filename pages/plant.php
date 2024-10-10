<?php
require_once '../includes/db_connection.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$plant = null;
$parentName = '';
$varietyName = '';

if (isset($_GET['id'])) {
    $plant_id = $_GET['id'];

    try {
        $pdo = getConnection();

        // Prepare and execute the query to get plant information
        $stmt = $pdo->prepare("SELECT parent, variety FROM Plants WHERE plant_id = ?");
        $stmt->execute([$plant_id]);
        $plant = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($plant) {
            // Extract parent and variety from the result
            $parentName = htmlspecialchars($plant['parent']);
            $varietyName = htmlspecialchars($plant['variety']);
        } else {
            $error = "Plant not found.";
        }

    } catch (PDOException $e) {
        $error = "A database error occurred. Please try again later.";
        error_log("Database error: " . $e->getMessage());
    }
} else {
    $error = "No plant ID provided.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Display Template</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .image-box {
            position: relative;
            width: 100%;
            padding-bottom: 100%;
        }
        .image-box img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .image-label {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0,0,0,0.7);
            color: white;
            text-align: center;
            padding: 5px;
        }
        .month-cell {
            width: 8.33%;
            text-align: center;
            font-weight: bold;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>
  <?php include '../includes/menu.php'; ?>
    
    <div class="container mt-5">
        <!-- Check if there's an error, display it if exists -->
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <h1 id="parentName">Parent</h1>
            <h2 id="varietyName">Variety</h2>
            <h3 id="plantType">Type</h3>
        <?php else: ?>
            <!-- Dynamically display the plant details -->
            <h1 id="parentName"><?php echo htmlspecialchars($parentName); ?></h1>
            <h2 id="varietyName"><?php echo htmlspecialchars($varietyName); ?></h2>
            <h3 id="plantType">Type</h3>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="image-box">
                    <img src="https://placehold.co/300" alt="Seed Image">
                    <div class="image-label">Seed</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="image-box">
                    <img src="https://placehold.co/300" alt="Plant Image">
                    <div class="image-label">Plant</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="image-box">
                    <img src="https://placehold.co/300" alt="Flower Image">
                    <div class="image-label">Flower</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="image-box">
                    <img src="https://placehold.co/300" alt="Fruit Image">
                    <div class="image-label">Fruit</div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <img src="https://placehold.co/500x300" alt="Plant Description Image" class="img-fluid">
            </div>
            <div class="col-md-6">
                <h3>Plant Description</h3>
                <p>This is a detailed description of the plant. It includes information about its appearance, growth habits, and any unique characteristics. The description helps gardeners and plant enthusiasts understand the plant's needs and what to expect as it grows.</p>
            </div>
        </div>

        <table class="table table-bordered mb-4">
            <thead>
                <tr>
                    <th scope="col" class="month-cell">Jan</th>
                    <th scope="col" class="month-cell">Feb</th>
                    <th scope="col" class="month-cell">Mar</th>
                    <th scope="col" class="month-cell">Apr</th>
                    <th scope="col" class="month-cell">May</th>
                    <th scope="col" class="month-cell">Jun</th>
                    <th scope="col" class="month-cell">Jul</th>
                    <th scope="col" class="month-cell">Aug</th>
                    <th scope="col" class="month-cell">Sep</th>
                    <th scope="col" class="month-cell">Oct</th>
                    <th scope="col" class="month-cell">Nov</th>
                    <th scope="col" class="month-cell">Dec</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="month-cell"></td>
                    <td class="month-cell"></td>
                    <td class="month-cell bg-success"></td>
                    <td class="month-cell bg-success"></td>
                    <td class="month-cell bg-success"></td>
                    <td class="month-cell bg-success"></td>
                    <td class="month-cell bg-success"></td>
                    <td class="month-cell bg-success"></td>
                    <td class="month-cell bg-success"></td>
                    <td class="month-cell"></td>
                    <td class="month-cell"></td>
                    <td class="month-cell"></td>
                </tr>
            </tbody>
        </table>

        <table class="table table-bordered mb-4">
            <thead>
                <tr>
                    <th scope="col">Description</th>
                    <th scope="col">Data</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">DTG (Days to Germination)</th>
                    <td id="dtg">7-14 days</td>
                </tr>
                <tr>
                    <th scope="row">DTH (Days to Harvest)</th>
                    <td id="dth">60-80 days</td>
                </tr>
                <tr>
                    <th scope="row">Zone</th>
                    <td id="zone">4-9</td>
                </tr>
                <tr>
                    <th scope="row">Sow Depth</th>
                    <td id="sowDepth">1/4 inch</td>
                </tr>
                <tr>
                    <th scope="row">Seed Spacing</th>
                    <td id="seedSpacing">6-12 inches</td>
                </tr>
            </tbody>
        </table>
       
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
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
    $stmt = $pdo->prepare("SELECT plant_id, Parent, Variety FROM Plants ORDER BY Parent ASC, Variety ASC");
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
    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-5">Plants List</h1>

        <?php
        if (!empty($error)) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . "</div>";
        }
        ?>

        <?php if (!empty($plants)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Plant ID</th>
                        <th>Parent</th>
                        <th>Variety</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plants as $plant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($plant['plant_id']); ?></td>
                            <td><?php echo htmlspecialchars($plant['Parent']); ?></td>
                            <td><?php echo htmlspecialchars($plant['Variety']); ?></td>
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
</body>
</html>

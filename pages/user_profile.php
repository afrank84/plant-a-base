<?php
session_start();
require_once '../includes/db_connection.php';  // Assume this file handles database connection

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error_message = '';
$success_message = '';

// Fetch user data
$stmt = $pdo->prepare("SELECT username, email, zone FROM Users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch available zones
$stmt = $pdo->query("SELECT zone FROM Zones ORDER BY zone");
$zones = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $zone = filter_input(INPUT_POST, 'zone', FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format";
    } else {
        // Check if email already exists for another user
        $stmt = $pdo->prepare("SELECT user_id FROM Users WHERE email = ? AND user_id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetchColumn()) {
            $error_message = "Email already in use by another account";
        } else {
            // Update user information
            $stmt = $pdo->prepare("UPDATE Users SET email = ?, zone = ? WHERE user_id = ?");
            if ($stmt->execute([$email, $zone, $user_id])) {
                $success_message = "Profile updated successfully!";
                // Update the user array with new values
                $user['email'] = $email;
                $user['zone'] = $zone;
            } else {
                $error_message = "An error occurred while updating your profile";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/menu.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">User Profile</h1>

        <?php if ($error_message): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="zone" class="form-label">Zone</label>
                <select class="form-select" id="zone" name="zone">
                    <option value="">Select a zone</option>
                    <?php foreach ($zones as $zone): ?>
                        <option value="<?php echo htmlspecialchars($zone); ?>" <?php echo $zone === $user['zone'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($zone); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>

        <div class="mt-4">
            <a href="change_password.php" class="btn btn-secondary">Change Password</a>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once '../includes/db_connection.php';

session_start();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Both email and password are required.";
    } else {
        try {
            $pdo = getConnection();
            
            // Check if user exists
            $stmt = $pdo->prepare("SELECT user_id, username, password_hash FROM Users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Login successful
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $success = "Login successful! Welcome, " . htmlspecialchars($user['username']) . "!";
                // Redirect to dashboard or home page
                // header("Location: dashboard.php");
                // exit();
            } else {
                $error = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $error = "A database error occurred. Please try again later.";
            error_log("Database error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Plant-a-base</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .footer {
            background-color: #f8f9fa;
            padding: 20px 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div id="menu-placeholder"></div>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Login</h1>
        
        <?php
        if (!empty($error)) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . "</div>";
        }
        if (!empty($success)) {
            echo "<div class='alert alert-success'>" . htmlspecialchars($success) . "</div>";
        }
        ?>
        
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <p class="mt-3 text-center">Don't have an account? <a href="register.php">Sign up here</a></p>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <p class="text-muted text-center">&copy; 2024 Plant-a-base. All rights reserved.</p>
        </div>
    </footer>
    <script src="../js/menu.js"></script>
    <script src="../js/footer.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

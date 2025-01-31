<?php
require_once '../includes/db_connection.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please ensure all fields are filled in.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "The email address provided is not valid. Please check and try again.";
    } elseif ($password !== $confirm_password) {
        $error = "The passwords you entered do not match. Please try again.";
    } else {
        try {
            $pdo = getConnection();
            
            // Check if email or username already exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
            $stmt->execute([$email, $username]);
            
            if ($stmt->rowCount() > 0) {
                $error = "The username or email address is already in use. Please choose another.";
            } else {
                // Insert new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
                if ($stmt->execute([$username, $email, $hashed_password])) {
                    $success = "Registration successful! You can now log in.";
                } else {
                    $error = "An unexpected error occurred while creating your account. Please try again later.";
                }
            }
        } catch (PDOException $e) {
            $error = "A database error occurred: " . $e->getMessage();
            // For production: log the error instead of showing it
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Plant-a-base</title>
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
        <h1 class="text-center mb-5">Sign Up</h1>
        
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
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                </form>
                <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
    <?php include('../includes/footer.php'); ?>
    <script src="../js/menu.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

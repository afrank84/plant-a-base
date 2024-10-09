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
$stmt = $pdo->prepare("SELECT username, email, zone, date_joined, profile_picture FROM Users WHERE user_id = ?");
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

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_picture']['name'];
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($filetype, $allowed)) {
            $error_message = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        } else {
            // Generate a unique filename
            $new_filename = $user['username'] . '_' . uniqid() . '.' . $filetype;
            $upload_path = '../uploads/profile_pictures/' . $new_filename;

            // Create image from uploaded file
            if ($filetype == 'jpg' || $filetype == 'jpeg') {
                $image = imagecreatefromjpeg($_FILES['profile_picture']['tmp_name']);
            } elseif ($filetype == 'png') {
                $image = imagecreatefrompng($_FILES['profile_picture']['tmp_name']);
            } elseif ($filetype == 'gif') {
                $image = imagecreatefromgif($_FILES['profile_picture']['tmp_name']);
            }

            if ($image) {
                // Get original dimensions
                $orig_width = imagesx($image);
                $orig_height = imagesy($image);

                // Set new dimensions (e.g., max width of 300px)
                $max_width = 300;
                $max_height = 300;

                // Calculate new dimensions while maintaining aspect ratio
                if ($orig_width > $orig_height) {
                    $new_width = $max_width;
                    $new_height = intval($orig_height * $max_width / $orig_width);
                } else {
                    $new_height = $max_height;
                    $new_width = intval($orig_width * $max_height / $orig_height);
                }

                // Create new image with new dimensions
                $new_image = imagecreatetruecolor($new_width, $new_height);

                // Preserve transparency for PNG files
                if ($filetype == 'png') {
                    imagealphablending($new_image, false);
                    imagesavealpha($new_image, true);
                    $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
                    imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);
                }

                // Resize the image
                imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);

                // Save the resized image
                if ($filetype == 'jpg' || $filetype == 'jpeg') {
                    imagejpeg($new_image, $upload_path, 85); // 85 is the quality (0-100)
                } elseif ($filetype == 'png') {
                    imagepng($new_image, $upload_path, 8); // 8 is the compression level (0-9)
                } elseif ($filetype == 'gif') {
                    imagegif($new_image, $upload_path);
                }

                // Free up memory
                imagedestroy($image);
                imagedestroy($new_image);

                // Update the database with the new filename
                $stmt = $pdo->prepare("UPDATE Users SET profile_picture = ? WHERE user_id = ?");
                if ($stmt->execute([$new_filename, $user_id])) {
                    $success_message .= " Profile picture updated successfully.";
                    $user['profile_picture'] = $new_filename;
                } else {
                    $error_message = "Failed to update profile picture in the database.";
                }
            } else {
                $error_message = "Failed to process the image.";
            }
        }
    }
}

// Determine the profile picture URL
$profile_picture_url = $user['profile_picture'] 
    ? "../uploads/profile_pictures/" . htmlspecialchars($user['profile_picture'])
    : "https://via.placeholder.com/150";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .profile-picture-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        .profile-picture-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s;
            border-radius: 50%;
        }
        .profile-picture-container:hover .profile-picture-overlay {
            opacity: 1;
        }
    </style>
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

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="profile-picture-container" onclick="document.getElementById('profile_picture').click();">
                            <img src="<?php echo $profile_picture_url; ?>" alt="Profile Picture" class="rounded-circle mb-3" width="150" height="150">
                            <div class="profile-picture-overlay">
                                <i class="fas fa-camera"></i> Change Picture
                            </div>
                        </div>
                        <h5 class="card-title"><?php echo htmlspecialchars($user['username']); ?></h5>
                        <p class="card-text">Member since: <?php echo date('F j, Y', strtotime($user['date_joined'])); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <form method="post" enctype="multipart/form-data" id="profile-form">
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
                    <div class="mb-3">
                        <input type="file" class="form-control d-none" id="profile_picture" name="profile_picture" accept="image/*">
                        <small class="form-text text-muted">Click on the profile picture to change it. Allowed formats: JPG, JPEG, PNG, GIF</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>

                <div class="mt-4">
                    <a href="change_password.php" class="btn btn-secondary">Change Password</a>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('profile_picture').addEventListener('change', function(event) {
            if (event.target.files && event.target.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.profile-picture-container img').src = e.target.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            }
        });
    </script>
</body>
</html>

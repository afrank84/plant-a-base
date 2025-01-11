<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
function is_active($page) {
    global $current_page;
    return $current_page === $page ? 'active' : '';
}

// Construct the profile picture URL
$default_avatar = '../img/default_profile_picture.png';
$profile_picture_url = isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture'])
    ? "../uploads/profile_pictures/" . $_SESSION['profile_picture']
    : $default_avatar;
?>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-leaf me-2"></i>Plant-a-base</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo is_active('index.php'); ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo is_active('database.php'); ?>" href="database.php">Database</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo is_active('plant.php'); ?>" href="plant.php">Template</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo is_active('user_dashboard.php'); ?>" href="user_dashboard.php" id="dashboardDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            My Plants
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dashboardDropdown">
                            <li><a class="dropdown-item" href="user_dashboard.php">Dashboard</a></li>
                            <li><a class="dropdown-item" href="view_events.php">All Plant Events</a></li>
                            <li><a class="dropdown-item" href="new_event.php">Add Event</a></li>
                            <li><a class="dropdown-item" href="upload_image_form.php">Upload Image</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo is_active('contact.php'); ?>" href="contact.php">Contact</a>
                </li>
            </ul>
            <form class="d-flex me-3">
                <input class="form-control me-2" type="search" placeholder="Search plants..." aria-label="Search">
                <button class="btn btn-success" type="submit">Search</button>
            </form>
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <img src="<?php echo htmlspecialchars($profile_picture_url); ?>" alt="Profile" class="rounded-circle me-2" width="40" height="40" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer; object-fit: cover;">
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="user_profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="user_dashboard.php">Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="btn btn-outline-light me-2" href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
                    <a class="btn btn-outline-light" href="register.php"><i class="fas fa-user-plus me-1"></i>Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('mouseover', () => {
            link.classList.add('text-success');
        });
        link.addEventListener('mouseout', () => {
            link.classList.remove('text-success');
        });
    });
});
</script>

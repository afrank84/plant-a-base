<?php
$current_page = basename($_SERVER['PHP_SELF']);

function is_active($page) {
    global $current_page;
    return $current_page === $page ? 'active' : '';
}
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
                    <a class="nav-link <?php echo is_active('template.php'); ?>" href="template.php">Template</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo is_active('dashboard.php'); ?>" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo is_active('contact.php'); ?>" href="contact.php">Contact</a>
                </li>
            </ul>
            <form class="d-flex me-3">
                <input class="form-control me-2" type="search" placeholder="Search plants..." aria-label="Search">
                <button class="btn btn-success" type="submit">Search</button>
            </form>
            <div class="d-flex align-items-center">
                <a class="btn btn-outline-light me-2" href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
                <a class="btn btn-outline-light" href="signup.php"><i class="fas fa-user-plus me-1"></i>Sign Up</a>
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

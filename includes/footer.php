<?php
// Include the database connection script
include_once 'db_connection.php';
?>
<footer class="container mt-5">
    <hr>
    <div class="row">
        <div class="col-md-6">
            <p>&copy; <?php echo date("Y"); ?> Plant-a-base. All rights reserved.</p>
            <?php if (isset($db_connection_status) && $db_connection_status): ?>
                <p>Database connection is active.</p>
            <?php else: ?>
                <p>Database connection failed.</p>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <ul class="list-inline text-md-end mb-0">
                <li class="list-inline-item">
                    <a href="https://www.instagram.com/afrank84" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-instagram fa-lg"></i>
                    </a>
                </li>
                <li class="list-inline-item">
                    <a href="https://github.com/afrank84" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-github fa-lg"></i>
                    </a>
                </li>
                <li class="list-inline-item">
                    <a href="https://x.com/afrank84" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-x-twitter fa-lg"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</footer>

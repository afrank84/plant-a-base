<?php
session_start(); // Ensure this is at the very top
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Plant-a-base</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../img/ico/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/ico/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/ico/favicon-16x16.png">
    <style>
        .feature-icon {
            font-size: 2rem;
            color: #28a745;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 0;
            margin-top: 40px;
        }
        .signup-section {
            background-color: #a8d5ba;
            padding: 40px 0;
            margin-top: 40px;
        }
        #captcha-container {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include '../includes/menu.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Welcome to Plant-a-base</h1>
        
        <div class="row mb-5">
            <div class="col-md-6">
                <h2>About Plant-a-base</h2>
                <p>Plant-a-base is your comprehensive digital garden companion. We've created a user-friendly platform that provides detailed information about various plants, helping gardeners, botanists, and plant enthusiasts make informed decisions and care for their green friends.</p>
            </div>
            <div class="col-md-6">
                <img src="../img/plantabase_about.png" alt="Plant-a-base Overview" class="img-fluid rounded" width="500" height="300">
            </div>
        </div>

        <h2 class="text-center mb-4">Key Features</h2>
        <div class="row">
            <?php
            $features = [
                ['icon' => 'fas fa-image', 'title' => 'Visual Lifecycle', 'description' => 'View high-quality images of plants at different stages: seed, plant, flower, and fruit.'],
                ['icon' => 'fas fa-calendar-alt', 'title' => 'Growing Calendar', 'description' => 'Understand the optimal growing seasons for each plant with our monthly view calendar.'],
                ['icon' => 'fas fa-info-circle', 'title' => 'Detailed Information', 'description' => 'Access comprehensive data including germination time, harvest time, planting zones, and more.']
            ];

            foreach ($features as $feature) {
                echo '<div class="col-md-4 mb-4">
                    <div class="text-center">
                        <i class="' . $feature['icon'] . ' feature-icon mb-3"></i>
                        <h3>' . $feature['title'] . '</h3>
                        <p>' . $feature['description'] . '</p>
                    </div>
                </div>';
            }
            ?>
        </div>

        <div class="row mt-5">
            <div class="col-md-8 offset-md-2 text-center">
                <img src="../img/frank_flowers.png" alt="Picture of Developer" class="img-fluid rounded-circle mb-4" style="max-width: 200px;">
                <h2 class="text-center mb-4">What I've Created</h2>
                <p>As a solo developer with a love of plants, I've developed a user-friendly template for displaying detailed plant information. Each plant page includes:</p>
                <ul class="text-start">
                    <li>High-quality images showcasing different stages of plant growth</li>
                    <li>Detailed plant descriptions</li>
                    <li>A monthly growing calendar</li>
                    <li>Key plant data such as days to germination, days to harvest, and planting specifications</li>
                    <li>A record-keeping section for tracking plant progress</li>
                </ul>
                <p>My goal is to provide a comprehensive resource for plant enthusiasts, making it easier than ever to grow and care for a wide variety of plants.</p>
            </div>
        </div>
    </div>

    <!-- Sign-up Section with Custom CAPTCHA -->
    <section class="signup-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <h2 class="mb-4">Interested in Product</h2>
                    <p class="mb-4">Sign up to receive updates about Plant-a-base and be notified when we launch!</p>
                    <form id="signup-form">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Enter your email address" aria-label="Email address" aria-describedby="button-signup" required>
                        </div>
                        <div id="captcha-container" class="mb-2"></div>
                        <div class="input-group mb-3">
                            <input type="number" id="captcha-answer" class="form-control" placeholder="Enter the answer" required>
                        </div>
                        <button class="btn btn-primary" type="submit" id="button-signup">I Would Use This</button>
                        <p id="signup-message" class="mt-3"></p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>

    <script src="../js/captcha.js"></script>
    <script src="https://kit.fontawesome.com/c2e74e567e.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

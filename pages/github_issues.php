<?php
require_once '../includes/db_connection.php';

$error = '';
$issues = [];

try {
    // GitHub API URL for public repository issues
    $owner = 'afrank84';  // GitHub repository owner
    $repo = 'plant-a-base';    // GitHub repository name
    $url = "https://api.github.com/repos/$owner/$repo/issues";

    // Create a stream context to set User-Agent (required by GitHub API)
    $options = [
        'http' => [
            'header' => "User-Agent: PHP\r\n"
        ]
    ];
    $context = stream_context_create($options);

    // Send the request
    $response = file_get_contents($url, false, $context);

    // Check if the response is not false (i.e., successful)
    if ($response !== false) {
        // Decode the JSON response into a PHP array
        $issues = json_decode($response, true);
    } else {
        $error = "Failed to fetch issues from GitHub.";
    }
} catch (Exception $e) {
    $error = "An error occurred: " . $e->getMessage();
    error_log("GitHub API error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Issues - Plant-a-base</title>
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
    <?php include '../includes/menu.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-5">GitHub Issues</h1>

        <?php
        if (!empty($error)) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . "</div>";
        }
        ?>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <?php
                if (!empty($issues)) {
                    foreach ($issues as $issue) {
                        echo "<div class='card mb-3'>";
                        echo "<div class='card-header'><h4>" . htmlspecialchars($issue['title']) . "</h4></div>";
                        echo "<div class='card-body'>";
                        echo "<p>" . htmlspecialchars($issue['body']) . "</p>";
                        echo "<p><strong>State:</strong> " . htmlspecialchars($issue['state']) . "</p>";
                        echo "<p><a href='" . htmlspecialchars($issue['html_url']) . "' target='_blank'>View Issue on GitHub</a></p>";
                        echo "</div></div>";
                    }
                } else {
                    echo "<div class='alert alert-info'>No issues found.</div>";
                }
                ?>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

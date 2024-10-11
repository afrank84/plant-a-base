<?php
require_once '../includes/db_connection.php';

$error = '';
$issues = [];

try {
    $owner = 'afrank84';
    $repo = 'plant-a-base';
    $url = "https://api.github.com/repos/$owner/$repo/issues";

    $options = [
        'http' => [
            'header' => "User-Agent: PHP\r\n"
        ]
    ];
    $context = stream_context_create($options);

    $response = file_get_contents($url, false, $context);

    if ($response !== false) {
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
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
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

        <?php if (!empty($issues)): ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>State</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($issues as $issue): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($issue['number']); ?></td>
                            <td><?php echo htmlspecialchars($issue['title']); ?></td>
                            <td>
                                <span class="badge <?php echo $issue['state'] === 'open' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo htmlspecialchars($issue['state']); ?>
                                </span>
                            </td>
                            <td><?php echo date('Y-m-d', strtotime($issue['created_at'])); ?></td>
                            <td>
                                <a href="<?php echo htmlspecialchars($issue['html_url']); ?>" target="_blank" class="btn btn-sm btn-primary">
                                    View on GitHub
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No issues found.</div>
        <?php endif; ?>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
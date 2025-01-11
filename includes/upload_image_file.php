<?php
// Check if a file has been uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // Define the target directory for uploads
    $targetDir = '../uploads/plant_img/';
    
    // Ensure the target directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    // Get file details
    $fileName = basename($_FILES['file']['name']);
    $targetFile = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Allowed image types
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    // Validate the file type
    if (!in_array($fileType, $allowedTypes)) {
        echo "Error: Only " . implode(", ", $allowedTypes) . " files are allowed.";
        exit;
    }

    // Resize the image to 300x300 pixels
    $maxWidth = 300;
    $maxHeight = 300;

    // Load the image based on its type
    switch ($fileType) {
        case 'jpg':
        case 'jpeg':
            $sourceImage = imagecreatefromjpeg($_FILES['file']['tmp_name']);
            break;
        case 'png':
            $sourceImage = imagecreatefrompng($_FILES['file']['tmp_name']);
            break;
        case 'gif':
            $sourceImage = imagecreatefromgif($_FILES['file']['tmp_name']);
            break;
        default:
            echo "Error: Unsupported file type.";
            exit;
    }

    if (!$sourceImage) {
        echo "Error: Unable to process the uploaded file.";
        exit;
    }

    // Create a blank image with the desired dimensions
    $resizedImage = imagecreatetruecolor($maxWidth, $maxHeight);

    // Get the original image dimensions
    $originalWidth = imagesx($sourceImage);
    $originalHeight = imagesy($sourceImage);

    // Resize the original image into the blank image
    imagecopyresampled(
        $resizedImage,
        $sourceImage,
        0, 0, 0, 0,
        $maxWidth, $maxHeight,
        $originalWidth, $originalHeight
    );

    // Save the resized image to the target directory
    $newFileName = $targetDir . pathinfo($fileName, PATHINFO_FILENAME) . "_300x300." . $fileType;
    switch ($fileType) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($resizedImage, $newFileName);
            break;
        case 'png':
            imagepng($resizedImage, $newFileName);
            break;
        case 'gif':
            imagegif($resizedImage, $newFileName);
            break;
    }

    // Free up memory
    imagedestroy($sourceImage);
    imagedestroy($resizedImage);

    echo "The file has been uploaded and resized to 300x300 pixels as " . htmlspecialchars(basename($newFileName)) . ".";
} else {
    echo "No file uploaded.";
}
?>

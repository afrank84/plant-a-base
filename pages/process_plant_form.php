<?php
require_once '../includes/db_connection.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Function to handle file upload
function handleFileUpload($file_input) {
    if (isset($_FILES[$file_input]) && $_FILES[$file_input]['error'] == 0) {
        $upload_dir = '../uploads/';
        $filename = uniqid() . '_' . $_FILES[$file_input]['name'];
        $filepath = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES[$file_input]['tmp_name'], $filepath)) {
            return $filename;
        }
    }
    return null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $parent = $_POST['parent'];
    $variety = $_POST['variety'];
    $type = $_POST['type'];
    $dtg = $_POST['dtg'];
    $dth = $_POST['dth'];
    $seed_spacing = $_POST['seed_spacing'];
    $row_spacing = $_POST['row_spacing'];
    $depth_to_sow = $_POST['depth_to_sow'];
    $notes_directions = $_POST['notes_directions'];
    $seed_link = $_POST['seed_link'];

    // Handle file uploads
    $img_seed_filename = handleFileUpload('img_seed_filename');
    $img_plant_filename = handleFileUpload('img_plant_filename');
    $img_flower_filename = handleFileUpload('img_flower_filename');
    $img_fruit_yield = handleFileUpload('img_fruit_yield');
    $img_customer_filename = handleFileUpload('img_customer_filename');

    // Handle growing seasons
    $growing_seasons = array();
    for ($i = 1; $i <= 12; $i++) {
        $growing_seasons[] = isset($_POST["growing_season_$i"]) ? 1 : 0;
    }

    // Prepare SQL statement (assuming you're using MySQLi)
    if (isset($_POST['plant_id'])) {
        // Update existing plant
        $sql = "UPDATE Plants SET 
                parent = ?, variety = ?, type = ?, dtg = ?, dth = ?, 
                img_seed_filename = COALESCE(?, img_seed_filename), 
                img_plant_filename = COALESCE(?, img_plant_filename), 
                img_flower_filename = COALESCE(?, img_flower_filename), 
                img_fruit_yield = COALESCE(?, img_fruit_yield), 
                img_customer_filename = COALESCE(?, img_customer_filename), 
                seed_spacing = ?, row_spacing = ?, depth_to_sow = ?, 
                notes_directions = ?, seed_link = ?, 
                growing_season_1 = ?, growing_season_2 = ?, growing_season_3 = ?, 
                growing_season_4 = ?, growing_season_5 = ?, growing_season_6 = ?, 
                growing_season_7 = ?, growing_season_8 = ?, growing_season_9 = ?, 
                growing_season_10 = ?, growing_season_11 = ?, growing_season_12 = ?
                WHERE plant_id = ?";
    } else {
        // Insert new plant
        $sql = "INSERT INTO Plants (
                parent, variety, type, dtg, dth, img_seed_filename, img_plant_filename,
                img_flower_filename, img_fruit_yield, img_customer_filename, seed_spacing,
                row_spacing, depth_to_sow, notes_directions, seed_link, 
                growing_season_1, growing_season_2, growing_season_3, growing_season_4, 
                growing_season_5, growing_season_6, growing_season_7, growing_season_8, 
                growing_season_9, growing_season_10, growing_season_11, growing_season_12
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    $stmt = $conn->prepare($sql);

    if (isset($_POST['plant_id'])) {
        $stmt->bind_param("sssssssssssssssiiiiiiiiiiiii", 
            $parent, $variety, $type, $dtg, $dth, 
            $img_seed_filename, $img_plant_filename, $img_flower_filename, 
            $img_fruit_yield, $img_customer_filename, 
            $seed_spacing, $row_spacing, $depth_to_sow, $notes_directions, $seed_link, 
            $growing_seasons[0], $growing_seasons[1], $growing_seasons[2], $growing_seasons[3], 
            $growing_seasons[4], $growing_seasons[5], $growing_seasons[6], $growing_seasons[7], 
            $growing_seasons[8], $growing_seasons[9], $growing_seasons[10], $growing_seasons[11],
            $_POST['plant_id']
        );
    } else {
        $stmt->bind_param("sssssssssssssssiiiiiiiiiii", 
            $parent, $variety, $type, $dtg, $dth, 
            $img_seed_filename, $img_plant_filename, $img_flower_filename, 
            $img_fruit_yield, $img_customer_filename, 
            $seed_spacing, $row_spacing, $depth_to_sow, $notes_directions, $seed_link, 
            $growing_seasons[0], $growing_seasons[1], $growing_seasons[2], $growing_seasons[3], 
            $growing_seasons[4], $growing_seasons[5], $growing_seasons[6], $growing_seasons[7], 
            $growing_seasons[8], $growing_seasons[9], $growing_seasons[10], $growing_seasons[11]
        );
    }

    if ($stmt->execute()) {
        echo "Plant data successfully saved.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

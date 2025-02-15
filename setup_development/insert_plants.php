<?php
require_once '../includes/db_connection.php'; // Ensure this path is correct
try {
    $pdo = getConnection(); // Ensure database connection is working

    // Sample plant data: parent_name, variety_name, type, dtg_days_to_grow, dth_days_to_harvest, depth_to_sow, seed_spacing, row_spacing
    $plants = [
        ['Tomato', 'Cherry', 'Vegetable', '70', '90', '1/4 inch', '12 inches', '24 inches', 'Prefers full sun and warm temperatures.'],
        ['Lettuce', 'Romaine', 'Vegetable', '30', '50', '1/8 inch', '8 inches', '12 inches', 'Thrives in cooler temperatures.'],
        ['Carrot', 'Nantes', 'Root', '50', '70', '1/4 inch', '2 inches', '4 inches', 'Grows best in loose, sandy soil.'],
        ['Bell Pepper', 'Red', 'Vegetable', '60', '80', '1/4 inch', '18 inches', '24 inches', 'Needs consistent warmth and moisture.'],
        ['Strawberry', 'June-bearing', 'Fruit', '60', '90', '1/8 inch', '12 inches', '18 inches', 'Prefers slightly acidic soil.'],
        ['Cucumber', 'English', 'Vegetable', '55', '75', '1/2 inch', '24 inches', '36 inches', 'Needs trellising for best growth.'],
        ['Spinach', 'Savoy', 'Leafy Green', '35', '50', '1/8 inch', '6 inches', '12 inches', 'Thrives in cool weather.'],
        ['Pumpkin', 'Sugar Pie', 'Fruit', '100', '120', '1 inch', '36 inches', '48 inches', 'Requires plenty of space to spread.'],
        ['Radish', 'French Breakfast', 'Root', '25', '30', '1/4 inch', '1 inch', '4 inches', 'Fast-growing and best in cool weather.'],
        ['Corn', 'Sweet Yellow', 'Vegetable', '75', '90', '1 inch', '10 inches', '30 inches', 'Needs to be grown in blocks for pollination.'],
    ];

    $stmt = $pdo->prepare("
        INSERT INTO plants (
            parent_name, variety_name, type, dtg_days_to_grow, dth_days_to_harvest, depth_to_sow, seed_spacing, row_spacing, 
            seed_image_url, plant_image_url, fruit_image_url, flower_image_url, img_customer_filename, 
            zone_1, zone_2, zone_3, zone_4, zone_5, zone_6, zone_7, zone_8, zone_9, zone_10, zone_11, zone_12, zone_13
        ) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($plants as $plant) {
        list($parent_name, $variety_name, $type, $dtg_days_to_grow, $dth_days_to_harvest, $depth_to_sow, $seed_spacing, $row_spacing) = $plant;

        // Generate unique filenames using "parent_variety" format
        $filename_base = strtolower(str_replace(' ', '_', $parent_name . '_' . $variety_name));
        
        $seed_image_url = "/uploads/plant_images/" . $filename_base . "_seed.jpg";
        $plant_image_url = "/uploads/plant_images/" . $filename_base . "_plant.jpg";
        $fruit_image_url = "/uploads/plant_images/" . $filename_base . "_fruit.jpg";
        $flower_image_url = "/uploads/plant_images/" . $filename_base . "_flower.jpg";
        $img_customer_filename = "/uploads/plant_images/" . $filename_base . "_customer.jpg";

        // Fill all zone fields with 'Yes'
        $zone_values = array_fill(0, 13, 'Yes');

        $stmt->execute([
            $parent_name, $variety_name, $type, $dtg_days_to_grow, $dth_days_to_harvest, $depth_to_sow, $seed_spacing, $row_spacing, 
            $seed_image_url, $plant_image_url, $fruit_image_url, $flower_image_url, $img_customer_filename, 
            $zone_values[0], $zone_values[1], $zone_values[2], $zone_values[3], $zone_values[4], 
            $zone_values[5], $zone_values[6], $zone_values[7], $zone_values[8], $zone_values[9], 
            $zone_values[10], $zone_values[11], $zone_values[12], 
        ]);
        
    }

    echo "✅ 10 sample plants added successfully!";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>

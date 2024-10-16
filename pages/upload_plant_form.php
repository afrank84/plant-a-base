<?php
session_start();
require_once '../includes/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Display Form Template</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #EADDCA; 
        }
        .image-box {
            position: relative;
            width: 100%;
            padding-bottom: 100%;
        }
        .image-box img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .image-label {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0,0,0,0.7);
            color: white;
            text-align: center;
            padding: 5px;
        }
        .month-cell {
            width: 8.33%;
            text-align: center;
            font-weight: bold;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 0;
            margin-top: 40px;
        }
        .form-check-input[type="checkbox"] {
            width: 100%;
            height: 20px;
        }
    </style>
</head>
<body>
    <?php include '../includes/menu.php'; ?>
    
    <div class="container mt-5">
        <form id="plantForm">
            <div class="mb-3">
                <label for="parentName" class="form-label">Parent Plant Name</label>
                <input type="text" class="form-control" id="parentName" name="parentName" required>
            </div>
            <div class="mb-3">
                <label for="varietyName" class="form-label">Variety Name</label>
                <input type="text" class="form-control" id="varietyName" name="varietyName" required>
            </div>
            <div class="mb-3">
                <label for="plantType" class="form-label">Type</label>
                <input type="text" class="form-control" id="plantType" name="plantType" required>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="image-box">
                        <img src="https://placehold.co/300" alt="Seed Image" id="seedImg">
                        <div class="image-label">Seed</div>
                    </div>
                    <input type="file" class="form-control mt-2" id="seedImgUpload" name="seedImgUpload" accept="image/*">
                </div>
                <div class="col-md-3 mb-3">
                    <div class="image-box">
                        <img src="https://placehold.co/300" alt="Plant Image" id="plantImg">
                        <div class="image-label">Plant</div>
                    </div>
                    <input type="file" class="form-control mt-2" id="plantImgUpload" name="plantImgUpload" accept="image/*">
                </div>
                <div class="col-md-3 mb-3">
                    <div class="image-box">
                        <img src="https://placehold.co/300" alt="Flower Image" id="flowerImg">
                        <div class="image-label">Flower</div>
                    </div>
                    <input type="file" class="form-control mt-2" id="flowerImgUpload" name="flowerImgUpload" accept="image/*">
                </div>
                <div class="col-md-3 mb-3">
                    <div class="image-box">
                        <img src="https://placehold.co/300" alt="Fruit Image" id="fruitImg">
                        <div class="image-label">Fruit</div>
                    </div>
                    <input type="file" class="form-control mt-2" id="fruitImgUpload" name="fruitImgUpload" accept="image/*">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <img src="https://placehold.co/500x300" alt="Plant Description Image" class="img-fluid" id="descriptionImg">
                    <input type="file" class="form-control mt-2" id="descriptionImgUpload" name="descriptionImgUpload" accept="image/*">
                </div>
                <div class="col-md-6">
                    <h3>Plant Description</h3>
                    <textarea class="form-control" id="plantDescription" name="plantDescription" rows="5" required></textarea>
                </div>
            </div>

            <table class="table table-bordered mb-4">
                <thead>
                    <tr>
                        <th scope="col" class="month-cell">Jan</th>
                        <th scope="col" class="month-cell">Feb</th>
                        <th scope="col" class="month-cell">Mar</th>
                        <th scope="col" class="month-cell">Apr</th>
                        <th scope="col" class="month-cell">May</th>
                        <th scope="col" class="month-cell">Jun</th>
                        <th scope="col" class="month-cell">Jul</th>
                        <th scope="col" class="month-cell">Aug</th>
                        <th scope="col" class="month-cell">Sep</th>
                        <th scope="col" class="month-cell">Oct</th>
                        <th scope="col" class="month-cell">Nov</th>
                        <th scope="col" class="month-cell">Dec</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="month-cell"><input type="checkbox" class="form-check-input" name="month[]" value="Jan"></td>
                        <td class="month-cell"><input type="checkbox" class="form-check-input" name="month[]" value="Feb"></td>
                        <td class="month-cell"><input type="checkbox" class="form-check-input" name="month[]" value="Mar"></td>
                        <td class="month-cell"><input type="checkbox" class="form-check-input" name="month[]" value="Apr"></td>
                        <td class="month-cell"><input type="checkbox" class="form-check-input" name="month[]" value="May"></td>
                        <td class="month-cell"><input type="checkbox" class="form-check-input" name="month[]" value="Jun"></td>
                        <td class="month-cell"><input type="checkbox" class="form-check-input" name="month[]" value="Jul"></td>
                        <td class="month-cell"><input type="checkbox" class="form-check-input" name="month[]" value="Aug"></td>
                        <td class="month-cell"><input type="checkbox" class="form-check-input" name="month[]" value="Sep"></td>
                        <td class="month-cell"><input type="checkbox" class="form-check-input" name="month[]" value="Oct"></td>
                        <td class="month-cell"><input type="checkbox" class="form-check-input" name="month[]" value="Nov"></td>
                        <td class="month-cell"><input type="checkbox" class="form-check-input" name="month[]" value="Dec"></td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered mb-4">
                <thead>
                    <tr>
                        <th scope="col">Description</th>
                        <th scope="col">Data</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">DTG (Days to Germination)</th>
                        <td><input type="text" class="form-control" id="dtg" name="dtg" required></td>
                    </tr>
                    <tr>
                        <th scope="row">DTH (Days to Harvest)</th>
                        <td><input type="text" class="form-control" id="dth" name="dth" required></td>
                    </tr>
                    <tr>
                        <th scope="row">Zone</th>
                        <td><input type="text" class="form-control" id="zone" name="zone" required></td>
                    </tr>
                    <tr>
                        <th scope="row">Sow Depth</th>
                        <td><input type="text" class="form-control" id="sowDepth" name="sowDepth" required></td>
                    </tr>
                    <tr>
                        <th scope="row">Seed Spacing</th>
                        <td><input type="text" class="form-control" id="seedSpacing" name="seedSpacing" required></td>
                    </tr>
                </tbody>
            </table>
            
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add JavaScript to handle form submission and image uploads
        document.getElementById('plantForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Here you would typically send the form data to a server
            console.log('Form submitted');
        });

        function handleImageUpload(inputId, imgId) {
            document.getElementById(inputId).addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById(imgId).src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        handleImageUpload('seedImgUpload', 'seedImg');
        handleImageUpload('plantImgUpload', 'plantImg');
        handleImageUpload('flowerImgUpload', 'flowerImg');
        handleImageUpload('fruitImgUpload', 'fruitImg');
        handleImageUpload('descriptionImgUpload', 'descriptionImg');
    </script>
</body>
</html>

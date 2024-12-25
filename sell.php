<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo "You must log in to add a property.";
        exit;
    }

    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
    $area = $_POST['area'];
    $location = $_POST['location'];
    $price = $_POST['price'];

    // Insert property details along with user ID
    $sql = "INSERT INTO land_properties (area, location, price, user_id) VALUES (:area, :location, :price, :user_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':area', $area);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    echo "Property added successfully!";
}
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Panel</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #fff; /* Set background to white */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 600px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
            margin: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        label {
            font-size: 14px;
            color: #333;
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .property-section {
            display: none;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
        }

        .form-actions button {
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }

        .form-actions .cancel-btn {
            background-color: #dc3545;
            color: #fff;
        }

        .form-actions .cancel-btn:hover {
            background-color: #b02a37;
        }

        .form-actions .submit-btn {
            background-color: #28a745;
            color: #fff;
        }

        .form-actions .submit-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Seller Panel</h1>
        <div id="property-selection">
            <button class="btn" id="list-land-btn">List Land</button>
            <button class="btn" id="list-house-btn">List House</button>
        </div>

        <div id="land-form" class="property-section">
            <h2>List Your Land</h2>
            <form action="./submit_land.php" method="post" enctype="multipart/form-data">
                <label for="area">Area of Land:</label>
                <input type="text" id="area" name="area" placeholder="Enter land area in sq.ft" required>
                
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" placeholder="Enter location" required>
                
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" placeholder="Enter price in nepali" required>
                
                <label for="map_image">Upload Map Image:</label>
                <input type="file" id="map_image" name="map_image" accept="image/*" required>
                
                <label for="property_images">Upload Property Images:</label>
                <input type="file" id="property_images" name="property_images[]" multiple accept="image/*">
                
                <div class="form-actions">
                    <button type="button" class="cancel-btn" onclick="resetForms()">Cancel</button>
                    <button type="submit" class="submit-btn">Submit</button>
                </div>
            </form>
        </div>

        <div id="house-form" class="property-section">
            <h2>List Your House</h2>
            <form action="./submit_house.php" method="post" enctype="multipart/form-data"></form>
                <label for="floors">Total Floors:</label>
                <input type="number" id="floors" name="floors" placeholder="Enter total floors" required>
                
                <label for="bedrooms">Bedrooms:</label>
                <input type="number" id="bedrooms" name="bedrooms" placeholder="Enter number of bedrooms" required>
                
                <label for="living_rooms">Living Rooms:</label>
                <input type="number" id="living_rooms" name="living_rooms" placeholder="Enter number of living rooms" required>
                
                <label for="kitchens">Kitchens:</label>
                <input type="number" id="kitchens" name="kitchens" placeholder="Enter number of kitchens" required>
                
                <label for="washrooms">Washrooms:</label>
                <input type="number" id="washrooms" name="washrooms" placeholder="Enter number of washrooms" required>
                
                <label for="attached_washrooms">Attached Washrooms:</label>
                <input type="number" id="attached_washrooms" name="attached_washrooms" placeholder="Enter number of attached washrooms" required>
                
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" placeholder="Enter location" required>
                
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" placeholder="Enter price in nepali" required>
                
                <label for="map_image">Upload Map Image:</label>
                <input type="file" id="map_image" name="map_image" accept="image/*" required>
                
                <label for="property_images">Upload Property Images:</label>
                <input type="file" id="property_images" name="property_images[]" multiple accept="image/*">
                
                <div class="form-actions">
                    <button type="button" class="cancel-btn" onclick="resetForms()">Cancel</button>
                    <button type="submit" class="submit-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Form visibility management
        const landForm = document.getElementById('land-form');
        const houseForm = document.getElementById('house-form');
        const propertySelection = document.getElementById('property-selection');

        document.getElementById('list-land-btn').onclick = () => {
            propertySelection.style.display = 'none';
            landForm.style.display = 'block';
        };

        document.getElementById('list-house-btn').onclick = () => {
            propertySelection.style.display = 'none';
            houseForm.style.display = 'block';
        };

        function resetForms() {
            landForm.style.display = 'none';
            houseForm.style.display = 'none';
            propertySelection.style.display = 'block';
        }
    </script>
</body>
</html>

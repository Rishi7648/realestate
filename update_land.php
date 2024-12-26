<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must log in to update a property.";
    exit;
}

// Get the property ID from the URL
if (!isset($_GET['id'])) {
    echo "Property ID is missing.";
    exit;
}

$property_id = $_GET['id'];

// Fetch the property details from the database
$sql = "SELECT * FROM land_properties WHERE id = :property_id AND user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':property_id', $property_id);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$property = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$property) {
    echo "Property not found or you are not authorized to edit it.";
    exit;
}

// Handle the form submission for updating property
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $area = $_POST['area'];
    $location = $_POST['location'];
    $price = $_POST['price'];

    // Handle image uploads
    $property_images = $_FILES['property_images'];
    $map_image = $_FILES['map_image'];

    // Update property in the database
    $sql = "UPDATE land_properties SET area = :area, location = :location, price = :price WHERE id = :property_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':area', $area);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':property_id', $property_id);

    if ($stmt->execute()) {
        echo "Property details updated successfully!";

        // Handle property images upload
        $upload_dir = 'uploads/';
        foreach ($property_images['tmp_name'] as $key => $tmp_name) {
            if ($property_images['error'][$key] == 0) {
                $img_name = $property_images['name'][$key];
                $img_tmp_name = $property_images['tmp_name'][$key];
                $img_path = $upload_dir . basename($img_name);
                
                // File type validation (e.g., image formats)
                if (in_array(pathinfo($img_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                    if (move_uploaded_file($img_tmp_name, $img_path)) {
                        // Save the image path in the database
                        $image_sql = "INSERT INTO property_images (property_id, image_path) VALUES (:property_id, :image_path)";
                        $image_stmt = $conn->prepare($image_sql);
                        $image_stmt->bindParam(':property_id', $property_id);
                        $image_stmt->bindParam(':image_path', $img_path);
                        $image_stmt->execute();
                    }
                } else {
                    echo "Invalid file format for property image.";
                }
            }
        }

        // Handle map image upload
        if ($map_image['error'] == 0) {
            $map_name = $map_image['name'];
            $map_tmp_name = $map_image['tmp_name'];
            $map_path = 'uploads/maps/' . basename($map_name);
            
            // File type validation for map image (e.g., image formats)
            if (in_array(pathinfo($map_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                if (move_uploaded_file($map_tmp_name, $map_path)) {
                    // Save map image path in the database
                    $map_sql = "UPDATE land_properties SET map_image = :map_image WHERE id = :property_id";
                    $map_stmt = $conn->prepare($map_sql);
                    $map_stmt->bindParam(':map_image', $map_path);
                    $map_stmt->bindParam(':property_id', $property_id);
                    $map_stmt->execute();
                }
            } else {
                echo "Invalid file format for map image.";
            }
        }
    } else {
        echo "Failed to update property details.";
    }
}

// Don't close the connection here; it's needed later to fetch images
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Land Property</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            background-color: white;
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .image-preview {
            margin-top: 20px;
            text-align: center;
        }

        .image-preview img {
            max-width: 200px;
            margin: 5px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        a {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Update Land Property</h1>
    </header>
    <form action="update_land.php?id=<?php echo htmlspecialchars($property['id']); ?>" method="POST" enctype="multipart/form-data">
        <label for="area">Area:</label>
        <input type="text" name="area" id="area" value="<?php echo htmlspecialchars($property['area']); ?>" required>

        <label for="location">Location:</label>
        <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($property['location']); ?>" required>

        <label for="price">Price:</label>
        <input type="text" name="price" id="price" value="<?php echo htmlspecialchars($property['price']); ?>" required>

        <label for="property_images">Upload New Images:</label>
        <input type="file" name="property_images[]" multiple>

        <label for="map_image">Upload Map Image:</label>
        <input type="file" name="map_image" accept="image/*">

        <button type="submit">Update Property</button>
    </form>

    <a href="my_properties.php">Back to My Properties</a>

    <div class="image-preview">
        <?php
        // Fetch and display the property images
        $image_sql = "SELECT * FROM property_images WHERE property_id = :property_id";
        $image_stmt = $conn->prepare($image_sql);
        $image_stmt->bindParam(':property_id', $property_id);
        $image_stmt->execute();
        $images = $image_stmt->fetchAll(PDO::FETCH_ASSOC);

        $image_path = 'uploads/';
        $map_path = 'uploads/maps/';

        // Display property images
        foreach ($images as $img) {
            echo '<img src="' . $image_path . $img['image_path'] . '" alt="Property Image">';
        }

        // Display the map image if available
        if (!empty($property['map_image'])) {
            echo '<img src="' . $map_path . $property['map_image'] . '" alt="Map Image">';
        }
        ?>
    </div>
</body>
</html>

<?php
// Close the database connection after all operations
$conn = null;
?>

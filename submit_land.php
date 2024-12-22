<?php
// Database connection
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $area = $_POST['area'];
    $location = $_POST['location'];
    $price = $_POST['price'];

    // Handle file uploads
    $map_image = $_FILES['map_image']['name'];
    $map_image_tmp = $_FILES['map_image']['tmp_name'];
    $property_images = $_FILES['property_images']['name'];
    $property_images_tmp = $_FILES['property_images']['tmp_name'];

    // Directory to store uploaded files
    $upload_dir = "uploads/";

    // Save the map image
    $map_image_path = $upload_dir . basename($map_image);
    if (!move_uploaded_file($map_image_tmp, $map_image_path)) {
        die("Failed to upload map image.");
    }

    // Save property images
    $property_images_paths = [];
    foreach ($property_images_tmp as $index => $tmp_name) {
        $file_name = basename($property_images[$index]);
        $file_path = $upload_dir . $file_name;
        if (move_uploaded_file($tmp_name, $file_path)) {
            $property_images_paths[] = $file_path;
        } else {
            die("Failed to upload property image: " . $file_name);
        }
    }
    $property_images_json = json_encode($property_images_paths);

    // Prepare SQL statement
    $sql = "INSERT INTO land_properties (area, location, price, map_image, property_images) 
            VALUES (:area, :location, :price, :map_image, :property_images)";
    $stmt = $conn->prepare($sql); // Ensure $sql is a string

    // Bind parameters
    $stmt->bindValue(':area', $area);
    $stmt->bindValue(':location', $location);
    $stmt->bindValue(':price', $price);
    $stmt->bindValue(':map_image', $map_image_path);
    $stmt->bindValue(':property_images', $property_images_json);

    // Execute query
    if ($stmt->execute()) {
        echo "Land property listed successfully!";
    } else {
        echo "Error: " . implode(", ", $stmt->errorInfo()); // Show more detailed error
    }
}
?>

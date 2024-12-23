<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
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
    if ($map_image) {
        $map_image_path = $upload_dir . basename($map_image);
        move_uploaded_file($map_image_tmp, $map_image_path);
    } else {
        $map_image_path = $_POST['existing_map_image']; // Retain old map image if no new one is uploaded
    }

    // Save property images
    $property_images_paths = [];
    foreach ($property_images_tmp as $index => $tmp_name) {
        $file_name = basename($property_images[$index]);
        $file_path = $upload_dir . $file_name;
        move_uploaded_file($tmp_name, $file_path);
        $property_images_paths[] = $file_path;
    }
    $property_images_json = json_encode($property_images_paths);

    // Prepare SQL statement to update property
    $sql = "UPDATE land_properties 
            SET area = :area, location = :location, price = :price, map_image = :map_image, property_images = :property_images
            WHERE id = :id";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindValue(':area', $area);
    $stmt->bindValue(':location', $location);
    $stmt->bindValue(':price', $price);
    $stmt->bindValue(':map_image', $map_image_path);
    $stmt->bindValue(':property_images', $property_images_json);
    $stmt->bindValue(':id', $id);

    // Execute the update query
    if ($stmt->execute()) {
        echo "Property updated successfully!";
    } else {
        echo "Error: " . implode(", ", $stmt->errorInfo());
    }
}
?>

<?php
// Database connection
include 'db.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $area = $_POST['area'];
    $location = $_POST['location'];
    $price = $_POST['price'];

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
$sql = $conn->prepare("INSERT INTO land_properties (area, location, price, map_image, property_images) 
                       VALUES (?, ?, ?, ?, ?)");
$sql->bind_param("sssss", $area, $location, $price, $map_image_path, $property_images_json);

if ($sql->execute()) {
    echo "Land property listed successfully!";
} else {
    echo "Error: " . $sql->error;
}


?>

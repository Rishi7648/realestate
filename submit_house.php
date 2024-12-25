<?php
session_start();
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("User not logged in.");
    }

    // Retrieve form data
    $floors = $_POST['floors'];
    $bedrooms = $_POST['bedrooms'];
    $living_rooms = $_POST['living_rooms'];
    $kitchens = $_POST['kitchens'];
    $washrooms = $_POST['washrooms'];
    $attached_washrooms = $_POST['attached_washrooms'];
    $map_image = $_FILES['map_image']['name'];
    $property_images = $_FILES['property_images'];
    $user_id = $_SESSION['user_id']; // Get the user ID from the session

    // Handle file uploads
    $target_dir = "uploads/";
    $map_image_target = $target_dir . basename($map_image);
    move_uploaded_file($_FILES['map_image']['tmp_name'], $map_image_target);

    $property_images_paths = [];
    foreach ($property_images['name'] as $key => $name) {
        $target_file = $target_dir . basename($name);
        move_uploaded_file($property_images['tmp_name'][$key], $target_file);
        $property_images_paths[] = $target_file;
    }
    $property_images_json = json_encode($property_images_paths);

    // Insert data into the database
    $query = "INSERT INTO houseproperties (user_id, floors, bedrooms, living_rooms, kitchens, washrooms, attached_washrooms, map_image, property_images) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiiiiisss", $user_id, $floors, $bedrooms, $living_rooms, $kitchens, $washrooms, $attached_washrooms, $map_image_target, $property_images_json);
    
    if ($stmt->execute()) {
        echo "Property listed successfully.";
    } else {
        echo "Failed to list property: " . $conn->error;
    }
}
?>
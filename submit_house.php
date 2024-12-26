<?php
session_start();
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo "You must log in to add a property.";
        exit;
    }

    try {
        $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
        $floors = $_POST['floors'];
        $bedrooms = $_POST['bedrooms'];
        $living_rooms = $_POST['living_rooms'];
        $kitchens = $_POST['kitchens'];
        $washrooms = $_POST['washrooms'];
        $attached_washrooms = $_POST['attached_washrooms'];
        $location = $_POST['location'];
        $price = $_POST['price'];
        $map_image = $_FILES['map_image']['name'];
        $property_images = implode(',', $_FILES['property_images']['name']); // Convert array to a comma-separated string
        $created_at = date('Y-m-d H:i:s');

        // File upload logic
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Upload map image
        $map_image_path = $upload_dir . basename($map_image);
        move_uploaded_file($_FILES['map_image']['tmp_name'], $map_image_path);

        // Upload property images
        foreach ($_FILES['property_images']['tmp_name'] as $key => $tmp_name) {
            $image_path = $upload_dir . basename($_FILES['property_images']['name'][$key]);
            move_uploaded_file($tmp_name, $image_path);
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO houseproperties 
                (user_id, location, price, floors, bedrooms, living_rooms, kitchens, washrooms, attached_washrooms, map_image, property_images, created_at) 
                VALUES (:user_id, :location, :price, :floors, :bedrooms, :living_rooms, :kitchens, :washrooms, :attached_washrooms, :map_image, :property_images, :created_at)";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':location', $location, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':floors', $floors, PDO::PARAM_INT);
        $stmt->bindParam(':bedrooms', $bedrooms, PDO::PARAM_INT);
        $stmt->bindParam(':living_rooms', $living_rooms, PDO::PARAM_INT);
        $stmt->bindParam(':kitchens', $kitchens, PDO::PARAM_INT);
        $stmt->bindParam(':washrooms', $washrooms, PDO::PARAM_INT);
        $stmt->bindParam(':attached_washrooms', $attached_washrooms, PDO::PARAM_INT);
        $stmt->bindParam(':map_image', $map_image, PDO::PARAM_STR);
        $stmt->bindParam(':property_images', $property_images, PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        echo "House property added successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<?php
include 'db.php';

if (isset($_GET['id'])) {
    $property_id = $_GET['id'];

    // Fetch property details based on the property ID from the house_properties table
    $sql = "SELECT * FROM houseproperties WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $property_id);
    $stmt->execute();
    $property = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$property) {
        echo "Property not found.";
        exit;
    }

    // Display the property details
    echo "<h2>Property Details</h2>";
    echo "<p><strong>Total Floors:</strong> " . $property['floors'] . "</p>";
    echo "<p><strong>Bedrooms:</strong> " . $property['bedrooms'] . "</p>";
    echo "<p><strong>Living Rooms:</strong> " . $property['living_rooms'] . "</p>";
    echo "<p><strong>Kitchens:</strong> " . $property['kitchens'] . "</p>";
    echo "<p><strong>Washrooms:</strong> " . $property['washrooms'] . "</p>";
    echo "<p><strong>Attached Washrooms:</strong> " . $property['attached_washrooms'] . "</p>";
    echo "<p><strong>Location:</strong> " . $property['location'] . "</p>";
    echo "<p><strong>Price:</strong> " . $property['price'] . " NPR</p>";

    // Display map image
    echo "<p><strong>Map:</strong><br><img src='" . $property['map_image'] . "' alt='Property Map' width='300'></p>";

    // Display property images (if any)
    $property_images = json_decode($property['property_images'], true);
    if ($property_images) {
        echo "<p><strong>Property Images:</strong><br>";
        foreach ($property_images as $image) {
            echo "<img src='$image' alt='Property Image' width='300' style='margin-right: 10px;'>";
        }
        echo "</p>";
    }
} else {
    echo "Property ID is missing.";
}
?>

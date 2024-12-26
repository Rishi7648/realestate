<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $property_id = $_GET['id'];

    // Fetch property details to delete images from the server
    $sql = "SELECT map_image, property_images FROM land_properties WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $property_id);
    $stmt->execute();
    $property = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($property) {
        // Delete the map image and property images from the server
        
       

        // Delete the property from the database
        $delete_sql = "DELETE FROM land_properties WHERE id = :id";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bindValue(':id', $property_id);
        if ($delete_stmt->execute()) {
            echo "Property deleted successfully!";
        } else {
            echo "Error deleting property.";
        }
    } else {
        echo "Property not found.";
    }
}
?>

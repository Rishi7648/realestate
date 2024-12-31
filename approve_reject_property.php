<?php
session_start();
include 'db.php'; // Include the database connection file

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "You must log in as an admin to perform this action.";
    exit;
}

// Validate and sanitize user input
if (isset($_POST['property_id'], $_POST['property_type'], $_POST['action'])) {
    $property_id = intval($_POST['property_id']); // Ensure it's an integer
    $property_type = $_POST['property_type'];
    $action = intval($_POST['action']); // Ensure it's an integer (1 for approve, 0 for reject)

    // Validate property type (should be either 'land' or 'house')
    if ($property_type !== 'land' && $property_type !== 'house') {
        echo "Invalid property type.";
        exit;
    }

    // Validate action (should be either 1 or 0)
    if ($action !== 1 && $action !== 0) {
        echo "Invalid action. Use 1 for approve and 0 for reject.";
        exit;
    }

    try {
        if ($action == 1) {
            // Update the property status to 'approved' if action is approve
            if ($property_type == 'land') {
                $sql = "UPDATE land_properties SET status = :status WHERE id = :property_id";
            } elseif ($property_type == 'house') {
                $sql = "UPDATE houseproperties SET status = :status WHERE id = :property_id";
            }

            $stmt = $conn->prepare($sql);
            $status = 'approved';
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':property_id', $property_id);

            // Execute the query
            if ($stmt->execute()) {
                echo "Property status updated to approved.";
            } else {
                echo "Failed to update property status.";
            }
        } else {
            // If rejected, delete the property
            if ($property_type == 'land') {
                $sql = "DELETE FROM land_properties WHERE id = :property_id";
            } elseif ($property_type == 'house') {
                $sql = "DELETE FROM houseproperties WHERE id = :property_id";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':property_id', $property_id);

            // Execute the deletion query
            if ($stmt->execute()) {
                echo "Property rejected and deleted.";
            } else {
                echo "Failed to delete property.";
            }
        }
    } catch (PDOException $e) {
        // Handle any errors during the database operation
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Required data not provided.";
}
?>

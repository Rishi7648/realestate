<?php
session_start();
include 'db.php'; // Include the database connection file

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "You must log in as an admin to perform this action.";
    exit;
}

// Get the raw POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate and sanitize user input
if (isset($data['property_id'], $data['property_type'], $data['action'])) {
    $property_id = intval($data['property_id']); // Ensure it's an integer
    $property_type = $data['property_type'];
    $action = intval($data['action']); // Ensure it's an integer (1 for approve, 0 for reject)

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
                $sql = "UPDATE land_properties SET status = 'approved' WHERE id = :property_id";
            } elseif ($property_type == 'house') {
                $sql = "UPDATE houseproperties SET status = 'approved' WHERE id = :property_id";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':property_id', $property_id);

            // Debugging: Check if the query was executed successfully
            if (!$stmt->execute()) {
                print_r($stmt->errorInfo()); // Output errors if any
                echo "Failed to update property status.";
            } else {
                echo "Property status updated to approved.";
            }
        } else {
           // If rejected, update the property status to 'rejected'
           if ($property_type == 'land') {
            $sql = "UPDATE land_properties SET status = 'rejected' WHERE id = :property_id";
        } elseif ($property_type == 'house') {
            $sql = "UPDATE houseproperties SET status = 'rejected' WHERE id = :property_id";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':property_id', $property_id);

        // Debugging: Check if the query was executed successfully
        if (!$stmt->execute()) {
            print_r($stmt->errorInfo()); // Output errors if any
            echo "Failed to update property status.";
        } else {
            echo "Property status updated to rejected.";
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
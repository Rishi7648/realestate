<?php
// Include database connection
include 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in first!";
    exit;
}

// Get the user_id from session
$user_id = $_SESSION['user_id'];

// Check if 'id' parameter is provided in the URL
if (!isset($_GET['id'])) {
    echo "Error: Property ID is missing.";
    exit;
}

$property_id = $_GET['id'];
echo var_dump($property_id);
// Fetch property details based on the user_id and property_id
$sql = "SELECT * FROM land_properties WHERE id = :id AND user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':id', $property_id);
$stmt->bindValue(':user_id', $user_id);
$stmt->execute();
$property = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if property is found
if (!$property) {
    echo "Property not found or you do not have permission to view this property.";
    exit;
}

// Handle the delete action
if (isset($_POST['delete'])) {
    // Delete the property
    $delete_sql = "DELETE FROM land_properties WHERE id = :id AND user_id = :user_id";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bindValue(':id', $property_id);
    $delete_stmt->bindValue(':user_id', $user_id);
    if ($delete_stmt->execute()) {
        echo "Property deleted successfully!";
        exit;
    } else {
        echo "Error deleting property.";
    }
}

// Handle the update action
if (isset($_POST['update'])) {
    // Retrieve and update form data
    $area = $_POST['area'];
    $location = $_POST['location'];
    $price = $_POST['price'];

    // Update property details
    $update_sql = "UPDATE land_properties SET area = :area, location = :location, price = :price WHERE id = :id AND user_id = :user_id";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bindValue(':area', $area);
    $update_stmt->bindValue(':location', $location);
    $update_stmt->bindValue(':price', $price);
    $update_stmt->bindValue(':id', $property_id);
    $update_stmt->bindValue(':user_id', $user_id);

    if ($update_stmt->execute()) {
        echo "Property updated successfully!";
        // Reload the page to show the updated values
        header("Location: my_property.php?id=$property_id");
        exit;
    } else {
        echo "Error updating property.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Property</title>
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 70%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .property-detail {
            margin-bottom: 20px;
        }

        .property-detail label {
            font-weight: bold;
        }

        .property-detail p {
            margin: 5px 0;
        }

        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-actions {
            text-align: center;
        }

        .btn {
            padding: 10px 20px;
            margin: 5px;
            border-radius: 4px;
            cursor: pointer;
        }

        .update-btn {
            background-color: #28a745;
            color: white;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .cancel-btn {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Property Details</h1>
        <?php if (isset($property)): ?>
        <div class="property-detail">
            <label>Area:</label>
            <p><?php echo htmlspecialchars($property['area']); ?> sq.ft</p>

            <label>Location:</label>
            <p><?php echo htmlspecialchars($property['location']); ?></p>

            <label>Price:</label>
            <p><?php echo htmlspecialchars($property['price']); ?> NPR</p>

            <label>Map:</label>
            <p><img src="<?php echo htmlspecialchars($property['map_image']); ?>" width="200" alt="Map"></p>

            <label>Property Images:</label>
            <p>
                <?php
                $images = json_decode($property['property_images'], true);
                if (is_array($images)) {
                    foreach ($images as $image) {
                        echo "<img src='" . htmlspecialchars($image) . "' width='150' alt='Property Image' style='margin-right: 10px;'>";
                    }
                }
                ?>
            </p>
        </div>

        <h2>Update Property</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="area">Area of Land:</label>
            <input type="text" id="area" name="area" value="<?php echo htmlspecialchars($property['area']); ?>" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($property['location']); ?>" required>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($property['price']); ?>" required>

            <div class="form-actions">
                <button type="submit" name="update" class="btn update-btn">Update</button>
                <button type="button" class="btn cancel-btn" onclick="window.location.href='my_property.php?id=<?php echo $property_id; ?>'">Cancel</button>
            </div>
        </form>

        <h2>Delete Property</h2>
        <form method="POST">
            <div class="form-actions">
                <button type="submit" name="delete" class="btn delete-btn">Delete Property</button>
            </div>
        </form>
        <?php else: ?>
            <p>No property found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

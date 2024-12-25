<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must log in to view your properties.";
    exit;
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch properties for the logged-in user
$sql = "SELECT * FROM land_properties WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Properties</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .property-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            transition: box-shadow 0.3s ease;
        }
        .property-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .property-details {
            margin-bottom: 10px;
        }
        .property-actions {
            display: flex;
            gap: 10px;
        }
        .action-btn {
            padding: 10px 15px;
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .action-btn:hover {
            background-color: #0056b3;
        }
        .delete-btn {
            background-color: #DC3545;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h1>My Properties</h1>
    <?php foreach ($properties as $property): ?>
        <div class="property-card">
            <div class="property-details">
                <p><strong>ID:</strong> <?php echo $property['id']; ?></p>
                <p><strong>Area:</strong> <?php echo $property['area']; ?> sq.ft</p>
                <p><strong>Location:</strong> <?php echo $property['location']; ?></p>
                <p><strong>Price:</strong> <?php echo $property['price']; ?> NPR</p>
            </div>
            <div class="property-actions">
                <a href="view_property.php?id=<?php echo $property['id']; ?>" class="action-btn">View</a>
                <a href="update_property.php?id=<?php echo $property['id']; ?>" class="action-btn">Update</a>
                <a href="delete_property.php?id=<?php echo $property['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this property?');">Delete</a>
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>
<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must log in to view properties.";
    exit;
}

// Fetch approved land properties
$sql_land = "SELECT * FROM land_properties WHERE status = 'approved'";
$stmt_land = $conn->prepare($sql_land);
$stmt_land->execute();
$land_properties = $stmt_land->fetchAll(PDO::FETCH_ASSOC);

// Fetch approved house properties
$sql_house = "SELECT * FROM houseproperties WHERE status = 'approved'";
$stmt_house = $conn->prepare($sql_house);
$stmt_house->execute();
$house_properties = $stmt_house->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Properties</title>
</head>
<body>
    <h1>Available Properties</h1>

    <h2>Land Properties</h2>
    <?php if (!empty($land_properties)): ?>
        <?php foreach ($land_properties as $property): ?>
            <div>
                <h3>Location: <?= htmlspecialchars($property['location']) ?></h3>
                <p>Area: <?= htmlspecialchars($property['area']) ?> sq ft</p>
                <p>Price: NPR <?= htmlspecialchars($property['price']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No land properties available.</p>
    <?php endif; ?>

    <h2>House Properties</h2>
    <?php if (!empty($house_properties)): ?>
        <?php foreach ($house_properties as $property): ?>
            <div>
                <h3>Location: <?= htmlspecialchars($property['location']) ?></h3>
                <p>Floors: <?= htmlspecialchars($property['floors']) ?></p>
                <p>Price: NPR <?= htmlspecialchars($property['price']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No house properties available.</p>
    <?php endif; ?>
</body>
</html>

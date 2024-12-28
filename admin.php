<?php
session_start();
include 'db.php'; // Assuming db.php is your database connection file

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "You must log in as an admin to view this page.";
    exit;
}

// Fetch all land properties with user details
$sql_land = "SELECT lp.*, u.first_name, u.last_name, u.phone, u.email, lp.map_image 
             FROM land_properties lp
             JOIN users u ON lp.user_id = u.id";
$stmt_land = $conn->prepare($sql_land);
$stmt_land->execute();
$land_properties = $stmt_land->fetchAll(PDO::FETCH_ASSOC);

// Fetch all house properties with user details
$sql_house = "SELECT hp.*, u.first_name, u.last_name, u.phone, u.email, hp.map_image
              FROM houseproperties hp
              JOIN users u ON hp.user_id = u.id";
$stmt_house = $conn->prepare($sql_house);
$stmt_house->execute();
$house_properties = $stmt_house->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        /* Add your existing styles here */
    </style>
</head>

<body>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        h2 {
            color: #444;
            margin-bottom: 10px;
        }

        .property {
            background-color: #fafafa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .property h3 {
            color: #333;
            font-size: 1.2em;
        }

        .property p {
            color: #555;
            margin: 5px 0;
        }

        .property h4 {
            margin-top: 15px;
            color: #666;
        }

        .property img {
            max-width: 600px; /* Ensure image scales well */
            height: auto;
            margin: 5px;
            border-radius: 5px;
        }

        .btn {
            padding: 10px 20px;
            margin: 10px 5px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }

        .approve {
            background-color: #28a745;
            color: white;
        }

        .approve:hover {
            background-color: #218838;
        }

        .reject {
            background-color: #dc3545;
            color: white;
        }

        .reject:hover {
            background-color: #c82333;
        }

        .btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .property {
                padding: 15px;
            }

            .property img {
                max-width: 80%; /* Adjust for mobile screens */
            }

            .btn {
                font-size: 0.9em;
                padding: 8px 16px;
            }
        }

        /* User Details Styles */
        .user-details {
            background-color: #fff;
            padding: 20px;
            margin-top: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .user-details h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .user-details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .user-details table th, .user-details table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .user-details table th {
            background-color: #f8f8f8;
            color: #333;
        }

        .user-details table td {
            color: #555;
        }

        /* Tab Styles */
        .tab-btns {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .tab-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .tab-btn:hover {
            background-color: #0056b3;
        }

        .tab-btn.active {
            background-color: #0056b3;
        }

        .property-list {
            display: none;
        }

        .property-list.active {
            display: block;
        }
    </style>
    <div class="container">
        <h1>Admin Panel</h1>

        <!-- Tab buttons for toggling between house and land properties -->
        <div class="tab-btns">
            <button class="tab-btn active" id="viewLandBtn" onclick="toggleTab('land')">View Land</button>
            <button class="tab-btn" id="viewHouseBtn" onclick="toggleTab('house')">View House</button>
        </div>

        <!-- Land Properties -->
        <div class="property-list land active">
            <h2>Land Properties</h2>
            <?php if (!empty($land_properties)): ?>
                <?php foreach ($land_properties as $property): ?>
                    <div class="property">
                        <h3>Location: <?= htmlspecialchars($property['location']) ?></h3>
                        <p>Area: <?= htmlspecialchars($property['area']) ?> sq ft</p>
                        <p>Price: NPR <?= htmlspecialchars($property['price']) ?></p>
                        <p>Uploaded By: <?= htmlspecialchars($property['first_name']) ?> <?= htmlspecialchars($property['last_name']) ?></p>
                        <p>Email: <?= htmlspecialchars($property['email']) ?></p>
                        <p>Phone Number: <?= htmlspecialchars($property['phone']) ?></p>
                        <p>User ID: <?= htmlspecialchars($property['user_id']) ?></p>
                        
                        <h4>Map:</h4>
                        <img src="<?= htmlspecialchars($property['map_image']) ?>" alt="Property Map"> <!-- Increased size automatically based on the updated styles -->

                        <h4>Images:</h4>
                        <?php
    $images = json_decode($property['property_images'], true);
    if ($images && is_array($images)) {
        foreach ($images as $image) {
            echo "<img src='$image' alt='Property Image'>";
        }
    }
?>

                        <button class="btn approve" onclick="handleAction(<?= $property['id'] ?>, 'land', 1)">Approve</button>
                        <button class="btn reject" onclick="handleAction(<?= $property['id'] ?>, 'land', 0)">Reject</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No land properties available.</p>
            <?php endif; ?>
        </div>

        <!-- House Properties -->
        <div class="property-list house">
            <h2>House Properties</h2>
            <?php if (!empty($house_properties)): ?>
                <?php foreach ($house_properties as $property): ?>
                    <div class="property">
                        <h3>Location: <?= htmlspecialchars($property['location']) ?></h3>
                        <p>Floors: <?= htmlspecialchars($property['floors']) ?></p>
                        <p>Bedrooms: <?= htmlspecialchars($property['bedrooms']) ?></p>
                        <p>Living Rooms: <?= htmlspecialchars($property['living_rooms']) ?></p>
                        <p>Kitchens: <?= htmlspecialchars($property['kitchens']) ?></p>
                        <p>Washrooms: <?= htmlspecialchars($property['washrooms']) ?></p>
                        <p>Attached Washrooms: <?= htmlspecialchars($property['attached_washrooms']) ?: 'Not Available' ?></p>
                        <p>Price: NPR <?= htmlspecialchars($property['price']) ?></p>
                        <p>Uploaded By: <?= htmlspecialchars($property['first_name']) ?> <?= htmlspecialchars($property['last_name']) ?></p>
                        <p>Email: <?= htmlspecialchars($property['email']) ?></p>
                        <p>Phone Number: <?= htmlspecialchars($property['phone']) ?></p>
                        <p>User ID: <?= htmlspecialchars($property['user_id']) ?></p>
                        
                        <h4>Map:</h4>
<?php
    // Ensure the map image path is correct
    $mapImage = htmlspecialchars($property['map_image']);
    // Check if the file exists at the specified path
    if (file_exists($mapImage)) {
        echo "<img src='$mapImage' alt='Property Map'>";
    } else {
        echo "<p>Map image not available.</p>";
    }
?>

                        <h4>Images:</h4>
                        <?php
                            $images = json_decode($property['property_images'], true);
                            if ($images && is_array($images)) {
                                foreach ($images as $image) {
                                    echo "<img src='$image' alt='Property Image'>";
                                }
                            }
                        ?>
                        <button class="btn approve" onclick="handleAction(<?= $property['id'] ?>, 'house', 1)">Approve</button>
                        <button class="btn reject" onclick="handleAction(<?= $property['id'] ?>, 'house', 0)">Reject</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No house properties available.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Toggle tab for land and house properties
        function toggleTab(tab) {
            const landTab = document.querySelector('.land');
            const houseTab = document.querySelector('.house');
            const landBtn = document.getElementById('viewLandBtn');
            const houseBtn = document.getElementById('viewHouseBtn');

            if (tab === 'land') {
                landTab.classList.add('active');
                houseTab.classList.remove('active');
                landBtn.classList.add('active');
                houseBtn.classList.remove('active');
            } else {
                houseTab.classList.add('active');
                landTab.classList.remove('active');
                houseBtn.classList.add('active');
                landBtn.classList.remove('active');
            }
        }
    </script>
</body>
</html>

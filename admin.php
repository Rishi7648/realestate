<?php
session_start();
include 'db.php'; // Assuming db.php is your database connection file

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "You must log in as an admin to view this page.";
    exit;
}

// Fetch search keyword if provided
$search_keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch all land properties with user details, filtered by search keyword
$sql_land = "SELECT lp.*, u.first_name, u.last_name, u.phone, u.email, lp.map_image, lp.status 
             FROM land_properties lp
             JOIN users u ON lp.user_id = u.id
             WHERE lp.location LIKE :keyword
             ORDER BY lp.created_at DESC"; // Sorting by creation date (latest first)
$stmt_land = $conn->prepare($sql_land);
$stmt_land->bindValue(':keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
$stmt_land->execute();
$land_properties = $stmt_land->fetchAll(PDO::FETCH_ASSOC);

// Fetch all house properties with user details, filtered by search keyword
$sql_house = "SELECT hp.*, u.first_name, u.last_name, u.phone, u.email, hp.map_image, hp.status
              FROM houseproperties hp
              JOIN users u ON hp.user_id = u.id
              WHERE hp.location LIKE :keyword
              ORDER BY hp.created_at DESC"; // Sorting by creation date (latest first)
$stmt_house = $conn->prepare($sql_house);
$stmt_house->bindValue(':keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            font-size: 2em;
        }
        .tab-btns {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .tab-btn {
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
        .property {
            border-bottom: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 10px;
        }
        .property h3 {
            font-size: 1.5em;
        }
        .property img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .btn {
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
        }
        .approve {
            background-color: #28a745;
            color: white;
        }
        .reject {
            background-color: #dc3545;
            color: white;
        }
        .search-bar input {
            padding: 10px;
            width: 80%;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .search-bar button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .tab-btns {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <button class="logout-btn" onclick="window.location.href='admin_logout.php'">Logout</button>

    <div class="container">
        <h1>Admin Panel</h1>

        <!-- Search Bar -->
        <div class="search-bar" style="margin-bottom: 20px;">
            <form method="GET" action="">
                <input type="text" name="search" value="<?= htmlspecialchars($search_keyword) ?>" placeholder="Search by location">
                <button type="submit">Search</button>
            </form>
        </div>

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
                        <p>Area: <?= htmlspecialchars($property['area']) ?></p>
                        <p>Price: NPR <?= htmlspecialchars($property['price']) ?></p>
                        <p>Status: <?= htmlspecialchars($property['status']) ?></p>
                        <p>Uploaded By: <?= htmlspecialchars($property['first_name']) ?> <?= htmlspecialchars($property['last_name']) ?></p>
                        <p>Email: <?= htmlspecialchars($property['email']) ?></p>
                        <p>Phone Number: <?= htmlspecialchars($property['phone']) ?></p>
                        <h4>Map:</h4>
                        <img src="<?= htmlspecialchars($property['map_image']) ?>" alt="Property Map">
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
                        <p>Price: NPR <?= htmlspecialchars($property['price']) ?></p>
                        <p>Status: <?= htmlspecialchars($property['status']) ?></p>
                        <p>Uploaded By: <?= htmlspecialchars($property['first_name']) ?> <?= htmlspecialchars($property['last_name']) ?></p>
                        <p>Email: <?= htmlspecialchars($property['email']) ?></p>
                        <p>Phone Number: <?= htmlspecialchars($property['phone']) ?></p>
                        <h4>Map:</h4>
                        <img src="<?= htmlspecialchars($property['map_image']) ?>" alt="Property Map">
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
                landTab.classList.remove('active');
                houseTab.classList.add('active');
                landBtn.classList.remove('active');
                houseBtn.classList.add('active');
            }
        }

        // Handle approve/reject action
        function handleAction(propertyId, propertyType, action) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "approve_reject_property.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert("Property status updated!");
                    location.reload();
                }
            };
            xhr.send("property_id=" + propertyId + "&property_type=" + propertyType + "&action=" + action);
        }
    </script>
</body>
</html>

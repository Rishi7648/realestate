<?php
// Include database connection
include('db.php'); // assuming db.php contains PDO connection code

if (isset($_POST['update_house'])) {
    // Sanitize inputs
    $propertyId = $_POST['property_id'];
    $totalFloors = $_POST['total_floors'];
    $bedrooms = $_POST['bedrooms'];
    $livingRooms = $_POST['living_rooms'];
    $kitchens = $_POST['kitchens'];
    $washrooms = $_POST['washrooms'];
    $attachedWashrooms = $_POST['attached_washrooms'];

    // Update property details in the database using PDO
    $sql = "UPDATE properties SET total_floors = ?, bedrooms = ?, living_rooms = ?, kitchens = ?, washrooms = ?, attached_washrooms = ? WHERE id = ? AND type = 'house'";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$totalFloors, $bedrooms, $livingRooms, $kitchens, $washrooms, $attachedWashrooms, $propertyId]);

        echo "House property updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Handle image uploads (if any)
    if (!empty($_FILES['property_images']['name'][0])) {
        $imageFiles = $_FILES['property_images'];

        for ($i = 0; $i < count($imageFiles['name']); $i++) {
            $fileName = $imageFiles['name'][$i];
            $fileTmpName = $imageFiles['tmp_name'][$i];
            $fileSize = $imageFiles['size'][$i];
            $fileError = $imageFiles['error'][$i];

            if ($fileError === 0) {
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png'];

                if (in_array($fileExt, $allowedExts)) {
                    if ($fileSize < 5000000) { // Max size 5MB
                        $newFileName = uniqid('', true) . "." . $fileExt;
                        $fileDestination = 'uploads/' . $newFileName;

                        if (move_uploaded_file($fileTmpName, $fileDestination)) {
                            // Insert image into database using PDO
                            $sql = "INSERT INTO property_images (property_id, image_path) VALUES (?, ?)";
                            try {
                                $stmt = $conn->prepare($sql);
                                $stmt->execute([$propertyId, $fileDestination]);
                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                            }
                        } else {
                            echo "Error uploading file.";
                        }
                    } else {
                        echo "File size too large!";
                    }
                } else {
                    echo "Invalid file type!";
                }
            } else {
                echo "File error: " . $fileError;
            }
        }
    }
}
?>

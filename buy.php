<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Panel</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 600px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
            margin: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .section {
            display: none;
            text-align: center;
        }

        .section.active {
            display: block;
        }

        .search-container {
            margin-top: 20px;
        }

        .search-container input[type="text"] {
            width: 80%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-container button {
            width: 15%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #218838;
        }

        .property-list {
            margin-top: 20px;
        }

        .property-item {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Buyer Panel</h1>
        
        <!-- Initial Button -->
        <div id="view-details">
            <button class="btn" id="view-details-btn">View Details</button>
        </div>

        <!-- Land/House Selection -->
        <div id="land-house-selection" class="section">
            <button class="btn" id="view-land-btn">View Land</button>
            <button class="btn" id="view-house-btn">View House</button>
        </div>

        <!-- Land/Search Page -->
        <div id="land-search" class="section">
            <h2>Search Land</h2>
            <div class="search-container">
                <input type="text" id="land-search-bar" placeholder="Search by location, price, etc.">
                <button type="button" id="search-land-btn">Search</button>
            </div>
            <div class="property-list" id="land-list">
                <!-- Land Properties will be listed here -->
            </div>
        </div>

        <!-- House/Search Page -->
        <div id="house-search" class="section">
            <h2>Search House</h2>
            <div class="search-container">
                <input type="text" id="house-search-bar" placeholder="Search by location, price, etc.">
                <button type="button" id="search-house-btn">Search</button>
            </div>
            <div class="property-list" id="house-list">
                <!-- House Properties will be listed here -->
            </div>
        </div>

    </div>

    <script>
        // Initial button event
        document.getElementById('view-details-btn').onclick = function() {
            document.getElementById('view-details').style.display = 'none';
            document.getElementById('land-house-selection').classList.add('active');
        };

        // Land and House selection events
        document.getElementById('view-land-btn').onclick = function() {
            document.getElementById('land-house-selection').style.display = 'none';
            document.getElementById('land-search').classList.add('active');
        };

        document.getElementById('view-house-btn').onclick = function() {
            document.getElementById('land-house-selection').style.display = 'none';
            document.getElementById('house-search').classList.add('active');
        };

        // Example data for properties
        const landData = [
            { location: 'California', price: '$500,000', area: '1500 sqft' },
            { location: 'Texas', price: '$300,000', area: '1200 sqft' }
        ];

        const houseData = [
            { location: 'New York', price: '$1,200,000', bedrooms: 3, bathrooms: 2 },
            { location: 'Florida', price: '$800,000', bedrooms: 4, bathrooms: 3 }
        ];

        // Search Functionality for Land
        document.getElementById('search-land-btn').onclick = function() {
            const query = document.getElementById('land-search-bar').value.toLowerCase();
            const filteredLand = landData.filter(property =>
                property.location.toLowerCase().includes(query) ||
                property.price.toLowerCase().includes(query) ||
                property.area.toLowerCase().includes(query)
            );
            displayProperties(filteredLand, 'land');
        };

        // Search Functionality for House
        document.getElementById('search-house-btn').onclick = function() {
            const query = document.getElementById('house-search-bar').value.toLowerCase();
            const filteredHouse = houseData.filter(property =>
                property.location.toLowerCase().includes(query) ||
                property.price.toLowerCase().includes(query) ||
                property.bedrooms.toString().includes(query) ||
                property.bathrooms.toString().includes(query)
            );
            displayProperties(filteredHouse, 'house');
        };

        // Display filtered properties
        function displayProperties(properties, type) {
            const propertyList = document.getElementById(type === 'land' ? 'land-list' : 'house-list');
            propertyList.innerHTML = '';
            properties.forEach(property => {
                const propertyItem = document.createElement('div');
                propertyItem.classList.add('property-item');
                propertyItem.innerHTML = `
                    <p><strong>Location:</strong> ${property.location}</p>
                    <p><strong>Price:</strong> ${property.price}</p>
                    <p><strong>${type === 'land' ? 'Area' : 'Bedrooms'}:</strong> ${property[type === 'land' ? 'area' : 'bedrooms']}</p>
                    ${type === 'house' ? `<p><strong>Bathrooms:</strong> ${property.bathrooms}</p>` : ''}
                `;
                propertyList.appendChild(propertyItem);
            });
        }
    </script>
</body>
</html>

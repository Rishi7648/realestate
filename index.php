<?php
session_start(); // Start the session to access session variables
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Estate Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styling for the navigation bar */
        
        /* Styling for the navigation bar */
.navbar {
    display: flex;
    justify-content: center; /* Center all content horizontally */
    align-items: center;
    padding: 10px 20px;
    background-color: #333; /* Dark background for contrast */
    color: #fff; /* White text color */
}

.navbar .brand {
    position: absolute; /* Remove from normal flow */
    left: 20px; /* Keep the brand aligned to the left */
    font-size: 1.5em;
    font-weight: bold;
    color: #ffda79; /* Stylish golden-yellow text */
    text-transform: uppercase;
}

.nav-links-container {
    display: flex;
    justify-content: center; /* Center the navigation links */
}
/*gap of navigation button*/
.nav-links {
    list-style-type: none;
    display: flex;
    gap: 30px;
    margin: 0;
    padding: 0;
}

.nav-links li {
    display: inline;
}

.nav-links a {
    color: #fff;
    text-decoration: none;
    font-size: 1em;
    font-weight: 500;
    padding: 5px 10px;
    transition: background-color 0.3s, color 0.3s;
}
/*when we move cursor on navugation button*/
.nav-links a:hover {
    background-color: #ffda79; /* Highlight effect */
    color: #333; /* Dark text on hover */
    border-radius: 5px;
}

body {
    font-family: Arial, sans-serif;
}

.footer {
    text-align: center;
    padding: 10px;
    background-color: #333;
    color: #fff;
}

    </style>
</head>
<body>

<!-- Navigation Bar with Brand on Left and Links Centered -->
<header>
    <nav class="navbar">
        <div class="brand">
            Real Estate
        </div>
        <div class="nav-links-container">
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="buy.php">Buy</a></li>
                <li><a href="sell.php">Sell</a></li>
                <li><a href="my_property.php?id=PROPERTY_ID"> My property</a></li>
                <li><a href="logout.php" onclick="logout()"> Logout</a></li>
            </ul>
        </div>
    </nav>
</header>

<!-- Main Content Area -->
<main>
    <section class="content">
        <!-- Your main content here -->
    </section>
</main>

<!-- Wrapper for the entire content -->
<div class="wrapper">
    <main>
        <section class="content">
            <!-- Your main content here, including forms or any other content -->
        </section>
    </main>
</div>

<!-- Footer Section -->
<footer class="footer">
    <div class="footer-content">
        <p><strong>Company :</strong> Real Estate Nepal</p>
        <p><strong>Location:</strong> Thamel,Kathmandu</p>
        <p><strong>Contact:</strong> 9899100101</p>
        <p><strong>Email:</strong> contact@realestate.com</p>
    </div>
</footer>

<script src="scripts.js"></script>
</body>
</html>
s
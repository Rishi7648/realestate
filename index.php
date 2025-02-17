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
        /* General Styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('photo.png.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            height: 100vh;
            overflow-y: auto; /* Allow scrolling */
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: center; /* Center the nav links */
            align-items: center;
            padding: 1rem;
            background-color: black;
            position: relative;
            width: 100%;
        }

        /* Brand (Logo) */
        .brand {
            font-size: 1.5em;
            font-weight: bold;
            color: #ffda79;
            text-transform: uppercase;
            position: absolute;
            left: 1rem; /* Align the logo to the left */
        }

        /* Nav Links */
        .nav-links {
            list-style-type: none;
            display: flex;
            gap: 1.5rem;
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

        .nav-links a:hover {
            background-color: #ffda79;
            color: #333;
            border-radius: 5px;
        }

        /* Hamburger Menu (Hidden by Default) */
        .hamburger {
            display: none;
            font-size: 30px;
            cursor: pointer;
            background: none;
            border: none;
            color: white;
        }

        /* Responsive Navigation - Vertical Layout */
        @media (max-width: 768px) {
            .hamburger {
                display: block;
                position: absolute;
                right: 20px;
                top: 15px;
            }

            .nav-links {
                display: none; /* Initially hidden */
                flex-direction: column;
                width: 100%;
                position: absolute;
                top: 60px;
                left: 0;
                background-color: rgba(0, 0, 0, 0.8);
                text-align: left;
                padding: 0px;
            }

            .nav-links.active {
                display: flex; /* Show when active */
            }

            .nav-links li {
                width: 100%;
                padding: 10px;
            }

            .nav-links a {
                display: block;
                width: 100%;
                padding: 10px;
                font-size: 1.2em;
            }
        }

        /* Footer Styles */
        footer {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<header>
    <nav class="navbar">
        <div class="brand">
            Real Estate
        </div>
        <button class="hamburger" onclick="toggleMenu()">☰</button>
        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="buy.php">Buy</a></li>
            <li><a href="sell.php">Sell</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="my_property.php?id=PROPERTY_ID">My Property</a></li>
            <li><a href="logout.php" onclick="logout()">Logout</a></li>
        </ul>
    </nav>
</header>

<!-- Main Content Area -->
<main>
    <section class="content">
        <!-- Your main content here -->
    </section>
</main>

<!-- Footer Section -->
<footer class="footer">
    <div class="footer-content">
        <p><strong>Company :</strong> Real Estate Nepal</p>
        <p><strong>Location:</strong> Thamel, Kathmandu</p>
        <p><strong>Contact:</strong> 9899100101</p>
        <p><strong>Email:</strong> contact@realestate.com</p>
    </div>
</footer>

<script>
    function toggleMenu() {
        var navLinks = document.querySelector(".nav-links");
        navLinks.classList.toggle("active");
    }
</script>

</body>
</html>

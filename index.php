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
            height: 300vh;
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
            padding: 20;
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
            border-radius: 30px;
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
                right: 25px;
                top: -5px;
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
                padding: 0px;
            }

            .nav-links a {
                display: block;
                width: 100%;
                padding: 10px;
                font-size: 1.2em;
            }
        }
/* Ensure full height for scrolling */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow-y: auto; /* Allow vertical scrolling */
    display: flex;
    flex-direction: column;
}

/* Main Content Section */
main {
    flex: 1; /* Allows content to expand */
    overflow-y: auto; /* Enables scrolling */
    padding-bottom: 50px; /* Prevents overlap with footer */
}

/* Home Page Content */
.content {
    min-height: 100vh; /* Ensures enough space for scrolling */
    padding: 20px;
}

/* Scrollable Footer */
.footer {
    background-color: black;
    color: white;
    text-align: center;
    padding: 1rem;
    width: 100%;
    position: relative;
    bottom: 0;
}

/* Fix Background Image */
body {
    background: url('photo.png.jpg') no-repeat center center;
    background-size: cover;
    background-attachment: fixed; /* Keeps background fixed while scrolling */
    color: white;
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

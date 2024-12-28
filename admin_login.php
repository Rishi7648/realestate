<?php
include 'db.php';
session_start();

// Check if the admin is already logged in
if (isset($_SESSION['admin_id'])) {
    // Admin is logged in, show the logout button
    if (isset($_POST['logout'])) {
        // Destroy the session to log out the admin
        session_destroy();
        header("Location: admin_login.php"); // Redirect to login page
        exit();
    }
} else {
    // Handle admin login and signup logic as before
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['signup'])) {
            // Handle admin signup
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Check if admin email already exists
            $sql = "SELECT * FROM admin WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $existingAdmin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingAdmin) {
                echo "<p>Email already registered!</p>";
            } else {
                // Insert new admin into the database
                $sql = "INSERT INTO admin (username, email, password) VALUES (:username, :email, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password]);
                echo "<p>Admin account created successfully. You can now login.</p>";
            }
        } elseif (isset($_POST['login'])) {
            // Handle admin login
            $email = $_POST['email'];
            $password = $_POST['password'];

            $sql = "SELECT * FROM admin WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['username'] = $admin['username'];

                header("Location: admin.php");
                exit();
            } else {
                echo "<p>Invalid admin credentials!</p>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login & Signup</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .form-container h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }
        .form-container input {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        .form-container button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
        }
        .form-container p {
            font-size: 14px;
            color: #666;
            margin-top: 15px;
        }
        .form-container p a {
            color: #4e54c8;
            text-decoration: none;
        }
        .toggle-form {
            margin-top: 20px;
            cursor: pointer;
            color: #4e54c8;
        }
        .password-container {
            position: relative;
        }
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .logout-btn {
            width: 100%;
            padding: 15px;
            background: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
        }
    </style>
</head>
<body>

<?php if (isset($_SESSION['admin_id'])): ?>
    <!-- If admin is logged in, show the admin panel with the logout button -->
    <div class="form-container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <form method="POST">
            <button type="submit" name="logout" class="logout-btn">Logout</button>
        </form>
    </div>
<?php else: ?>
    <!-- Admin Login & Signup forms as before -->
    <div class="form-container" id="login-form">
        <h2>Admin Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Admin Email" required>
            <div class="password-container">
                <input type="password" id="login-password" name="password" placeholder="Password" required>
                <span class="eye-icon" onclick="togglePassword('login-password')">&#128065;</span>
            </div>
            <button type="submit" name="login">Login as Admin</button>
        </form>
        <p class="toggle-form" onclick="toggleForm('signup-form')">Don't have an account? Signup</p>
    </div>

    <div class="form-container" id="signup-form" style="display: none;">
        <h2>Admin Signup</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Admin Email" required>
            <div class="password-container">
                <input type="password" id="signup-password" name="password" placeholder="Password" required>
                <span class="eye-icon" onclick="togglePassword('signup-password')">&#128065;</span>
            </div>
            <button type="submit" name="signup">Signup as Admin</button>
        </form>
        <p class="toggle-form" onclick="toggleForm('login-form')">Already have an account? Login</p>
    </div>
<?php endif; ?>

<script>
    function toggleForm(formId) {
        document.getElementById('login-form').style.display = formId === 'login-form' ? 'block' : 'none';
        document.getElementById('signup-form').style.display = formId === 'signup-form' ? 'block' : 'none';
    }

    function togglePassword(inputId) {
        var passwordField = document.getElementById(inputId);
        var icon = passwordField.nextElementSibling;

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.innerHTML = "&#128064;"; // Eye open icon
        } else {
            passwordField.type = "password";
            icon.innerHTML = "&#128065;"; // Eye closed icon
        }
    }
</script>
</body>
</html>

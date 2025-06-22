<?php
session_start();
include("connect.php"); // Ensure this connects to $conn

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Admin login (hardcoded)
    if ($username === "admin123" && $password === "admin@123") {
        $_SESSION["username"] = $username;
        echo '<script>alert("Admin Login Successful!"); window.location.href="admin.php";</script>';
        exit();
    }

    // Normal user login
    $stmt = $conn->prepare("SELECT password FROM signup_table WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row["password"];

        if ($password === $hashedPassword) {
            $_SESSION["username"] = $username;
            echo '<script>alert("Login Successful!"); window.location.href="home.html";</script>';
            exit();
        } else {
            echo '<script>alert("Incorrect password!"); window.location.href="login.php";</script>';
        }
    } else {
        echo '<script>alert("Username does not exist! Please sign up."); window.location.href="login.php";</script>';
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Cafeteria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: 'Roboto', sans-serif;
            background-image: url("finale.jpg");
            background-size:cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* .bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: -1;
        } */

        .container {
            background: linear-gradient(to bottom right, #ffffff, #f1f1f1);
            border-radius: 15px;
            box-shadow: 3px 5px 3px rgba(150, 126, 126, 0.5);
            padding: 30px;
            width: 400px;
            margin: 20px 10px;
            margin-top: 130px;
            text-align: center;
            animation: slideIn 1s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            color: #555;
            font-weight: bold;
            margin: 15px 0 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007BFF;
        }

        .button {
            background-color: #007BFF;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }

        p {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
        }

        p a {
            color: #007BFF;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        p a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        footer {
            margin-top: auto;
            text-align: center;
            padding: 10px;
            color: white;
        }

        @media (max-width: 400px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-overlay"></div>

    <div class="container">
        <h1>Login Here</h1>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Enter your username..." required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Enter your password..." required>

            <button type="submit" class="button">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>

    <footer>
        &copy; 2025 Cafeteria Management System. All Rights Reserved.
    </footer>
</body>
</html>

<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    $stmt = $conn->prepare("SELECT password FROM signup_table WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) 
    {
        echo '<script>alert("Username already exist..! Try something else.."); window.location.href="signup.html";</script>';
        exit();
    }

    if ($password !== $confirm_password) 
    {
        echo '<script>alert("Passwords do not match!"); window.location.href="signup.html";</script>';
        exit();
    }

    // $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO signup_table (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) 
    {
        echo '<script>alert("Signup Successful!"); window.location.href="cafeuser.php";</script>';
    } 
    else 
    {
        echo '<script>alert("Error: Unable to register!");</script>';
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up - Cafeteria Management</title>
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;/
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-image: url("finale.jpg"); /* Add a café-related image */
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            height: 100vh;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .container {
            margin-top: 100px;
            min-width: 450px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            text-align: center;
            box-shadow: 0.3rem 0.4rem 4px #ab9797;
            animation: slideIn 1s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        h1 {
            color: black; /* Warm Coffee Brown */
            font-size: 28px;
            margin-bottom: 15px;
            animation: fadeIn 2s ease-in-out;
        }

        .input-group {
            text-align: left;
            margin-bottom: 12px;
        }

        .input-group label {
            font-size: 16px;
            color: #171717;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #272020;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .input-group input:focus {
            border-color: #d35400;
            box-shadow: 0 0 5px rgba(211, 84, 0, 0.5);
        }

        .button {
            width: 100%;
            padding: 12px;
            background-color: #ff0202;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background-color: #be0606;
            transform: translateY(-3px);
        }

        .links {
            margin-top: 10px;
            font-size: 14px;
        }

        .links a {
            color: blue;
            font-style: italic;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .links a:hover {
            color: rgb(0, 34, 255);
            font-weight: bold;
        }

        p {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
        }

        p a {
            font-style: italic;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        p a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        footer {
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #4e637000;
            color: white;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Sign Up</h1>
        <form action="SignUp.php" method="POST" name="signupForm" onsubmit="return validateForm()">
            <div class="input-group">
                <label>Username:</label>
                <input type="text" placeholder="Enter your username..." name="username" required>
            </div>

            <div class="input-group">
                <label>Password:</label>
                <input type="password" name="password" placeholder="Enter your password..." required autocomplete="off">
            </div>

            <div class="input-group">
                <label>Repeat Password:</label>
                <input type="password" placeholder="Repeat your password..." name="confirm_password" required>
            </div>
<!-- 
            <input type="checkbox" name="remember" checked autocomplete="off"> Remember Me

            <p>By creating an account, you agree to our 
                <a href="policy.html">Terms & Privacy</a>.
            </p> -->

            <button type="submit" class="button">Sign Up</button>

            <div class="links">
                <p>Already have an account? 
                    <a href="login.php">Log in</a>
                </p>
            </div>
        </form>
    </div>
    <footer>
        &copy; 2025 Cafeteria Management System. All Rights Reserved.
    </footer>
</body>
</html>
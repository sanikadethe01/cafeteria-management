<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cafe User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            padding: 40px;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .form-container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: slideIn 1s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            animation: fadeIn 1.5s ease-in-out;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="tel"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            transition: box-shadow 0.3s ease-in-out;
        }

        input[type="text"]:focus,
        input[type="tel"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus {
            box-shadow: 0 0 10px rgba(81, 203, 238, 1);
            border-color: rgba(81, 203, 238, 1);
        }

        input[type="submit"] {
            margin-top: 25px;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
            transform: translateY(-3px);
        }

        .note {
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
            color: #777;
            animation: fadeIn 2s ease-in-out;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            color: green;
            animation: fadeIn 1.5s ease-in-out;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<?php
session_start();
include("connect.php");
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $fname = trim($_POST["first_name"]);
    $lname = trim($_POST["last_name"]);
    $contact = trim($_POST["contact_no"]);
    $dob = $_POST["dob"];
    $age = $_POST["age"];
    $address = trim($_POST["address"]);


    // if (!$conn) {
    //     die("<div class='message error'>Connection failed: " . mysqli_connect_error() . "</div>");
    // }

    $username_lower = strtolower($username);

    $signup_check = $conn->prepare("SELECT * FROM signup_table WHERE LOWER(username) = ?");
    $signup_check->bind_param("s", $username_lower);
    $signup_check->execute();
    $signup_result = $signup_check->get_result();

    if ($signup_result->num_rows === 1) 
    {
        $check = $conn->prepare("SELECT * FROM Cafeuser WHERE username = ? OR contact_no = ?");
        $check->bind_param("ss", $username, $contact);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = "<div class='message error'>Username or Contact Number already exists!</div>";
        } else {
            $stmt = $conn->prepare("INSERT INTO Cafeuser (username, first_name, last_name, contact_no, dob, age, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssis", $username, $fname, $lname, $contact, $dob, $age, $address);

            if ($stmt->execute()) {
                echo "<script>alert('Cafe user registered successfully!'); window.location.href='login.php';</script>";
                exit;
            } else {
                $message = "<div class='message error'>Error: " . $stmt->error . "</div>";
            }

            $stmt->close();
        }

        $check->close();
    }
    else {
        $message = "<div class='message error'>Please sign up first before registering as a cafe user.</div>";
    }
    $signup_check->close();
    $conn->close();
}
?>

<div class="form-container">
    <h2>Cafe User Registration</h2>
    <form method="POST" action="">
        <label for="username">Username</label>
        <input type="text" name="username" required>

        <label for="first_name">First Name</label>
        <input type="text" name="first_name" required>

        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" required>

        <label for="contact_no">Contact No</label>
        <input type="number" name="contact_no" required>

        <label for="dob">Date of Birth</label>
        <input type="date" name="dob" id="dob" required>

        <label for="age">Age</label>
        <input type="number" name="age" id="age" readonly required>

        <label for="address">Address</label>
        <input type="text" name="address" required>

        <input type="submit" value="Register">
    </form>
    <div class="note">* You must sign up first. If you haven’t already, please <a href="SignUp.php">sign up here</a>.</div>
    <?= $message ?>
</div>

<script>
    function calculateAge() {
        const dobInput = document.getElementById('dob');
        const ageInput = document.getElementById('age');

        dobInput.addEventListener('input', function () {
            const dob = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            if (!isNaN(age) && age > 0) {
                ageInput.value = age;
            } else {
                ageInput.value = "";
            }
        });
    }

    window.onload = calculateAge;
</script>

</body>
</html>
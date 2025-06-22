<?php
ob_start();
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #434343, #000000);
            color: white;
            text-align: center;
            padding-top: 50px;
        }
        h1 {
            color: cyan;
        }
        .button-container {
            margin-top: 20px;
        }
        .admin-button {
            padding: 15px 30px;
            margin: 10px;
            font-size: 16px;
            background-color: #00bcd4;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
        }
        .admin-button:hover {
            background-color: #0097a7;
            transform: scale(1.05);
        }
        .record-container {
            margin-top: 40px;
            text-align: left;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 10px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            color: white;
        }
        table, th, td {
            border: 1px solid #aaa;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #0097a7;
        }
        form.inline-form {
            display: inline-block;
            margin: 10px;
        }
        input[type="text"], input[type="number"], input[type="email"] {
            padding: 8px;
            border-radius: 5px;
            border: none;
            margin: 5px;
        }
    </style>
</head>
<body>
    <h1>Welcome Admin</h1>

    <div class="button-container">
        <form method="post" class="inline-form">
            <button name="view_users" class="admin-button">View Users</button>
        </form>
        <form method="post" class="inline-form">
            <button name="view_orders" class="admin-button">View Orders</button>
        </form>
        <form method="post" class="inline-form">
            <button name="view_items" class="admin-button">View Items</button>
        </form>
    </div>

    <div class="record-container">
        <?php
        include "connect.php";

        // Add User
        if (isset($_POST['add_user'])) {
            $username = $_POST['username'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $contact = $_POST['contact'];
            $dob = $_POST['dob'];
            $age = $_POST['age'];
            $address = $_POST['address'];

            $checkUser = $conn->query("SELECT * FROM cafeuser WHERE username='$username'");

            if ($checkUser->num_rows > 0) {
                echo "<p style='color: red;'>User <strong>$username</strong> already exists!</p>";
            } else {
                $conn->query("INSERT INTO signup_table (username, password) VALUES ('$username', 'default123')");
                $conn->query("INSERT INTO cafeuser (username, first_name, last_name, contact_no, dob, age, address) 
                              VALUES ('$username', '$fname', '$lname', '$contact', '$dob', $age, '$address')");
                echo "<p>User <strong>$username</strong> added successfully!</p>";
            }
        }

        // Remove User
        if (isset($_POST['remove_user'])) {
            $username = $_POST['remove_username'];
            $checkUser = $conn->query("SELECT * FROM cafeuser WHERE username='$username'");
            if ($checkUser->num_rows > 0) {
                $conn->query("DELETE FROM cafeuser WHERE username='$username'");
                $conn->query("DELETE FROM signup_table WHERE username='$username'");
                echo "<p>User <strong>$username</strong> removed successfully!</p>";
            } else {
                echo "<p style='color: red;'>User <strong>$username</strong> not found!</p>";
            }
        }

        // Add Item
        if (isset($_POST['add_item'])) {
            $item_id = $_POST['item_id'];
            $name = $_POST['item_name'];
            $desc = $_POST['item_desc'];
            $price = $_POST['item_price'];
            $checkItem = $conn->query("SELECT * FROM items WHERE item_id=$item_id");
            if ($checkItem->num_rows > 0) {
                echo "<p style='color: red;'>Item ID <strong>$item_id</strong> already exists!</p>";
            } else {
                $conn->query("INSERT INTO items (item_id, name, description, price) VALUES ($item_id, '$name', '$desc', $price)");
                echo "<p>Item <strong>$name</strong> added successfully!</p>";
            }
        }

        // Remove Item
        if (isset($_POST['remove_item'])) {
            $item_id = $_POST['remove_item_id'];
            $checkItem = $conn->query("SELECT * FROM items WHERE item_id=$item_id");
            if ($checkItem->num_rows > 0) {
                $conn->query("DELETE FROM items WHERE item_id=$item_id");
                echo "<p>Item ID <strong>$item_id</strong> removed successfully!</p>";
            } else {
                echo "<p style='color: red;'>Item ID <strong>$item_id</strong> not found!</p>";
            }
        }

        // View Users
        if (isset($_POST['view_users'])) {
            echo "<h2>Users</h2>";
            $sql = "SELECT * FROM cafeuser";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<table><tr><th>Username</th><th>First Name</th><th>Last Name</th><th>Contact</th><th>DOB</th><th>Age</th><th>Address</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['username']}</td><td>{$row['first_name']}</td><td>{$row['last_name']}</td><td>{$row['contact_no']}</td><td>{$row['dob']}</td><td>{$row['age']}</td><td>{$row['address']}</td></tr>";
                }
                echo "</table>";
            } else {
                echo "No users found.";
            }
        }

        // View Orders
        if (isset($_POST['view_orders'])) {
            echo "<h2>Orders</h2>";
            $sql = "SELECT * FROM ordertable";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<table><tr><th>Order ID</th><th>Username</th><th>Item ID</th><th>Quantity</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['order_id']}</td><td>{$row['username']}</td><td>{$row['item_id']}</td><td>{$row['quantity']}</td></tr>";
                }
                echo "</table>";
            } else {
                echo "No orders found.";
            }
        }

        // View Items
        if (isset($_POST['view_items'])) {
            echo "<h2>Items</h2>";
            $sql = "SELECT * FROM items";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<table><tr><th>Item ID</th><th>Name</th><th>Description</th><th>Price</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['item_id']}</td><td>{$row['name']}</td><td>{$row['description']}</td><td>{$row['price']}</td></tr>";
                }
                echo "</table>";
            } else {
                echo "No items found.";
            }
        }

        $conn->close();
        ?>
    </div>

    <!-- Forms for Add / Remove -->
    <div class="record-container">
        <h2>Add New User</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="fname" placeholder="First Name" required>
            <input type="text" name="lname" placeholder="Last Name" required>
            <input type="text" name="contact" placeholder="Contact No" required>
            <input type="text" name="dob" placeholder="YYYY-MM-DD" required>
            <input type="number" name="age" placeholder="Age" required>
            <input type="text" name="address" placeholder="Address" required>
            <button name="add_user" class="admin-button">Add User</button>
        </form>

        <h2>Remove User</h2>
        <form method="post">
            <input type="text" name="remove_username" placeholder="Username" required>
            <button name="remove_user" class="admin-button">Remove User</button>
        </form>

        <h2>Add New Item</h2>
        <form method="post">
            <input type="number" name="item_id" placeholder="Item ID" required>
            <input type="text" name="item_name" placeholder="Name" required>
            <input type="text" name="item_desc" placeholder="Description" required>
            <input type="number" name="item_price" step="0.01" placeholder="Price" required>
            <button name="add_item" class="admin-button">Add Item</button>
        </form>

        <h2>Remove Item</h2>
        <form method="post">
            <input type="number" name="remove_item_id" placeholder="Item ID" required>
            <button name="remove_item" class="admin-button">Remove Item</button>
        </form>
    </div>
</body>
</html>

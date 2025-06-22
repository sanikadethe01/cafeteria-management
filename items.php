<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Your Order</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #2574a1;
            margin-top: 20px;
        }
        /* Navigation Bar */
        nav {
            background-color: rgb(3, 3, 3);
            padding: 10px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            padding: 10px 15px;
            transition: 0.3s ease-in-out;
        }

        nav a:hover {
            color: red;
            transform: scale(1.05);
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group input[type="text"] {
            width: 50%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #2574a1;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        input[type="number"] {
            width: 60px;
            padding: 5px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }

        input[type="checkbox"] {
            transform: scale(1.2);
        }

        .submit-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #2574a1;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .submit-btn:hover {
            background-color: #1e6bd7;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <nav>
        <span style="color: cyan; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">Cafeteria Management System</span>
        <a href="home.html">HOME</a>
        <a href="items.php">PLACE_ORDER</a>
        <a href="contactus.html">CONTACT US</a>
        <a href="Feedback.php">FEEDBACK</a>
        <a href="logout.php">LOGOUT</a>
    </nav>
    <div class="container">
        <h2>Place Your Order</h2>
        <form method="POST" action="orders.php">
            <div class="form-group">
                Username: <input type="text" name="username" placeholder="Enter your username" required>
            </div>
            <table>
                <tr>
                    <th>Select</th>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
                <?php
                include 'connect.php';
                $sql = "SELECT * FROM items";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td><input type='checkbox' name='item[]' value='{$row['item_id']}'></td>
                        <td>{$row['name']}</td>
                        <td>{$row['description']}</td>
                        <td>₹{$row['price']}</td>
                        <td><input type='number' name='quantity[{$row['item_id']}]' min='1' value='1'></td>
                      </tr>";
                }
                ?>
            </table>
            <button type="submit" class="submit-btn">Place Order</button>
        </form>
    </div>
</body>
</html>

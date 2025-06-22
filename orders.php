<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $items = $_POST['item'] ?? [];
    $quantities = $_POST['quantity'] ?? [];

    if (empty($username) || empty($items)) {
        echo "<script>alert('Please enter a username and select at least one item.'); window.history.back();</script>";
        exit;
    }

    // Check if user exists
    $check_user = $conn->prepare("SELECT * FROM Cafeuser WHERE username = ?");
    $check_user->bind_param("s", $username);
    $check_user->execute();
    $result = $check_user->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Username not found. Please register first.'); window.history.back();</script>";
        exit;
    }

    // Prepare statements
    $stmt = $conn->prepare("INSERT INTO OrderTable (username, item_id, quantity) VALUES (?, ?, ?)");
    $get_price = $conn->prepare("SELECT price FROM Items WHERE item_id = ?");
    $insert_bill = $conn->prepare("INSERT INTO Bill (bill_id, order_id, amount, date) VALUES (?, ?, ?, ?)");

    $totalAmount = 0;
    $orderIds = [];
    $ordered_items = [];
    $date = date('Y-m-d');
    $bill_id = uniqid(); // Or use NULL if your DB auto-increments

    foreach ($items as $item_id) {
        $quantity = isset($quantities[$item_id]) ? (int)$quantities[$item_id] : 1;

        // Insert into OrderTable
        $stmt->bind_param("sii", $username, $item_id, $quantity);
        $stmt->execute();
        $order_id = $conn->insert_id;
        $orderIds[] = $order_id;

        // Get item price
        $get_price->bind_param("i", $item_id);
        $get_price->execute();
        $priceResult = $get_price->get_result()->fetch_assoc();
        $price = $priceResult['price'];

        $total = $price * $quantity;
        $totalAmount += $total;

        // Store for bill display
        $ordered_items[] = [
            'item_id' => $item_id,
            'quantity' => $quantity,
            'price' => $price
        ];

        // Insert into Bill
        $insert_bill->bind_param("sids", $bill_id, $order_id, $total, $date);
        $insert_bill->execute();
    }

    // Show Bill Summary
    echo "
    <h2 style='text-align:center;'>Bill Summary</h2>
    <div style='max-width:600px;margin:auto;padding:20px;background:#f9f9f9;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.1);'>
        <p><strong>Username:</strong> $username</p>
        <p><strong>Date:</strong> $date</p>
        <hr>
        <h3>Ordered Items:</h3>
        <table style='width:100%; border-collapse:collapse; margin-bottom:20px;'>
            <thead>
                <tr style='background:#eee;'>
                    <th style='text-align:left;padding:8px;'>Item ID</th>
                    <th style='text-align:left;padding:8px;'>Quantity</th>
                    <th style='text-align:left;padding:8px;'>Price (₹)</th>
                    <th style='text-align:left;padding:8px;'>Total (₹)</th>
                </tr>
            </thead>
            <tbody>";

    foreach ($ordered_items as $item) {
        $item_total = $item['quantity'] * $item['price'];
        echo "<tr>
                <td style='padding:8px;'>{$item['item_id']}</td>
                <td style='padding:8px;'>{$item['quantity']}</td>
                <td style='padding:8px;'>₹{$item['price']}</td>
                <td style='padding:8px;'>₹$item_total</td>
            </tr>";
    }

    echo "  </tbody>
        </table>
        <hr>
        <p style='font-size:18px;'><strong>Total Amount:</strong> ₹$totalAmount</p>
        <div style='text-align:center;margin-top:30px;'>
            <img src='pay.jpg' alt='QR Code for Payment' style='max-width:200px;border-radius:8px;box-shadow:0 0 8px rgba(0,0,0,0.1);'>
            <p style='margin-top:10px;color:#666;'>Scan to Pay</p>
        </div>
        <div style='text-align:center;margin-top:25px;'>
            <a href='items.php' style='padding:10px 20px;background:#2574a1;color:white;text-decoration:none;border-radius:5px;'>Place New Order</a>
        </div>
    </div>";

} else {
    echo "<script>alert('Invalid request.'); window.location.href='items.php';</script>";
}
?>

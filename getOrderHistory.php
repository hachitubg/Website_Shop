<?php
// getOrderHistory.php

// Include the database configuration file
include("config.php");

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["is_logged_in"]) || !$_SESSION["is_logged_in"]) {
    // You may want to handle this case appropriately, e.g., redirect to the login page
    exit("User not logged in");
}

// Get the user ID
$userId = $_SESSION["user_id"];

// Query to get order history with product details and status
$orderQuery = "SELECT o.OrderDate, p.ProductName, o.Quantity, o.TotalAmount, o.PaymentStatus
               FROM orders o
               INNER JOIN products p ON o.ProductID = p.ProductID
               WHERE o.UserID = $userId";
$orderResult = mysqli_query($conn, $orderQuery);

// Check for errors
if (!$orderResult) {
    die('Error: ' . mysqli_error($conn));
}

// Generate HTML for order history
$orderHistoryHTML = '<h2>Lịch sử mua hàng</h2>';

// Check if there are rows in the result set
if (mysqli_num_rows($orderResult) > 0) {
    $orderHistoryHTML .= '<table border="1"><tr><th>Ngày đặt hàng</th><th>Tên sản phẩm</th><th>Số lượng</th><th>Tổng tiền</th><th>Trạng thái</th></tr>';

    while ($order = mysqli_fetch_assoc($orderResult)) {
        $orderDate = $order['OrderDate'];
        $productName = $order['ProductName'];
        $quantity = $order['Quantity'];
        $totalAmount = $order['TotalAmount'];
        $paymentStatus = $order['PaymentStatus'];

        $orderHistoryHTML .= "<tr>
                                  <td>$orderDate</td>
                                  <td>$productName</td>
                                  <td>$quantity</td>
                                  <td>$totalAmount VND</td>
                                  <td>$paymentStatus</td>
                              </tr>";
    }

    $orderHistoryHTML .= '</table>';
} else {
    // Display message when there is no order history
    $orderHistoryHTML .= '<p>Bạn chưa thanh toán đơn hàng nào!</p>';
}

// Close the database connection
mysqli_close($conn);

// Return the HTML for order history
echo $orderHistoryHTML;
?>

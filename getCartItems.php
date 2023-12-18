<?php
// getCartItems.php

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

// Query to get cart items
$cartQuery = "SELECT p.ProductName, c.Quantity, p.Price, c.ProductID, c.ShoppingCartID
              FROM ShoppingCart c
              INNER JOIN Products p ON c.ProductID = p.ProductID
              WHERE c.UserID = $userId";
$cartResult = mysqli_query($conn, $cartQuery);

// Check for errors
if (!$cartResult) {
    die('Error: ' . mysqli_error($conn));
}

// Generate HTML for the cart items
$cartItemsHTML = '<h2>Giỏ hàng</h2>';

// Check if there are any items in the cart
if (mysqli_num_rows($cartResult) > 0) {
    $cartItemsHTML .= '<table border="1"><tr><th>Tên sản phẩm</th><th>Số lượng</th><th>Đơn giá</th><th>Tổng cộng</th><th>Action</th></tr>';

    while ($cartItem = mysqli_fetch_assoc($cartResult)) {
        $productName = $cartItem['ProductName'];
        $quantity = $cartItem['Quantity'];
        $price = number_format($cartItem['Price'], 0, ',', '.');
        $totalPrice = number_format($quantity * $price, 0, ',', '.');

        // Check if 'ShoppingCartID' key is present in the $cartItem array
        $cartId = isset($cartItem['ShoppingCartID']) ? $cartItem['ShoppingCartID'] : '';

        $cartItemsHTML .= "<tr>
                              <td>$productName</td>
                              <td>$quantity</td>
                              <td>$price VND</td>
                              <td>$totalPrice VND</td>
                              <td><button onclick=\"removeFromCart($cartId)\">Xóa</button></td>
                           </tr>";
    }

    $cartItemsHTML .= '</table>';
} else {
    $cartItemsHTML .= '<p>Không có sản phẩm nào trong giỏ hàng.</p>';
}

// Close the database connection
mysqli_close($conn);

// Return the HTML for cart items
echo $cartItemsHTML;
?>

<?php
// checkout.php

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
$cartQuery = "SELECT ProductID, Quantity FROM ShoppingCart WHERE UserID = $userId";
$cartResult = mysqli_query($conn, $cartQuery);

// Check for errors
if (!$cartResult) {
    die('Error: ' . mysqli_error($conn));
}

// Process each item in the cart
while ($cartItem = mysqli_fetch_assoc($cartResult)) {
    $productId = $cartItem['ProductID'];
    $quantity = $cartItem['Quantity'];

    // Get product details
    $productQuery = "SELECT * FROM Products WHERE ProductID = $productId";
    $productResult = mysqli_query($conn, $productQuery);
    $product = mysqli_fetch_assoc($productResult);

    // Calculate total amount
    $totalAmount = $quantity * $product['Price'];

    // Insert order information into the Orders table
    $insertOrderQuery = "INSERT INTO Orders (UserID, ProductID, Quantity, TotalAmount) VALUES ($userId, $productId, $quantity, $totalAmount)";
    mysqli_query($conn, $insertOrderQuery);

    // Update product quantity in the Products table
    $updateProductQuery = "UPDATE Products SET Quantity = Quantity - $quantity WHERE ProductID = $productId";
    mysqli_query($conn, $updateProductQuery);
}

// Clear the shopping cart
$clearCartQuery = "DELETE FROM ShoppingCart WHERE UserID = $userId";
mysqli_query($conn, $clearCartQuery);

// Close the database connection
mysqli_close($conn);

// Show a success message
echo "<script>alert('Thanh toán thành công!'); window.location.href = 'dashboard.php';</script>";
?>

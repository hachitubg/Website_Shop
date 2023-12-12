<?php
// removeCartItem.php

// Include the database configuration file
include("config.php");

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["is_logged_in"]) || !$_SESSION["is_logged_in"]) {
    // You may want to handle this case appropriately, e.g., redirect to login page
    exit("User not logged in");
}

// Get the user ID
$userId = $_SESSION["user_id"];

// Get the product ID to remove
$cartId = isset($_POST['cartId']) ? $_POST['cartId'] : '';

// Validate input (you might want to do more validation)
if (!is_numeric($userId) || !is_numeric($cartId)) {
    exit("Invalid input");
}

// Remove the item from the cart
$removeCartItemQuery = "DELETE FROM ShoppingCart WHERE ShoppingCartID = $cartId";
$removeCartItemResult = mysqli_query($conn, $removeCartItemQuery);

// Check for errors
if (!$removeCartItemResult) {
    die('Error: ' . mysqli_error($conn));
}

// Close the database connection
mysqli_close($conn);

// Return a success message
echo "Item removed from the cart successfully";
?>
